<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Google_Service_Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;

// Include Models
use App\Models\User;
use App\Models\Media;
use App\Models\Agent;
use App\Models\Estate;
use App\Models\Seller;
use App\Models\Notary;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Template;
use App\Models\EstateLog;
use App\Models\RealEstate;
use App\Models\EstateEvent;
use App\Models\EstateOffre;
use App\Models\EstateTicket;
use App\Models\EstateRemark;
use App\Models\EstateStatus;
use App\Models\EstateDetail;
use App\Models\EstateComment;
use App\Models\EstateReminder;
use App\Models\EstateResolution;
use App\Models\TemplateReminder;
use App\Models\EstateRealEstate;

use App\Mail\MailableEmail;
use App\Mail\EmailT;
use Illuminate\Support\Facades\Mail;

use BadChoice\Handesk\Handesk;
use BadChoice\Handesk\Ticket;

class EstateController extends Controller
{

    private $client;
    private $user;
    private $calendarId;
    private $updated;
    private $deleted;
    private $objectTicket;

    /**
     * Set variables
     */
    public function __construct()
    {
        // Init null the client of google
        $this->client = null;
        // Init the ID of the calendar to connect
        $this->calendarId = "primary";
        // To know is value was updated
        $this->updated = false;
        // To know is value was deleted
        $this->deleted = false;
        // Init object to use the class Ticket
        $this->objectTicket = new Ticket();
    }

    /**
     * Update Data generic
     * @var model is model to update
     * @var field is the field to update NOTE : the name of field should be exactly as database
     * @var value is the new value to put
     * @var id is the id for register to update
     * @var estate_id is the estate id for register to update
     * @var field_log is the value to the name of field without processing
     */
    private function updateData($model, $field, $value, $id, $estate_id, $field_log)
    {
        // Get all register
        $register = $model::find($id);
        $oldValue = $register->$field;
        // Verify if the new value is different to current value
        if ($register->$field != $value) {
            try {
                // Update value where
                $model::where('id', '=', $id)
                    ->update([$field => $value]);
                // Updated true
                $this->updated = true;
                //Create new log about update
                EstateLog::create([
                    'estate_id' => $estate_id,
                    'user_id' => Auth::user()->id,
                    'old_value' => $oldValue,
                    'new_value' => $value,
                    'field' => $field_log
                ]);
                if ($field == 'category') {
                    // Getting Last Status of this estate
                    $lastStatus = $this->getLastStatus($id);
                    // Update value where
                    $date = date('Y-m-d H:i:s');
                    EstateStatus::where('id', '=', $lastStatus['id'])
                        ->update(['stop_at' => $date]);
                    // Create status log
                    EstateStatus::create([
                        'estate_id' => $id,
                        'category_id' => $value,
                        'user_id' => Auth::user()->id,
                        'start_at' => $date,
                        'stop_at' => null
                    ]);
                }
            } catch (\Exception $e) {
                // Updated false
                $this->updated = $e->getMessage();
            }
        }
        // Return updated
        return $this->updated;
    }

    /**
     * Delete Data generic
     * @var model is model to update
     * @var id is the id for register to delete
     */
    private function deleteData($model, $id)
    {
        try {
            // Delete value where
            $var = $model::where('id', '=', $id)
                ->delete();
            // Deleted true
            $this->deleted = true;
        } catch (\Exception $e) {
            // Deleted false
            $this->deleted = false;
        }
        // Return deleted
        return $this->deleted;
    }

    /**
     * Get Client
     * @var google_token is the google token of user
     */
    private function getClient($google_token)
    {
        // Set the session varible to request a new token in false
        session()->put('requireGoogleToken', false);
        // Get the credentals of the app
        $credentialsPath = storage_path('keys/client_secret.json');
        // Create and save a new Google_Client instance
        $this->client = new Google_Client();
        // Set the scopes to google calendar
        $this->client->setScopes(Google_Service_Calendar::CALENDAR);
        // Set the Auth Config with the credentials
        $this->client->setAuthConfig($credentialsPath);
        // Set the type of Access
        $this->client->setAccessType('offline');
        // Set the prompt
        $this->client->setPrompt('select_account consent');
        // Save the google token of user
        $google_token = $google_token;
        // If the user that is authenticated not have a google token
        if ($google_token) {
            // Decode json of Google Token saved in the database
            $accessToken = json_decode($google_token, true);
            // Set the access token
            $this->client->setAccessToken($accessToken);
        }
        // If there isn't previous token or it's expired
        if ($this->client->isAccessTokenExpired()) {
            // Set the session variable to show if required a new token in true
            session()->put('requireGoogleToken', true);
            // Refresh the token if possible, else fetch a new one.
            if ($this->client->getRefreshToken()) {
                // Save the refresh token
                $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
                // Set the session varible to request a new token in false
                session()->put('requireGoogleToken', false);
            } else {
                // Set the session varible to request a new token in false
                session()->put('requireGoogleToken', true);
            }
        }
        return $this->client;
    }

    /**
     * Split Categories
     * @var categories is the array with all categories
     */
    private function splitCategories($categories)
    {
        $aCategories = array();
        $aSubCategories = array();
        $aParents = array();
        foreach ($categories as $key => $category) {
            if ($category['parent'] === 0) {
                $aCategories[] = $category;
                $aParents[$key] = $category['id'];
            }
        }
        foreach ($categories as $category) {
            if ($category['parent'] !== 0) {
                $indexParent = array_search($category['parent'], $aParents);
                $category['slug_parent'] = $categories[$indexParent]['slug'];
                $aSubCategories[] = $category;
            }
        }
        return [
            'categories' => $aCategories,
            'subCategories' => $aSubCategories,
        ];
    }

    /**
     * Connect with Google Calendar
     */
    public function connect()
    {
        // Save the client
        $client = $this->getClient(Auth::user()->google_token);
        // Request authorization from the user
        $authUrl = $this->client->createAuthUrl();
        /*dd($authUrl, session()->get('requireGoogleToken'));*/
        return redirect()->to($authUrl);
    }

    /**
     * Dashboard
     */
    public function index(Request $request)
    {
        // Save the client
        $client = $this->getClient(Auth::user()->google_token);
        // Get categories
        $categories = $this->getCategories();
        // Get reminder of type task
        $remindersTask = $this->getRemindersTask();
        $remindersTask = array();
        // dd($remindersTask);
        $splitCategories = $this->splitCategories($categories);
        // Saveb the request
        $token_google = $request->all();
        // If the code is setting
        if (isset($token_google['code']) && $token_google['code'] !== '') {
            // Get the access token
            $token_google = $client->fetchAccessTokenWithAuthCode($token_google['code']);
            // Set the session varible to request a new token in false
            session()->put('requireGoogleToken', false);
            // Encode in json the token ok google
            $token_google = json_encode($token_google);
            // Get the user id
            $userId = Auth::id();
            // Save the token
            $user = DB::table('users')->where('id', $userId)->update(['google_token' => $token_google]);
            // Redirect to route dashboard
            return redirect()->route('dashboard');
        }
        if ($client->isAccessTokenExpired()) {
            User::where('id', '=', Auth::user()->id)->update(['google_token' => NULL]);
        }
        // Get list of tickets of a user
        $tickets = $this->listTickets();
        if (Auth::user()->type == 2) {
            $tickets = $this->listTicketsToManager(Auth::user()->id);
        }
        $auxTickets = array();
        foreach ($tickets as $ticket) {
            foreach ($ticket as $value) {
                $auxTickets[] = $this->objectTicket->find($value->id);
            }
        }
        $auxTickets = array_reverse($auxTickets);
        // Return view Dashboard
        return view('estates.dashboard', ['categories' => $splitCategories['categories'], 'subCategories' => $splitCategories['subCategories'], 'remindersTask' => $remindersTask, 'auxTickets' => $auxTickets]);
    }

    /**
     * Listing
     * @var category by default is all
     * @var subCategory by default is all
     */
    public function listing(Request $request, $category = "all", $subCategory = 'all')
    {
        // Get data of a estate
        $estates = $this->getEstates();
        // Save the client
        $client = $this->getClient(Auth::user()->google_token);
        // Get categories
        $categories = $this->getCategories();
        $splitCategories = $this->splitCategories($categories);
        $this->countEstatesCategory(); // Save the total estates that they have a category
        $this->countCategories(); // Save if a category is parent
        // If session is of the a manager
        if (Auth::user()->type == 2) {
            // Get estates to show to manager
            $estates = $this->getEstatesToManager(Auth::user()->id);
        }
        // If session is of the a manager
        if (Auth::user()->type == 3) {
            // Get estates to show to manager
            $estates = $this->getEstatesToAgent(Auth::user()->id);
        }
        // Return view with the current category
        return view('estates.listing', ['category' => $category, 'subCategory' => $subCategory, 'estates' => $estates, 'categories' => $splitCategories['categories'], 'subCategories' => $splitCategories['subCategories']]);
    }

    /**
     * Search
     */
    public function search(Request $request)
    {
        // Save the client
        $client = $this->getClient(Auth::user()->google_token);
        //Get data of a estate
        $estates = $this->getEstates();
        // If session is of the a manager
        if (Auth::user()->type == 2) {
            // Get estates to show to manager
            $estates = $this->getEstatesToManager(Auth::user()->id);
        }
        // Get categories
        $categories = $this->getCategories();
        $splitCategories = $this->splitCategories($categories);
        // Return view listing with category and sub category all by default
        return view('estates.listing', ['category' => 'general', 'subCategory' => 'all', 'estates' => $estates, 'categories' => $splitCategories['categories'], 'subCategories' => $splitCategories['subCategories']]);
    }

    /**
     * Edit
     */
    public function edit()
    {
        $client = $this->getClient(Auth::user()->google_token);
    }

    /**
     * View
     * @var id by default is 0
     */
    public function view($id = 0)
    {
        // Get type of menu
        $user = User::where('id', '=', Auth::user()->id)->get();
        $typemenu = $user[0]->menu;
        // Save the client
        $client = $this->getClient(Auth::user()->google_token);
        // Create the service instance of Google Calendar
        $service = new Google_Service_Calendar($client);
        // If the ID is 0
        $id = (int)$id;
        if ($id === 0) {
            // Redirect previous url
            return back();
        }
        // Get comments
        $comments = $this->getComments();
        // Get resolutions
        $resolutions = $this->getResolutions();
        // Get agents
        $agents = $this->getAgents();
        // Get notaries
        $notaries = $this->getNotaires();
        //Get data of a estate
        $estate = $this->getEstate($id);
        // If session is of the a manager
        if (Auth::user()->type == 2) {
            // Get estates to show to manager
            $estates = $this->getEstatesToManager(Auth::user()->id);
        }
        // If session is of the a manager
        if (Auth::user()->type == 3) {
            // Get estates to show to manager
            $estates = $this->getEstatesToAgent(Auth::user()->id);
        }
        // If session is of the a manager
        if (Auth::user()->type == 2) {
            // Get estates to show to manager
            $estates = $this->getEstatesToManager(Auth::user()->id);
        }
        // If session is of the a manager
        if (Auth::user()->type == 3) {
            // Get estates to show to manager
            $estates = $this->getEstatesToAgent(Auth::user()->id);
        }
        //Get data of a seller
        $seller = $this->getSeller($estate['seller']);
        //Get estate details
        $estateDetails = $this->getEstateDetails($id);
        // Get details of the json
        $details = json_decode($estateDetails['encode'], true);
        // dd($estateDetails['details']);
        // Get logs
        $logs = $this->getLogs($id);
        // Get status
        $status = $this->getStatus($id);
        //Get all the categories
        $categories = $this->getCategories();
        // Get all RealEstates
        $realestates = $this->getRealestate();
        // Get advertisements of the estate
        $advertisements = $this->getEstateAdvertisement($id);
        // Get offer
        $offer = $this->getOffre($id);
        // Get medias of the estate
        $medias = $this->getMedias($id);
        // Get remarks of current estate
        $remarks = $this->getEstateRemark($id);
        // Get settings
        $settings = $this->getSettings();
        // Get reminders of this estate
        $reminders = $this->getReminders($id);
        // Get templates of process of the reminders
        $templatesReminders = $this->getTemplatesReminders();
        // Get templates to create task only
        $templatesTask = Template::where('type', '=', 'task')->get();
        // Get templates
        $templates = $this->getTemplates();
        foreach ($settings as $setting) {
            if ($setting['name'] == 'Rappels: Demande N° Tel') {
                $telSetting = $setting['value']; //Save data of setting remind phone
            }
            if ($setting['name'] == 'Rappels: Prise de RDV') {
                $RDVSetting = $setting['value']; //Save data of setting remind RDV
            }
            if ($setting['name'] == 'Rappels: Réponse à l\'offre') {
                //Save data of setting remind response
                $responseSetting = $setting['value'];
            }
        }
        // Get estate events of the estate
        $events = EstateEvent::where('estate_id', '=', $id)->get();
        $eventsArray = array();
        $all = array();
        // dd($client->isAccessTokenExpired());
        if (!$client->isAccessTokenExpired()) {
            foreach ($events as $_event) {
                $event = $service->events->get($this->calendarId, $_event->event_id);
                $eventsArray['id'] = $_event->id;
                $eventsArray['id_google'] = $event->id;
                $eventsArray['start'] = $event->start->dateTime;
                $eventsArray['end'] = $event->end->dateTime;
                $all[] = $eventsArray;
            }
        }
        // Get the list of tickets of a estate
        $auxTickets = array(); //$this->listAllTicketsEstate($id);

        $this->countEstatesCategory(); // Save the total estates that they have a category
        $this->countCategories(); // Save if a category is parent
        $emails = '';
        // Save the client
        if (!$client->isAccessTokenExpired()) {
            $client = $this->getClient(Auth::user()->google_token);
            // Create the service instance of Google Calendar
            $service = new Google_Service_Calendar($client);
            // Return view with the data of calendar
            $calendarList = $service->calendarList->listCalendarList();


            while (true) {
                // foreach ($calendarList->getItems() as $calendarListEntry) {
                // 	echo $calendarListEntry->getSummary();
                // }
                $pageToken = $calendarList->getNextPageToken();
                if ($pageToken) {
                    $optParams = array('pageToken' => $pageToken);
                    $calendarList = $service->calendarList->listCalendarList($optParams);
                } else {
                    break;
                }
            }
            foreach ($calendarList as $value) {
                $account = explode("@", $value->id);
                if ($account[1] == 'maisonsvides.be') {
                    $id_ = 'src=' . $value->id . '&amp;';
                    $emails = $emails . $id_;
                }
            }
        }

        // Get to show events
        $eve_ = $this->showEvents($id);
        // Get events confirmed
        $eventConfirmed = EstateEvent::where('confirmed', '=', 1)->get();
        // Get total tickets no answered
        $countTicketsNoAnswer = EstateTicket::where('estate_id', '=', $id)
            ->where('no_answer', '=', 1)->count();
        $ticketsNoAnswer = EstateTicket::where('estate_id', '=', $id)
            ->where('no_answer', '=', 1)->get();
        $auxticketsNoAnswer = array();
        foreach ($ticketsNoAnswer as $ticket) {
            $auxticketsNoAnswer[] = $ticket->ticket_id;
        }
        // Return view the data of the estate
        return view('estates.view', ['id' => $id, 'comments' => $comments, 'estateDetails' => $estateDetails, 'details' => $details, 'resolutions' => $resolutions, 'estate' => $estate, 'seller' => $seller, 'logs' => $logs, 'status' => $status, 'categories' => $categories, 'realestates' => $realestates, 'advertisements' => $advertisements, 'medias' => $medias, 'offer' => $offer, 'remarks' => $remarks, 'templates' => $templates, 'all' => $all, 'agents' => $agents, 'notaries' => $notaries, 'templatesReminders' => $templatesReminders, 'reminders' => $reminders, 'auxTickets' => $auxTickets, 'emails' => $emails, 'eve_' => $eve_, 'eventConfirmed' => $eventConfirmed, 'typemenu' => $typemenu, 'templatesTask' => $templatesTask, 'countTicketsNoAnswer' => $countTicketsNoAnswer, 'auxticketsNoAnswer' => $auxticketsNoAnswer]);
    }

    /**
     * Delete
     */
    public function delete()
    {
        // Save the client
        $client = $this->getClient(Auth::user()->google_token);
    }

    /**
     * Calendar
     */
    public function calendar()
    {
        $estate_ids = Estate::pluck('id');
        $seller_ids = Seller::pluck('id');

        // Save the client
        $client = $this->getClient(Auth::user()->google_token);
        // Create the service instance of Google Calendar
        $service = new Google_Service_Calendar($client);

        // Return view with the data of calendar
        $calendarList = $service->calendarList->listCalendarList();


        while (true) {
            // foreach ($calendarList->getItems() as $calendarListEntry) {
            // 	echo $calendarListEntry->getSummary();
            // }
            $pageToken = $calendarList->getNextPageToken();
            if ($pageToken) {
                $optParams = array('pageToken' => $pageToken);
                $calendarList = $service->calendarList->listCalendarList($optParams);
            } else {
                break;
            }
        }
        $ids = '';
        $response = Event::all();
        foreach ($response as $event) {
            $events[] = [
                'name' => $event->name,
                'telephone' => $event->name,
                'email' => $event->name,
                'type_visit' => $event->name,
                'start' => $event->name,
                'end' => $event->name,
                'localization' => $event->name
            ];
        }
        foreach ($calendarList as $value) {
            $account = explode("@", $value->id);
            if ($account[1] == 'gmail.com') {
                $id = 'src=' . $value->id . '&amp;';
                $ids = $ids . $id;
            }
        }
        //dd($response);

        return view('estates.calendar', ['ids' => $ids, 'estate_ids' => $estate_ids, 'seller_ids' => $seller_ids]);
    }

    /**
     * Get events to show
     */
    public function showEvents($estate_id)
    {
        $allEvents = array();
        $allEvents['total'] = 0;
        // Save the client
        $client = $this->getClient(Auth::user()->google_token);
        if (!$client->isAccessTokenExpired()) {
            // Init response
            $response = array(
                'status' => false,
                'message' => 'Une erreur s\'est produite lors de la synchronisation du calendrier. Veuillez réessayer plus tard.',
            );
            // Create the service instance of Google Calendar
            $service = new Google_Service_Calendar($client);
            // Get data of events of the DB
            $events = Event::all();
            foreach ($events as $event) {
                $allEvents['events'][] = $service->events->get('primary', $event->event_id);
            }
            $allEvents['total'] = count($events);
        }
        if ($client->isAccessTokenExpired()) {
            User::where('id', '=', Auth::user()->id)->update(['google_token' => NULL]);
        }
        return $allEvents;
    }

    /**
     * Get events
     */
    public function getEvents()
    {
        // Save the client
        $client = $this->getClient(Auth::user()->google_token);

        // Init response
        $response = array(
            'status' => false,
            'message' => 'Une erreur s\'est produite lors de la synchronisation du calendrier. Veuillez réessayer plus tard.',
        );
        // Create the service instance of Google Calendar
        $service = new Google_Service_Calendar($client);
        $calendarId = 'primary';
        $optParams = [
            'maxResults' => 50,
            'orderBy' => 'startTime',
            'singleEvents' => true,
            'timeZone' => 'Europe/Brussels',
        ];
        $events = $service->events->listEvents($calendarId, $optParams);


        // ///////MI CODE
        // Return view with the data of calendar
        $calendarList = $service->calendarList->listCalendarList();


        // dd($calendarList);die;
        $ids = array();
        $aux = array();
        foreach ($calendarList as $value) {
            $account = explode("@", $value->id);
            if ($account[1] == 'maisonsvides.be') {
                $ids['id'] = $value->id;
                $ids['backgroundColor'] = $value->backgroundColor;
                $ids['name'] = $value->summary;
                $aux[] = $ids;
                //dd($aux);die;
            }
        }
        $response = Event::all();
        foreach ($response as $event) {
            $eventC[] = [
                'name' => $event->name,
                'telephone' => $event->telephone,
                'email' => $event->email,
                'type_visit' => $event->type_visit,
                'start' => $event->start,
                'end' => $event->end,
                'localization' => $event->localization
            ];
        }
        // ///////MI CODE
        $allEvents = array();
        foreach ($aux as $id) {
            // Get result of events
            $results = $service->events->listEvents($id['id'], $optParams);

            // Save the events
            $arrayEvents = array();
            $events = $results->getItems();
            function str_replace_last($search, $replace, $subject)
            {
                $pos = strrpos($subject, $search);
                if ($pos !== false) {
                    $subject = substr_replace($subject, $replace, $pos, strlen($search));
                }
                return $subject;
            }
            foreach ($events as $event) {
                //In cse of any error change $className to other value(ex. ' admin')
                $className = '.' . str_replace('.', '@', str_replace_last('.', '-', ltrim($id['id'], '.')));
                $arrayEvents[] = array(
                    'title' => $event->summary,
                    'id' => $event->id, // Id of the event
                    'start' => $event->start->dateTime, // Datetime start
                    'end' => $event->end->dateTime, // Datetime end
                    'backgroundColor' => $id['backgroundColor'], // Color of circle event,
                    'borderColor' => $id['backgroundColor'], // Border color of de event,
                    'textColor' => '#000000', // Border color of de event,
                    'className' => array(
                        $className
                    ),
                    'extendedProps' => array(
                        'contact' => $id['id'], // Name of contact,
                        'phone' => '0321654987', // Phone of contact,
                        'email' => $id['id'], // Email of contact
                        'type' => 'Propriétaire', // Type of contact
                        'address' => 'Rue de la Résistance 85 1140 Evere', // Estate address
                        'title' => $event->summary, // Title of event or estate
                        'coordinates' => array( // Coordinates of the estate
                            'lat' => 50.8705611, // Latitude
                            'long' => 4.3975782, // Longitude
                        ),
                    ),
                    'description' => '<p>' . $event->description . '</p>', // Estate description
                );
            }
            $allEvents[] = $arrayEvents;
        }
        $auxAllEvents = array();
        foreach ($allEvents as $eventc) {
            if (!empty($eventc)) {
                foreach ($eventc as $event) {
                    $auxAllEvents[] = $event;
                    //dd($auxAllEvents);die;
                }
            }
        }
        if (is_array($auxAllEvents) && !empty($auxAllEvents)) {
            $response = array(
                'status' => true,
                'events' => $auxAllEvents,
            );
        } elseif (is_array($auxAllEvents) && empty($auxAllEvents)) {
            $response = array(
                'status' => true,
                'message' => 'Vous n\'avez aucune visite programmée',
                'events' => $auxAllEvents,
            );
        }

        /*dd($events);*/
        return response($response)->header('Content-Type', 'application/json');
    }

    /**
     * Create event in google
     */
    public function createEvent(Request $request)
    {
        //Get data of the request
        $data = $request->all();
        // Save the client
        $client = $this->getClient(Auth::user()->google_token);
        // Init response
        $response = array(
            'status' => false,
            'message' => 'Une erreur s\'est produite lors de la synchronisation du calendrier. Veuillez réessayer plus tard.',
        );
        try {
            // Create the service instance of Google Calendar
            $service = new Google_Service_Calendar($client);
            $datetimestart = $data['date_event_click_start'] . 'T' . $data['time_event_click_start'];
            $datetimestart = date('Y-m-d\TH:i:sO', strtotime($datetimestart));
            $datetimeend = $data['date_event_click_end'] . 'T' . $data['time_event_click_end'];
            $datetimeend = date('Y-m-d\TH:i:sO', strtotime($datetimeend));
            $event = new Google_Service_Calendar_Event(array(
                'summary' => 'Visite du dossier : ' . $data['number_bien'],
                'status' => 'tentative',
                'description' => 'Adresse: ' . $data['address_bien'] . ', Nom de la peersonne de contact: ' . $data['name_seller'] . ', Numéro de téléphone: ' . $data['phone_seller'] . ', E-mail: ' . $data['seller_email'],
                'start' => array(
                    'dateTime' => $datetimestart,
                    'timeZone' => 'Europe/Brussels',
                ),
                'end' => array(
                    'dateTime' => $datetimeend,
                    'timeZone' => 'Europe/Brussels',
                ),
            ));

            $calendarId = $data['chosen_calendar'];
            $event = $service->events->insert($calendarId, $event, ['sendUpdates' => 'all', 'sendNotifications' => true]);

            // Save event in DB
            if (isset($event->id)) {
                $estateEvent = EstateEvent::create([
                    'estate_id' => $data['estate_id'],
                    'event_id' => $event->id,
                    'user_id' => Auth::user()->id,
                    'seller_id' => $data['seller_id']
                ]);
                $response = array(
                    'status' => true,
                    'message' => 'L\'événement a été enregistrée.'
                );
            }
        } catch (Google_Service_Exception $e) {
            $response = array(
                'status' => false,
                'message' => 'L’événement n’a pas pu être créé'
            );
        }
        return response($response)->header('Content-Type', 'application/json');
    }

    /**
     * Create event
     */
    public function setRdv(Request $request)
    {
        // Save the client
        $client = $this->getClient(Auth::user()->google_token);
        // Get all data of request
        $data = $request->all();
        $visit = $request->input('date_visit');
        $start = $request->input('date_start');
        $start = date('Y-m-d\TH:i:sO', strtotime($start));
        $end = $request->input('date_end');
        $end = date('Y-m-d\TH:i:sO', strtotime($end));
        // Init response
        $response = array('status' => false, 'message' => 'La visite n\'a pas pu être enregistrée, veuillez réessayer plus tard');
        $apiKey = env('GOOGLE_API_KEY');
        $client->setDeveloperKey($apiKey);
        $service = new Google_Service_Calendar($client);
        // Optional params to create a request to get envents
        $optParams = array(
            'maxResults' => 250, // Number of max results to obtain default is 250,
            'orderBy' => 'startTime', // Set the order,
            'singleEvents' => true, // To allow the order startTime
            'timeMin' => $start, // Set the current time to obtain results
            'timeMax' => $end, // Set the current time to obtain results
        );
        // Get result of events
        $results = $service->events->listEvents($this->calendarId, $optParams);
        // Save the events
        $events = $results->getItems();
        // dd($events);die;
        if (is_array($events) && count($events) >= 1) {
            $response = array(
                'status' => false,
                'message' => 'Vous avez déjà une visite programmée avec ces données.',
                'data' => $data,
                'start' => $start,
                'end' => $end
            );
        } else {
            $email = auth()->user()->email;
            $guestsCanInviteOthers = true;
            $account = explode("@", $email);
            if ($account[1] != 'gmail.com') {
                $guestsCanInviteOthers = false;
            }
            /* // Crear el objeto de fecha y hora de inicio del evento
            $startDateTime = new DateTime($clickedDate);
            $startDateTime->setTime($start, 0, 0);

            // Crear el objeto de fecha y hora de fin del evento
            $endDateTime = new DateTime($clickedDate);
            $endDateTime->setTime($end, 0, 0); */
            // Create a event
            $event = new Google_Service_Calendar_Event(array(
                'summary' => 'Visite du dossier ', //. $request->input('estate_id'),
                'description' => 'Visite de type: ' . $request->input('type-visite')
                    . ' ' . 'à l\'adresse suivante: ' . $request->input('localisation')
                    . '. ' . 'Et les données du propriétaire sont ' . $request->input('contact')
                    . ', avec téléphone: ' . $request->input('tel')
                    . ' et e-mail: ' . $request->input('mail'),
                'status' => 'tentative',
                'start' => array(
                    'dateTime' => $start,
                    'timeZone' => 'Europe/Brussels'
                ),
                'end' => array(
                    'dateTime' => $end,
                    'timeZone' => 'Europe/Brussels'
                ),
                /* 'attendees' => array(
                    array(
                        'name' => $request->input('contact'),
                        'email' => $request->input('mail')
                    )
                ) */
            ));

            $event = $service->events->insert($this->calendarId, $event, ['sendUpdates' => 'all', 'sendNotifications' => true]);
            // dd(isset($event->id));
            /*$event = $service->events->insert($this->calendarId, $event);*/
            if (isset($event->id)) {
                /* $estateEvent = EstateEvent::create([
                    'estate_id' => $request->input('estate_id'),
                    'event_id' => $event->id,
                    'user_id' => Auth::user()->id,
                    'seller_id' => $request->input('seller_id') // o $data['seller_id']
                ]); */
                $event_created = Event::create([
                    'estate_id' => $request->input('estate_id'),
                    //'event_id' => $event->id,
                    'name' => $request->input('contact'),
                    'telephone' => $request->input('tel'),
                    'email' => $request->input('mail'),
                    'type_visit' => $request->input('type-visite'),
                    'start' => $start,
                    'end' => $end,
                    'localization' => $request->input('localisation'),
                    'description' => $request->input('descriptif'),
                    'user_id' => Auth::user()->id,
                    //'seller_id' => $request->input('seller_id') // o $data['seller_id']
                ]);

                $response = array(
                    'status' => true,
                    'message' => 'La visite a été enregistrée.',
                    'data' => $event_created,
                    'start' => $start,
                    'end' => $end,
                );
            }
        }
        /* $estates = Estate::all();
         //dd($estates);die;
         $category = $estates[0]->category;
         //$new_id= $estates[0]->id ;
         $updated = $this->updateData(app("App\\Models\\Estate"), $category, 1 , $request->input('estate_id'), $request->input('estate_id'), [$category]); // Update data
         $estates = $this->getEstates();
         // If session is of the a manager
         if (Auth::user()->type == 2) {
             // Get estates to show to manager
             $estates = $this->getEstatesToManager(Auth::user()->id);
         }
         foreach ($estates as $estate) {
             if (empty($estate['offre'])) {
                 $updated = $this->updateData(app("App\\Models\\Estate"), $category, 1 , $estate['offre'], $estate['offre'], [$category]); // Update data
             }
         }*/
        // return response($response)->header('Content-Type', 'application/json');
        return redirect()->route('calendar')->with('response', $response);
    }

    /**
     * Thanks confirmation
     */
    public function thanks($event_id, $estateid)
    {
        // Get id of the page
        $infos = $this->getEstateEvent($event_id);
        // Variables to service of google calendar
        $user = $this->getUser($infos['user_id']);
        $client = $this->getClient($user['google_token']); // Save the client
        $service = new Google_Service_Calendar($client);

        // Confirm event in the DB
        $upEvent = EstateEvent::where('event_id', '=', $event_id)
            ->update(['confirmed' => 1]);

        // Create format of date to save in the DB
        $eventConfirmed = $service->events->get('primary', $event_id); // Get data of the event
        $dateConf = 'Visite le ' . date('Y-m-d', strtotime($eventConfirmed->start->dateTime)) . ' de ' . date('H:m', strtotime($eventConfirmed->start->dateTime)) . ' à ' . date('H:m', strtotime($eventConfirmed->end->dateTime));
        // GEt id of the estate
        $a = Estate::whereRaw('md5(id) = "' . $estateid . '"')->first();
        $estateid = $a->id;
        // Save visit in the estate
        $up = Estate::where('id', '=', $estateid)->update(['visit_date_at' => $dateConf, 'rdv' => 1]);

        // /**
        // * Removing unconfirmed events
        // **/
        // Save unconfirmed events of the DB
        $eventsNotConfirmed = EstateEvent::where('estate_id', '=', $estateid)->where('confirmed', '=', 0)->get();
        foreach ($eventsNotConfirmed as $event) {
            // Delete events not confirmed of the Google Calendar
            $service->events->delete('primary', $event->event_id);
            // Delete events not confirmed of the DB
            EstateEvent::where('event_id', '=', $event->event_id)->delete();
        }

        /**
         * Removing event at the same time of confirmation
         **/
        $date = $eventConfirmed->start->dateTime;
        $allevents = EstateEvent::where('confirmed', '=', 0)->get();
        foreach ($allevents as $e) {
            $event_ = $service->events->get('primary', $e->event_id); // Get data of the event
            if ($date == $event_->start->dateTime) {
                // Delete events not confirmed of the Google Calendar
                $service->events->delete('primary', $e->event_id);
                // Delete events not confirmed of the DB
                EstateEvent::where('event_id', '=', $e->event_id)->delete();
            }
        }

        return view('thanks', ['eventConfirmed' => $eventConfirmed]);
    }

    /**
     * Confirm the visite
     */
    public function confirm($estateid)
    {
        $estates = Estate::get();
        foreach ($estates as $estate) {
            if (md5($estate->id) == $estateid) {
                $estateid = $estate->id;
                // Get id of the page
                $infos = $this->getEstateEvents($estateid);
                $events = array();
                if (!empty($infos)) {
                    // Save the client
                    $user = $this->getUser($infos[0]['user_id']);
                    $client = $this->getClient($user['google_token']);
                    foreach ($infos as $info) {

                        $service = new Google_Service_Calendar($client);
                        $events[] = $service->events->get('primary', $info['event_id']);
                    }
                }
            }
        }
        return view('confirm', ['events' => $events, 'estateid' => $estateid]);
    }

    /**
     * Get estate event
     */
    private function getEstateEvents($estate_id)
    {
        $estateEvent = EstateEvent::where('estate_id', '=', $estate_id)->get();
        $estateEventArray = array();
        foreach ($estateEvent as $event) {
            $estateEventArray[] = array(
                'id' => $event->id,
                'estate_id' => $event->estate_id,
                'event_id' => $event->event_id,
                'user_id' => $event->user_id,
                'seller_id' => $event->seller_id
            );
        }
        return $estateEventArray;
    }

    /**
     * Get estate event
     */
    private function getEstateEvent($event_id)
    {
        $estateEvent = EstateEvent::where('event_id', '=', $event_id)->get();
        $estateEventArray = array();
        foreach ($estateEvent as $event) {
            $estateEventArray = array(
                'id' => $event->id,
                'estate_id' => $event->estate_id,
                'event_id' => $event->event_id,
                'user_id' => $event->user_id,
                'seller_id' => $event->seller_id
            );
        }
        return $estateEventArray;
    }

    /**
     * Visits
     */
    public function visits()
    {
        // Get data of all estates
        $estates = $this->getEstatesNotVisit();
        return view('estates.visits', ['estates' => $estates]);
    }

    /**
     * Visit
     * @var id by default is 0
     */
    public function visit($id = 0)
    {
        //Get data of a estate
        $estate = $this->getEstate($id);
        //dd($estate);die;
        // If session is of the a manager
        if (Auth::user()->type == 2 || Auth::user()->type == 1) {
            // Get estates to show to manager
            $estates = $this->getEstatesToManager(Auth::user()->id);
        }
        // If session is of the a manager
        if (Auth::user()->type == 3) {
            // Get estates to show to manager
            $estates = $this->getEstatesToAgent(Auth::user()->id);
        }
        //Get data of a seller
        $seller = $this->getSeller($estate['seller']);
        //Get estate details
        $estateDetails = $this->getEstateDetails($id);
        //dd($estateDetails);die;
        // Get details of the json
        // $details = json_decode($estateDetails['adapte'], true);
        // dd($details);
        // Get remarks of current estate
        $remarks = $this->getEstateRemark($id);
        // Get offer
        $offer = $this->getOffre($id);
        // Save the client
        $client = $this->getClient(Auth::user()->google_token);
        $id = (int)$id;
        // If the ID is 0
        if ($id === 0) {
            // Return view listing estates module visit
            return view('estates.visit', ['id' => $id]);
        }
        // Return view edit module visit
        return view('estates.visit', ['id' => $id, 'estate' => $estate, 'estates' => $estates, 'details' => $estateDetails, 'seller' => $seller, 'offer' => $offer, 'remarks' => $remarks]);
    }

    /**
     * Get comments
     */
    public function getComments()
    {
        $comments = EstateComment::select(
            "estate_comments.id",
            "estate_comments.estate_id",
            "estate_comments.user_id",
            "estate_comments.comment",
            "estate_comments.created_at",
            "users.name as username"
        )->join("users", "users.id", "=", "estate_comments.user_id")
            ->get();
        $commentsArray = array();
        foreach ($comments as $comment) {
            $commentsArray[] = array(
                'id' => $comment->id,
                'estate_id' => $comment->estate_id,
                'username' => $comment->username,
                'comment' => $comment->comment,
                'created_at' => $comment->created_at
            );
        }
        return $commentsArray;
    }

    /**
     * Create new comment intern
     */
    public function newComment(Request $request)
    {
        //Get data of the request
        $data = $request->all();
        //Init the response
        $response = array(
            'status' => false,
            'message' => 'Le commentaire n\'a pas pu être créé. Réessayez plus tard.'
        );
        try {
            $category = EstateComment::create([
                'estate_id' => $data['estate_id'],
                'user_id' => Auth::user()->id,
                'comment' => $data['estate_comment_internal']
            ]);
            $response = array(
                'status' => true,
                'message' => 'Le commentaire a été créée',
                'data' => $data,
            );
        } catch (\Exception $e) {
            $message = "";
            if ($e->getCode() == 23000) {
                $message = 'Le commentaire existe déjà';
            }
            $response = array(
                'status' => false,
                'message' => $message,
                'data' => $data
            );
        }
        //Return response
        return back()->with('message', 'Le commentaire a été créé avec succès');
    }

    /**
     * Create new comment intern to RDV
     */
    public function newCommentRDV(Request $request)
    {
        //Get data of the request
        $data = $request->all();
        //Init the response
        $response = array(
            'status' => false,
            'message' => 'Le commentaire n\'a pas pu être créé. Réessayez plus tard.'
        );
        try {
            $category = EstateComment::create([
                'estate_id' => $data['estate_id'],
                'user_id' => Auth::user()->id,
                'comment' => $data['estate_comment_internal']
            ]);
            //Create new log about update
            EstateLog::create([
                'estate_id' => $data['estate_id'],
                'user_id' => Auth::user()->id,
                'old_value' => '',
                'new_value' => $data['estate_comment_internal'],
                'field' => 'commentrdv'
            ]);
            $response = array(
                'status' => true,
                'message' => 'Le commentaire a été créée',
                'data' => $data,
            );
        } catch (\Exception $e) {
            $message = $e->getMessage();
            if ($e->getCode() == 23000) {
                $message = 'Le commentaire existe déjà';
            }
            $response = array(
                'status' => false,
                'message' => $message,
                'data' => $data
            );
        }
        //Return response
        return response($response)->header('Content-Type', 'application/json');
    }

    /**
     * Get resolutions
     */
    public function getResolutions()
    {
        $resolutions = EstateResolution::select(
            "estate_resolutions.id",
            "estate_resolutions.estate_id",
            "estate_resolutions.user_id",
            "estate_resolutions.comment",
            "estate_resolutions.created_at",
            "users.name as username"
        )->join("users", "users.id", "=", "estate_resolutions.user_id")
            ->get();
        $resolutionsArray = array();
        foreach ($resolutions as $resolution) {
            $resolutionsArray[] = array(
                'id' => $resolution->id,
                'estate_id' => $resolution->estate_id,
                'username' => $resolution->username,
                'comment' => $resolution->comment,
                'created_at' => $resolution->created_at
            );
        }
        return $resolutionsArray;
    }

    /**
     * Create new resolution to the problem
     */
    public function newResolution(Request $request)
    {
        //Get data of the request
        $data = $request->all();
        //Init the response
        $response = array(
            'status' => false,
            'message' => 'La résolution n\'a pas pu être créé. Réessayez plus tard.'
        );
        try {
            $category = EstateResolution::create([
                'estate_id' => $data['estate_id'],
                'user_id' => Auth::user()->id,
                'comment' => $data['estate_new_problem']
            ]);
            $response = array(
                'status' => true,
                'message' => 'La résolution a été créée',
                'data' => $data,
            );
        } catch (\Exception $e) {
            $message = "";
            if ($e->getCode() == 23000) {
                $message = 'La résolution existe déjà';
            }
            $response = array(
                'status' => false,
                'message' => $message,
                'data' => $data
            );
        }
        //Return response
        return response($response)->header('Content-Type', 'application/json');
    }

    /**
     * Get estates not visit
     */
    private function getEstatesNotVisit()
    {
        $estate = Estate::where('module_visit', '=', 0)->get();
        $estatesArray = array();
        foreach ($estate as $_estate) {
            //Get data of a seller
            $seller = $this->getSeller($_estate->seller);
            $estatesArray[] = array(
                'id' => $_estate->id,
                'reference' => $_estate->reference,
                'type_estate' => $_estate->type_estate,
                'name' => $_estate->name,
                'category' => $_estate->category,
                'visit_date_at' => $_estate->visit_date_at,
                'main_photo' => $_estate->main_photo,
                'street' => $_estate->street,
                'number' => $_estate->number,
                'box' => $_estate->box,
                'code_postal' => $_estate->code_postal,
                'city' => $_estate->city,
                'seller' => $seller,
                'when_want_sell' => $_estate->when_want_sell,
                'want_tenant_after_sell' => $_estate->want_tenant_after_sell,
                'agent' => $_estate->agent,
                'estimate' => $_estate->estimate,
                'market' => $_estate->market,
                'attempt_via_agency' => $_estate->attempt_via_agency,
                'agency_name' => $_estate->agency_name,
                'information_additional' => $_estate->information_additional,
                'module_visit' => $_estate->module_visit,
                'created_at' => $_estate->created_at
            );
        }
        return $estatesArray;
    }

    /**
     * Get estates
     */
    private function getEstates()
    {
        $estate = Estate::get();
        $estatesArray = array();
        foreach ($estate as $_estate) {
            //Get data of a seller
            $seller = $this->getSeller($_estate->seller);
            $estatesArray[] = array(
                'id' => $_estate->id,
                'reference' => $_estate->reference,
                'name' => $_estate->name,
                'type_estate' => $_estate->type_estate,
                'category' => $_estate->category,
                'visit_date_at' => $_estate->visit_date_at,
                'main_photo' => $_estate->main_photo,
                'street' => $_estate->street,
                'number' => $_estate->number,
                'box' => $_estate->box,
                'code_postal' => $_estate->code_postal,
                'city' => $_estate->city,
                'seller' => $seller,
                'when_want_sell' => $_estate->when_want_sell,
                'want_tenant_after_sell' => $_estate->want_tenant_after_sell,
                'agent' => $_estate->agent,
                'estimate' => $_estate->estimate,
                'market' => $_estate->market,
                'offre' => $this->getOffre($_estate->id),
                'attempt_via_agency' => $_estate->attempt_via_agency,
                'agency_name' => $_estate->agency_name,
                'information_additional' => $_estate->information_additional,
                'module_visit' => $_estate->module_visit,
                'created_at' => $_estate->created_at
            );
        }
        return $estatesArray;
    }

    /**
     * Get estates relation with agent and manager
     */
    private function getAgentManager($managerid)
    {
        $agents = Agent::where('manager_id', '=', $managerid)->get();
        $agentsArray = array();
        foreach ($agents as $agent) {
            $agentsArray[] = array(
                'agent_id' => $agent->agent_id,
                'manager_id' => $agent->manager_id
            );
        }
        return $agentsArray;
    }

    /**
     * Get estates to manager
     */
    private function getEstatesToManager($managerid)
    {
        // Get agents of manager
        $am = $this->getAgentManager($managerid);
        foreach ($am as $value) {
            $aux[] = $value['agent_id'];
        }
        // Get all estates of their agents
        $estate = Estate::whereIn('agent', $aux)->get();
        $superAux = array();
        foreach ($estate as $_estate) {
            //Get data of a seller
            $seller = $this->getSeller($_estate->seller);
            $superAux[] = array(
                'id' => $_estate->id,
                'reference' => $_estate->reference,
                'name' => $_estate->name,
                'type_estate' => $_estate->type_estate,
                'category' => $_estate->category,
                'visit_date_at' => $_estate->visit_date_at,
                'main_photo' => $_estate->main_photo,
                'street' => $_estate->street,
                'number' => $_estate->number,
                'box' => $_estate->box,
                'code_postal' => $_estate->code_postal,
                'city' => $_estate->city,
                'seller' => $seller,
                'when_want_sell' => $_estate->when_want_sell,
                'want_tenant_after_sell' => $_estate->want_tenant_after_sell,
                'agent' => $_estate->agent,
                'estimate' => $_estate->estimate,
                'market' => $_estate->market,
                'offre' => $this->getOffre($_estate->id),
                'attempt_via_agency' => $_estate->attempt_via_agency,
                'agency_name' => $_estate->agency_name,
                'information_additional' => $_estate->information_additional,
                'module_visit' => $_estate->module_visit,
                'created_at' => $_estate->created_at
            );
        }
        // Get all estates that don't have an agent assigned
        $estateWithoutAgent = Estate::where('agent', '=', 1)->get();
        foreach ($estateWithoutAgent as $_estate) {
            //Get data of a seller
            $seller = $this->getSeller($_estate->seller);
            $superAux[] = array(
                'id' => $_estate->id,
                'reference' => $_estate->reference,
                'name' => $_estate->name,
                'type_estate' => $_estate->type_estate,
                'category' => $_estate->category,
                'visit_date_at' => $_estate->visit_date_at,
                'main_photo' => $_estate->main_photo,
                'street' => $_estate->street,
                'number' => $_estate->number,
                'box' => $_estate->box,
                'code_postal' => $_estate->code_postal,
                'city' => $_estate->city,
                'seller' => $seller,
                'when_want_sell' => $_estate->when_want_sell,
                'want_tenant_after_sell' => $_estate->want_tenant_after_sell,
                'agent' => $_estate->agent,
                'estimate' => $_estate->estimate,
                'market' => $_estate->market,
                'offre' => $this->getOffre($_estate->id),
                'attempt_via_agency' => $_estate->attempt_via_agency,
                'agency_name' => $_estate->agency_name,
                'information_additional' => $_estate->information_additional,
                'module_visit' => $_estate->module_visit,
                'created_at' => $_estate->created_at
            );
        }
        return $superAux;
    }

    /**
     * Get estates to agent
     */
    public function getEstatesToAgent($agentid)
    {
        $estate = Estate::where('agent', '=', $agentid)->get();
        $estatesArray = array();
        foreach ($estate as $_estate) {
            //Get data of a seller
            $seller = $this->getSeller($_estate->seller);
            $estatesArray[] = array(
                'id' => $_estate->id,
                'reference' => $_estate->reference,
                'name' => $_estate->name,
                'type_estate' => $_estate->type_estate,
                'category' => $_estate->category,
                'visit_date_at' => $_estate->visit_date_at,
                'main_photo' => $_estate->main_photo,
                'street' => $_estate->street,
                'number' => $_estate->number,
                'box' => $_estate->box,
                'code_postal' => $_estate->code_postal,
                'city' => $_estate->city,
                'seller' => $seller,
                'when_want_sell' => $_estate->when_want_sell,
                'want_tenant_after_sell' => $_estate->want_tenant_after_sell,
                'agent' => $_estate->agent,
                'estimate' => $_estate->estimate,
                'market' => $_estate->market,
                'offre' => $this->getOffre($_estate->id),
                'attempt_via_agency' => $_estate->attempt_via_agency,
                'agency_name' => $_estate->agency_name,
                'information_additional' => $_estate->information_additional,
                'module_visit' => $_estate->module_visit,
                'created_at' => $_estate->created_at
            );
        }
        // dd($estatesArray);die;
        return $estatesArray;
    }

    /**
     * Get estate
     */
    public function getEstate($estate_id)
    {
        $estate = Estate::where('id', '=', $estate_id)->get();
        $estateArray = array();
        foreach ($estate as $_estate) {
            $seller = $this->getSeller($_estate->seller);

            $estateArray = array(
                'id' => $_estate->id,
                'reference' => $_estate->reference,
                'name' => $seller['name'],
                'phone' => $seller['phone'],
                'email' => $seller['email'],
                'category' => $this->getCategory($_estate->category),
                'construction' => $_estate->construction,
                'renovation' => $_estate->renovation,
                'peb' => $_estate->peb,
                'surface' => $_estate->surface,
                'town_planning' => $_estate->town_planning,
                'rooms' => $_estate->rooms,
                'bathrooms' => $_estate->bathrooms,
                'garden' => $_estate->garden,
                'terrase' => $_estate->terrase,
                'garage' => $_estate->garage,
                'number_gas' => $_estate->number_gas,
                'number_electric' => $_estate->number_electric,
                'type_estate' => $_estate->type_estate,
                'visit_date_at' => $_estate->visit_date_at,
                'main_photo' => $_estate->main_photo,
                'street' => $_estate->street,
                'number' => $_estate->number,
                'box' => $_estate->box,
                'code_postal' => $_estate->code_postal,
                'city' => $_estate->city,
                'seller' => $_estate->seller,
                'details_seller' => $this->getSeller($_estate->seller),
                'when_want_sell' => $_estate->when_want_sell,
                'want_tenant_after_sell' => $_estate->want_tenant_after_sell,
                'want_buy_wesold' => $_estate->want_buy_wesold,
                'agent' => $_estate->agent,
                'estimate' => $_estate->estimate,
                'market' => $_estate->market,
                'type_of_sale' => $_estate->type_of_sale,
                'attempt_via_agency' => $_estate->attempt_via_agency,
                'attempt_via_client' => $_estate->attempt_via_client,
                'agency_name' => $_estate->agency_name,
                'price_published_agence' => $_estate->price_published_agence,
                'date_of_sale_agence' => $_estate->date_of_sale_agence,
                'price_published_himself' => $_estate->price_published_himself,
                'date_of_sale_himself' => $_estate->date_of_sale_himself,
                'information_additional' => $_estate->information_additional,
                'date_send_reminder' => $_estate->date_send_reminder,
                'send_reminder_half_past_eight' => $_estate->send_reminder_half_past_eight,
                'rdv' => $_estate->rdv
            );
        }
        //dd($estateArray);die;
        return $estateArray;
    }

    /**
     * Get seller
     */
    private function getSeller($seller_id)
    {
        $seller = Seller::where('id', '=', $seller_id)->get();
        $sellerArray = array();
        foreach ($seller as $_seller) {
            $sellerArray = array(
                'id' => $_seller->id,
                'name' => $_seller->name,
                'email' => $_seller->email,
                'phone' => $_seller->phone,
                'type' => $_seller->type,
                'contact_by' => $_seller->contact_by,
                'reason_sale' => $_seller->reason_sale,
                'looking_property' => $_seller->looking_property,
                'want_stay_tenant' => $_seller->want_stay_tenant,
                'when_to_buy' => $_seller->when_to_buy
            );
        }
        return $sellerArray;
    }

    /**
     * Get users
     */
    public function getUser($id)
    {
        $users = User::select(
            "users.id",
            "users.name",
            "users.email",
            "users.google_email",
            'users.google_token'
        )->where("users.id", "=", $id)
            ->get();
        $userArray = array();
        foreach ($users as $user) {
            $userArray = array(
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'google_email' => $user->google_email,
                'google_token' => $user->google_token
            );
        }
        return $userArray;
    }

    /**
     * Get agents
     */
    public function getAgents()
    {
        $users = User::where("type", "=", 3)->get();
        $userArray = array();
        foreach ($users as $user) {
            $userArray[] = array(
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            );
        }
        return $userArray;
    }

    /**
     * Get notaires
     */
    public function getNotaires()
    {
        $notaries = Notary::get();
        $notairesArray = array();
        foreach ($notaries as $notary) {
            $notairesArray[] = array(
                'id' => $notary->id,
                'title' => $notary->title,
                'name' => $notary->name,
                'lastname' => $notary->lastname,
                'address' => $notary->address,
                'phone' => $notary->phone,
                'email' => $notary->email,
                'key' => $notary->key
            );
        }
        return $notairesArray;
    }

    public function getWhatever($id)
    {
        $estates = DB::table('estates')
            ->where('id', '=', $id)
            ->get();
        //$type= $estates['type_state'];
        $estates2 = DB::table('estates')
            ->where('type_estate', '=', 'Type of Estate')
            ->get();

        //dd($estates);
        die;
    }

    /**
     * Get estate details
     */
    private function getEstateDetails($id)
    {
        $details = DB::table('estate_details')
            ->where('estate_id', '=', $id)
            ->get();
        // dd($details);

        $detailsArray = array();
        foreach ($details as $detail) {
            $detailsArray = array(
                'id' => $detail->id,
                'estate_id' => $detail->estate_id,
                'seller_email' => $detail->seller_email,
                'seller_phone' => $detail->seller_phone,
                'seller_name' => $detail->seller_name,
                'description' => $detail->description,
                'comment' => $detail->comment,
                'problems' => $detail->problems,
                'encode' => $detail->encode,
                'adapte' => $detail->adapte,
                'year_construction' => $detail->year_construction,
                'year_renovation' => $detail->year_renovation,
                'coordinate_x' => $detail->coordinate_x,
                'coordinate_y' => $detail->coordinate_y,
                'peb' => $detail->peb,
                'price_evaluated' => $detail->price_evaluated,
                'price_market' => $detail->price_market,
                'visit_remarks' => $detail->visit_remarks,
                'estate_description' => $detail->estate_description,
                'town_planning' => $detail->town_planning,
                'more_habitations' => $detail->more_habitations,
                'rooms' => $detail->rooms,
                'bathrooms' => $detail->bathrooms,
                'estate_street' => $detail->estate_street,
                'jardin' => $detail->jardin,
                'electrique' => $detail->electrique,
                'gaz' => $detail->gaz,
                'garage' => $detail->garage,
                'details_commentaire' => $detail->details_commentaire,
                'interior_state' => $detail->interior_state,
                'exterior_state' => $detail->exterior_state,
                'district_state' => $detail->district_state,
                'interior_highlights' => $detail->interior_highlights,
                'exterior_highlights' => $detail->exterior_highlights,
                'interior_weak_point' => $detail->interior_weak_point,
                'exterior_weak_point' => $detail->exterior_weak_point,
                'desires_to_sell' => $detail->desires_to_sell,
                'details_state_interior' => $detail->details_state_interior,
                'details_state_exterior' => $detail->details_state_exterior,
                'agent_notice' => $detail->agent_notice,
                'price_client' => $detail->price_client,
                'price_market' => $detail->price_market,
                'type_bien' => $detail->type_estate,
            );
        }
        //dd($detailsArray);
        return $detailsArray;
    }

    /**
     * Update bloc informations & commentaires of the estate
     */
    public function editInformations(Request $request)
    {
        // Get all data of request
        $data = $request->all();
        $details = array();
        // dd($data);
        $idEstate = $data['estate_id'];
        $idSeller = $data['seller_id'];
        // Init updated
        $updated = false;
        // Init the reponse
        $response = array(
            'status' => false, // Reponse status
            'message' => 'Les informations n\'a pas été mise à jour ou les informations n\'ont pas été modifiées.' // Response message
        );


        if ($data['sale__type_of_sale'] == 'Par agence') {
            foreach ($data as $key => $dat) { // Foreach key of data
                $model = ""; // Variable to save model name
                if (strpos($key, "sale__") !== false) {
                    $model = "App\\Models\\Estate";
                    $key_log = $key;
                    $key = str_replace('sale__', '', $key);
                    $updated = $this->updateData(app($model), $key, $dat, $idEstate, $idEstate, $key_log); // Update data
                }
                Estate::where('id', '=', $idEstate)
                    ->update(['price_published_himself' => 0, 'date_of_sale_himself' => '']);
            }
        }
        if ($data['sale__type_of_sale'] == 'Par lui même') {
            // dd($data);
            foreach ($data as $key => $dat) { // Foreach key of data
                $model = ""; // Variable to save model name
                if (strpos($key, "sale__") !== false) {
                    $model = "App\\Models\\Estate";
                    $key_log = $key;
                    $key = str_replace('sale__', '', $key);
                    $updated = $this->updateData(app($model), $key, $dat, $idEstate, $idEstate, $key_log); // Update data
                }
                Estate::where('id', '=', $idEstate)
                    ->update(['agency_name' => '', 'price_published_agence' => 0, 'date_of_sale_agence' => '']);
            }
        }
        if ($data['sale__type_of_sale'] == 'Le deux') {
            foreach ($data as $key => $dat) { // Foreach key of data
                $model = ""; // Variable to save model name
                if (strpos($key, "sale__") !== false) {
                    $model = "App\\Models\\Estate";
                    $key_log = $key;
                    $key = str_replace('sale__', '', $key);
                    $updated = $this->updateData(app($model), $key, $dat, $idEstate, $idEstate, $key_log); // Update data
                }
            }
        }
        if ($data['sale__type_of_sale'] == 'Non') {
            foreach ($data as $key => $dat) { // Foreach key of data
                $model = ""; // Variable to save model name
                if (strpos($key, "sale__") !== false) {
                    $model = "App\\Models\\Estate";
                    $key_log = $key;
                    $key = str_replace('sale__', '', $key);
                    $updated = $this->updateData(app($model), $key, $dat, $idEstate, $idEstate, $key_log); // Update data
                }
                Estate::where('id', '=', $idEstate)
                    ->update(['agency_name' => '', 'price_published_agence' => 0, 'date_of_sale_agence' => '', 'price_published_himself' => 0, 'date_of_sale_himself' => '']);
            }
        }
        // Get keys of data
        foreach ($data as $key => $dat) { // Foreach key of data
            $model = ""; // Variable to save model name
            $id = ""; // Variable to save the id
            if (strpos($key, "estate__") !== false) {
                $model = "App\\Models\\Estate";
                $key_log = $key;
                $key = str_replace('estate__', '', $key);
                $id = $idEstate;
                if ($key == 'attempt_via_agency') {
                    $dat = ($dat == 'Oui') ? 1 : 0;
                }
                $updated = $this->updateData(app($model), $key, $dat, $id, $idEstate, $key_log); // Update data
            }
            if (strpos($key, "seller_") !== false) {
                $model = "App\\Models\\Seller";
                $key_log = $key;
                $key = str_replace('seller_', '', $key);
                $id = $idSeller;
                if ($key == 'want_stay_tenant') {
                    $dat = ($dat == 'Oui') ? 1 : 0;
                }
                $updated = $this->updateData(app($model), $key, $dat, $id, $idEstate, $key_log); // Update data
            }
            // TO SAVE DETAILS IN JSON
            if (str_starts_with($key, 'details__')) {
                $key = str_replace('details__', '', $key);
                $details[$key] = $dat;
                $nameSeller = '';
                if ($key == 'lastName') {
                    Seller::where('id', '=', $idSeller)->update(['name' => $dat]);
                }
                if ($key == 'firstName') {
                    $seller = Seller::where('id', '=', $idSeller)->get();
                    $nameSeller = $seller[0]->name . ' ' . $dat;
                    Seller::where('id', '=', $idSeller)->update(['name' => $nameSeller]);
                }
                if ($key == 'email') {
                    Seller::where('id', '=', $idSeller)->update(['email' => $dat]);
                }
                if ($key == 'phone') {
                    Seller::where('id', '=', $idSeller)->update(['phone' => $dat]);
                }
            }
        }
        if ($updated) { // If updated is true
            $response = array(
                'status' => true,
                'message' => 'Les informations a été mise à jour'
            );
        }

        if (!$updated) { // If updated is false
            $reponse = array(
                'status' => false,
                'message' => 'Certaines données n\'ont pas pu être mises à jour ou ont la même valeur, veuillez réessayer plus tard ...'
            );
        }
        // Save estatus en attente d'une offre
        $estates = $this->getEstates();
        // If session is of the a manager
        if (Auth::user()->type == 2) {
            // Get estates to show to manager
            $estates = $this->getEstatesToManager(Auth::user()->id);
        }
        foreach ($estates as $estate) {
            if (empty($estate['offre']) && $estate['module_visit'] == 1) {
                $updated = $this->updateData(app("App\\Models\\Estate"), 'category', 4, $estate['id'], $estate['id'], 'category'); // Update data
            }
        }
        $this->countEstatesCategory(); // Save the total estates that they have a category
        $this->countCategories(); // Save if a category is parent

        //Save json of the details in the DB
        try {
            // Update value where
            EstateDetail::where('estate_id', '=', $data['estate_id'])
                ->update(['encode' => json_encode($details)]);
            $response = array(
                'status' => true,
                'message' => 'Les informations a été mise à jour'
            );
        } catch (\Exception $e) {
            // Updated false
            $this->updated = false;
        }

        // Return response
        return response($response)->header('Content-Type', 'application/json');
    }

    /**
     * Count categories
     */
    private function countCategories()
    {
        // Code to save if a category is parent
        $categories = $this->getCategories(); // Get categories
        $parent = array(); // Array to save all parents of the categories
        foreach ($categories as $category) {
            $parent[$category['parent']] = $category['parent']; // Save only the parent of the category
        }
        Category::query()->update(['has_child' => 0]);
        foreach ($parent as $key => $value) {
            if ($value != 0 || $value != null) { // If parent is differnt of 0 or if is null
                // Update value where
                Category::where('id', '=', $value)->update(['has_child' => 1]);
            }
        }
    }

    /**
     * Count ESTATES categry
     */
    private function countEstatesCategory()
    {
        // Code to save the total estates that they have a category
        $categories = $this->getCategories(); // Get categories
        foreach ($categories as $category) {
            // Get the category of each estate
            $estate = Estate::where('category', '=', $category['id'])->get();
            $value = $estate->count(); // Count total of estates that they have this category
            $model = app("App\\Models\\Category"); // Variable model category
            $id = $category['id']; // Variable to save the id category
            $field = 'count'; // Variable to save the value to field 'count'
            // Get all register
            $register = $model::find($id);
            // Verify if the new value is different to current value
            if ($register->$field != $value) {
                try {
                    // Update value where
                    $model::where('id', '=', $id)
                        ->update([$field => $value]);
                    // Updated true
                    $this->updated = true;
                } catch (\Exception $e) {
                    // Updated false
                    $this->updated = false;
                }
            }
        }
    }

    /**
     * Get logs
     */
    private function getLogs($estate_id)
    {
        $logs = EstateLog::select(
            "estate_logs.id",
            "estate_logs.estate_id",
            "users.name as username",
            "estate_logs.old_value",
            "estate_logs.new_value",
            "estate_logs.field",
            "estate_logs.created_at"
        )->join("users", "users.id", "=", "estate_logs.user_id")
            ->where('estate_id', '=', $estate_id)
            ->get();
        $logsArray = array();
        foreach ($logs as $log) {
            $logsArray[] = array(
                'id' => $log->id,
                'estate_id' => $log->estate_id,
                'user_id' => $log->username,
                'old_value' => $log->old_value,
                'new_value' => $log->new_value,
                'field' => $log->field,
                'created' => $log->created_at
            );
        }
        return $logsArray;
    }

    /**
     * Update details of the estate
     */
    public function editDetails(Request $request)
    {
        // Get all data of request
        $data = $request->all();
        //dd($data);die;
        // Init updated
        $updated = false;

        // Init the response
        $response = array(
            'status' => false, // Response status
            'message' => 'Les informations n\'ont pas été mises à jour ou les informations n\'ont pas été modifiées.' // Response message
        );

        // Update estate_details if estate_id is provided
        if (isset($data['estate_id'])) {
            $estateDetails = EstateDetail::where('estate_id', $data['estate_id'])->first();

            if ($estateDetails) {
                //  $estateDetails->adapte = json_encode($datosAdapte);
                $estateDetails->estate_description = $data['estate_description'];
                $estateDetails->seller_email = $data['seller_email'];
                $estateDetails->seller_phone = $data['seller_phone'];
                $estateDetails->seller_name = $data['seller_name'];
                $estateDetails->year_construction = $data['year_construction'];
                $estateDetails->year_renovation = $data['year_renovation'];
                $estateDetails->peb = $data['peb'];
                $estateDetails->town_planning = $data['town_planning'];
                $estateDetails->price_evaluated = $data['price_evaluated'];
                $estateDetails->more_habitations = $data['more_habitations'] == 'oui' ? 1 : 0;
                $estateDetails->rooms = $data['rooms'];
                $estateDetails->bathrooms = $data['bathrooms'];
                $estateDetails->estate_street = $data['estate_street'];
                $estateDetails->coordinate_x = $data['coordinate_x'];
                $estateDetails->coordinate_y = $data['coordinate_y'];
                $estateDetails->jardin = $data['jardin'] == 'oui' ? 1 : 0;
                $estateDetails->garage = $data['garage'] == 'oui' ? 1 : 0;
                $estateDetails->gaz = $data['gaz'];
                $estateDetails->electrique = $data['electrique'];
                $estateDetails->details_commentaire = $data['details_commentaire'];
                $estateDetails->interior_state = $data['interior_state'];
                $estateDetails->exterior_state = $data['exterior_state'];
                $estateDetails->district_state = $data['district_state'];
                $estateDetails->interior_highlights = $data['interior_highlights'];
                $estateDetails->exterior_highlights = $data['exterior_highlights'];
                $estateDetails->interior_weak_point = $data['interior_weak_point'];
                $estateDetails->exterior_weak_point = $data['exterior_weak_point'];
                $estateDetails->desires_to_sell = $data['desires_to_sell'];
                $estateDetails->details_state_interior = $data['details_state_interior'];
                $estateDetails->details_state_exterior = $data['details_state_exterior'];
                $estateDetails->agent_notice = $data['agent_notice'];
                $estateDetails->price_market = $data['price_market'];
                $estateDetails->price_client = $data['price_client'];
                //$estateDetails->visit_remarks = $data['visit_remarks'];
                $estateDetails->type_estate = $data['type_estate'];

                $estateDetails->save();

                $updated = true;
            }
            if (!$estateDetails) {
                $estateDetails = new EstateDetail;

                //  $estateDetails->adapte = json_encode($datosAdapte);
                $estateDetails->estate_description = $data['estate_description'];
                $estateDetails->seller_email = $data['seller_email'];
                $estateDetails->seller_phone = $data['seller_phone'];
                $estateDetails->seller_name = $data['seller_name'];
                $estateDetails->year_construction = $data['year_construction'];
                $estateDetails->year_renovation = $data['year_renovation'];
                $estateDetails->peb = $data['peb'];
                $estateDetails->town_planning = $data['town_planning'];
                $estateDetails->price_evaluated = $data['price_evaluated'];
                $estateDetails->more_habitations = $data['more_habitations'] == 'oui' ? 1 : 0;
                $estateDetails->rooms = $data['rooms'];
                $estateDetails->bathrooms = $data['bathrooms'];
                $estateDetails->estate_street = $data['estate_street'];
                $estateDetails->coordinate_x = $data['coordinate_x'];
                $estateDetails->coordinate_y = $data['coordinate_y'];
                $estateDetails->jardin = $data['jardin'] == 'oui' ? 1 : 0;
                $estateDetails->garage = $data['garage'] == 'oui' ? 1 : 0;
                $estateDetails->gaz = $data['gaz'];
                $estateDetails->electrique = $data['electrique'];
                $estateDetails->details_commentaire = $data['details_commentaire'];
                $estateDetails->details_state_interior = $data['details_state_interior'];
                $estateDetails->details_state_exterior = $data['details_state_exterior'];
                $estateDetails->district_state = $data['district_state'];
                $estateDetails->interior_highlights = $data['interior_highlights'];
                $estateDetails->exterior_highlights = $data['exterior_highlights'];
                $estateDetails->interior_weak_point = $data['interior_weak_point'];
                $estateDetails->exterior_weak_point = $data['exterior_weak_point'];
                $estateDetails->desires_to_sell = $data['desires_to_sell'];
                $estateDetails->details_state_interior = $data['details_state_interior'];
                $estateDetails->details_state_exterior = $data['details_state_exterior'];
                $estateDetails->agent_notice = $data['agent_notice'];
                $estateDetails->price_market = $data['price_market'];
                $estateDetails->price_client = $data['price_client'];
                // $estateDetails->visit_remarks = $data['visit_remarks'];
                $estateDetails->type_estate = $data['type_estate'];

                $estateDetails->save();

                $updated = true;
            }
        }

        // If updated, set response status and message
        if ($updated) {
            $response['status'] = true;
            $response['message'] = 'Les informations ont été mises à jour avec succès.';
        }

        // Redirect to visits page with response message
        return redirect()->route('visits')->with('response', $response);
        // Return response
        // return redirect()->route('visits')->with('success', 'La visita ha sido actualizada correctamente.');
    }

    /**
     * Update details of the estate
     */
    public function editDetailsAdapte(Request $request)
    {
        // Get all data of request
        $data = $request->all();
        $details = array();
        // Init updated
        $updated = false;
        // Init the reponse
        $response = array(
            'status' => false, // Reponse status
            'message' => 'Les informations n\'a pas été mise à jour ou les informations n\'ont pas été modifiées.' // Response message
        );

        //Save json of the details in the DB
        try {
            // Get keys of data
            foreach ($data as $key => $dat) { // Foreach key of data
                // TO SAVE DETAILS IN JSON
                if (str_starts_with($key, 'details__')) {
                    $key = str_replace('details__', '', $key);
                    $details[$key] = $dat;
                }
            }
            // Update value where
            EstateDetail::where('estate_id', '=', $data['estate_id'])
                ->update(['adapte' => json_encode($details)]);
            $response = array(
                'status' => true,
                'message' => 'Les informations a été mise à jour'
            );
        } catch (\Exception $e) {
            // Updated false
            $this->updated = false;
        }

        // Return response
        return response($response)->header('Content-Type', 'application/json');
    }

    /**
     * Get status
     */
    private function getStatus($estate_id)
    {
        $logs = EstateStatus::select(
            "estate_status.estate_id",
            "categories.name as categoryname",
            "users.name as username",
            "estate_status.start_at",
            "estate_status.stop_at"
        )->join("users", "users.id", "=", "estate_status.user_id")
            ->join("categories", "categories.id", "=", "estate_status.category_id")
            ->where('estate_id', '=', $estate_id)
            ->orderby('start_at', 'ASC')
            ->get();
        $logsArray = array();
        foreach ($logs as $log) {
            $logsArray[] = array(
                'estate_id' => $log->estate_id,
                'categoryname' => $log->categoryname,
                'username' => $log->username,
                'start_at' => $log->start_at,
                'stop_at' => $log->stop_at
            );
        }
        return $logsArray;
    }

    /**
     * Get last status of a estate
     */
    private function getLastStatus($estate_id)
    {
        $logs = EstateStatus::select(
            "estate_status.id",
            "estate_status.estate_id",
            "categories.name as categoryname",
            "users.name as username",
            "estate_status.start_at",
            "estate_status.stop_at"
        )->join("users", "users.id", "=", "estate_status.user_id")
            ->join("categories", "categories.id", "=", "estate_status.category_id")
            ->where('estate_id', '=', $estate_id)
            ->orderby('start_at', 'DESC')->take(1)
            ->get();
        $logsArray = array();
        foreach ($logs as $log) {
            $logsArray = array(
                'id' => $log->id,
                'estate_id' => $log->estate_id,
                'categoryname' => $log->categoryname,
                'username' => $log->username,
                'start_at' => $log->start_at,
                'stop_at' => $log->stop_at
            );
        }
        return $logsArray;
    }

    /**
     * Get categories
     */
    private function getCategories()
    {
        $categories = Category::get();
        $categoriesArray = array();
        foreach ($categories as $category) {
            $categoriesArray[] = array(
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'parent' => $category->parent,
                'has_child' => $category->has_child,
                'count' => $category->count
            );
        }
        return $categoriesArray;
    }

    /**
     * Get category
     */
    private function getCategory($id)
    {
        $categories = Category::where('id', '=', $id)->get();
        $categoriesArray = array();
        foreach ($categories as $category) {
            $categoriesArray = array(
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'parent' => $category->parent,
                'has_child' => $category->has_child,
                'count' => $category->count
            );
        }
        return $categoriesArray;
    }

    /**
     * Get all sities immibiliers
     */
    private function getRealestate()
    {
        $sities = RealEstate::get();
        $sitiesArray = array();
        foreach ($sities as $site) {
            $sitiesArray[] = array(
                'id' => $site->id,
                'name' => $site->name
            );
        }
        return $sitiesArray;
    }

    /**
     * Create new advertisement
     */
    public function newAdvertisement(Request $request)
    {
        //Get data of the request
        $data = $request->all();
        //Init the response
        $response = array(
            'status' => false,
            'message' => 'Le nouveau site immobilier n\'a pas pu être créée. Réessayez plus tard.'
        );

        try {

            $es = EstateRealEstate::create([
                'estate_id' => $data['estate_id'],
                'realestate_id' => $data['estate_form_ads_site'],
                'refrence' => $data['estate_ads_ref'],
                'url' => $data['estate_ads_url'],
                'put_online' => $data['estate_ads_online'],
                'price' => $data['estate_ads_price']
            ]);
            $response = array(
                'status' => true,
                'message' => 'Le nouveau site immobilier a été créée'
            );
        } catch (\Exception $e) {
            $message = "";
            if ($e->getCode() == 23000) {
                $message = 'Le nouveau site immobilier existe déjà';
            }
            $response = array(
                'status' => false,
                'message' => $message,
                'data' => $data
            );
        }

        //Return response
        return response($response)->header('Content-Type', 'application/json');
    }

    /**
     * Get all sities immibiliers
     */
    private function getEstateAdvertisement($estate_id)
    {
        $estateadv = EstateRealEstate::select(
            "estate_realestate.id",
            "estate_realestate.estate_id",
            "realestates.name as realestatename",
            "estate_realestate.refrence",
            "estate_realestate.url",
            "estate_realestate.put_online",
            "estate_realestate.price"
        )->join("realestates", "realestates.id", "=", "estate_realestate.realestate_id")
            ->where('estate_id', '=', $estate_id)
            ->get();
        $advsArray = array();
        foreach ($estateadv as $adv) {
            $putonline = strtotime($adv->put_online);
            $putonline = date('d.m.y', $putonline);
            $advsArray[] = array(
                'id' => $adv->id,
                'estate_id' => $adv->estate_id,
                'realestatename' => $adv->realestatename,
                'refrence' => $adv->refrence,
                'url' => $adv->url,
                'put_online' => $putonline,
                'price' => $adv->price
            );
        }
        return $advsArray;
    }

    /**
     * Get settings
     */
    private function getSettings()
    {
        $settings = Setting::get();
        $settingsArray = array();
        foreach ($settings as $setting) {
            $settingsArray[] = array(
                'id' => $setting->id,
                'type' => $setting->type,
                'name' => $setting->name,
                'value' => $setting->value
            );
        }
        return $settingsArray;
    }

    // /**
    //	* Get estate reminders
    //	*/
    // private function getEstateReminder($estate_id) {
    // 	$estatereminder = EstateReminder::select(
    // 		"estate_reminders.id",
    // 		"estate_reminders.estate_id",
    // 		"estate_reminders.reminders_phone",
    // 		"estate_reminders.reminders_rdv",
    // 		"estate_reminders.reminders_offer"
    // 	)
    // 	->where('estate_id', '=', $estate_id)
    // 	->get();
    // 	$remindersArray = array();
    // 	foreach ($estatereminder as $reminder) {
    // 		$remindersArray = array(
    // 			'id' => $reminder->id,
    // 			'estate_id' => $reminder->estate_id,
    // 			'reminders_phone' => $reminder->reminders_phone,
    // 			'reminders_rdv' => $reminder->reminders_rdv,
    // 			'reminders_offer' => $reminder->reminders_offer
    // 		);
    // 	}
    // 	return $remindersArray;
    // }

    /**
     * Update reminders
     */
    public function updateReminders(Request $request)
    {
        // Get all data of request
        $data = $request->all();
        // Save new data of reminders telephone
        $reminders_phone = array(
            $data['tel_reminder_one'],
            $data['tel_reminder_two'],
            $data['tel_reminder_three']
        );
        $reminders_phone = serialize($reminders_phone); // Serialize to be saved
        // Save new data of reminders RDV
        $reminders_rdv = array(
            $data['rdv_reminder_one'],
            $data['rdv_reminder_two'],
            $data['rdv_reminder_three']
        );
        $reminders_rdv = serialize($reminders_rdv); // Serialize to be saved
        // Save new data of reminders responses
        $reminders_offer = array(
            $data['response_reminder_one'],
            $data['response_reminder_two'],
            $data['response_reminder_three']
        );
        $reminders_offer = serialize($reminders_offer); // Serialize to be saved
        $reminders['reminders_phone'] = $reminders_phone;
        $reminders['reminders_rdv'] = $reminders_rdv;
        $reminders['reminders_offer'] = $reminders_offer;

        // Init updated
        $updated = false;
        // Init the reponse
        $response = array(
            'status' => false, // Reponse status
            'message' => 'La catégorie n\'a pas été mise à jour ou les informations n\'ont pas été modifiées.' // Response message
        );

        // Get keys of data
        foreach ($reminders as $key => $dat) { // Foreach key of data
            $updated = $this->updateData(app("App\\Models\\EstateReminder"), $key, $dat, $data['reminder_id'], $data['estate_id'], $key); // Updatate data
        }
        if ($updated) { // If updated is true
            $response = array(
                'status' => true,
                'message' => 'La catégorie a été mise à jour'
            );
        }

        if (!$updated) { // If updated is false
            $reponse = array(
                'status' => false,
                'message' => 'Certaines données n\'ont pas pu être mises à jour ou ont la même valeur, veuillez réessayer plus tard ...'
            );
        }


        // Return response
        return response($response)->header('Content-Type', 'application/json');
    }

    /**
     * Upload photo
     * Save photo on the server
     */
    public function uploadPhoto(Request $request, $disk)
    {
        // Init response
        $response = array('status' => false);
        // Get data of request
        $photo = $request->all();
        // If the photo is set
        if (isset($photo['photo'])) {
            // Get info of photo
            $fileinfo = explode(",", $photo['photo']);
            // Get type of photo
            $type = $fileinfo[0];
            $type = explode("/", $type)[1];
            $type = explode(";", $type)[0];
            // Get the content of photo
            $data = base64_decode($fileinfo[1]);
            if (str_starts_with($photo['namePhoto'], 'main_photo')) {
                // Get the file name
                $fileName = $photo['namePhoto'] . '.' . $type;
            } else {
                // Get the file name
                $fileName = $photo['namePhoto'];
            }
            // If photo is created on the server
            if (\Storage::disk($disk)->put($fileName, $data)) {
                // Set response
                $response = array(
                    'status' => true, // Status true
                );
            }
        }
        // If the image start with main_photo
        if (str_starts_with($photo['namePhoto'], 'main_photo')) {
            // Get estate id to send in the function updateData
            $estate_id = str_replace('main_photo_', '', $photo['namePhoto']);
            $updated = $this->updateData(app("App\\Models\\Estate"), 'main_photo', $photo['namePhoto'] . '.' . $type, $estate_id, $estate_id, 'main_photo'); // Updatate data
        } else {
            $media = Media::create([ // Create new media to pictures or documents
                'estate_id' => $photo['estateid'],
                'name' => $photo['namePhoto'],
                'file_name' => time() . '.' . $type,
                'type' => $photo['typefile'],
                'size' => $photo['size'],
                'extension' => $type
            ]);
            // Set response
            $response = array(
                'status' => true, // Status true
                'uid' => $photo['uid'],
                'id' => $media['id']
            );
        }

        // Return response in content type json
        return response($response)->header('Content-Type', 'application/json');
    }

    /**
     * Get medias of the estate
     */
    private function getMedias($estate_id)
    {
        $medias = Media::where('estate_id', '=', $estate_id)->get();
        $mediasArray = array();
        foreach ($medias as $media) {
            $mediasArray[] = array(
                'id' => $media->id,
                'estate_id' => $media->estate_id,
                'name' => $media->name,
                'file_name' => $media->file_name,
                'type' => $media->type
            );
        }
        return $mediasArray;
    }

    /**
     * Delete site immobilier
     */
    public function deleteMedia($id)
    {
        // Init updated
        $deleted = false;
        // Init the reponse
        $response = array(
            'status' => false, // Reponse status
            'message' => 'Le site n\'a pas été supprimé' // Response message
        );
        // Get medias of the estate
        $medias = Media::where('id', '=', $id)->get();
        $mediasArray = array();
        foreach ($medias as $media) {
            $mediasArray = array(
                'id' => $media->id,
                'estate_id' => $media->estate_id,
                'name' => $media->name,
                'file_name' => $media->file_name,
                'type' => $media->type
            );
        }
        // Create status log
        EstateLog::create([
            'estate_id' => $mediasArray['estate_id'],
            'user_id' => Auth::user()->id,
            'old_value' => $mediasArray['file_name'],
            'new_value' => '',
            'field' => 'Supprimé - media'
        ]);

        // Deleting the user
        $deleted = $this->deleteData(app("App\\Models\\Media"), $id);

        // If updated is true
        if ($deleted) {
            $response = array(
                'status' => true,
                'message' => 'Le site a été supprimé'
            );
        }
        // If updated is false
        if (!$deleted) {
            $reponse = array(
                'status' => false,
                'message' => 'Certaines données n\'ont pas pu être supprimées, veuillez réessayer plus tard ...'
            );
        }
        // Return response
        return response($response)->header('Content-Type', 'application/json');
    }

    /**
     * Get offres
     */
    private function getOffres()
    {
        $offres = EstateOffre::get();
        $offresArray = array();
        foreach ($offres as $offre) {
            $offresArray[] = array(
                'id' => $offre->id,
                'estate_id' => $offre->estate_id,
                'price_seller' => $offre->price_seller,
                'price_wesold' => $offre->price_wesold,
                'price_market' => $offre->price_market,
                'other_offer' => $offre->other_offer
            );
        }
        return $offresArray;
    }

    /**
     * Get offre of the estate
     */
    private function getOffre($estate_id)
    {
        $offres = EstateOffre::where('estate_id', '=', $estate_id)->get();
        $offreArray = array();
        foreach ($offres as $offre) {
            $offreArray = array(
                'id' => $offre->id,
                'estate_id' => $offre->estate_id,
                'price_seller' => $offre->price_seller,
                'price_wesold' => $offre->price_wesold,
                'price_market' => $offre->price_market,
                'other_offer' => $offre->other_offer,
                'notaire' => $offre->notaire,
                'condition_offer' => $offre->condition_offer,
                'validity' => $offre->validity,
                'textadded' => $offre->textadded,
                'pdf' => $offre->pdf
            );
        }
        return $offreArray;
    }

    /**
     * Update offre of the estate
     */
    public function updateOffer(Request $request)
    {
        // Get all data of request
        $data = $request->all();
        $estateid = $data['estate_id'];
        $offre = $this->getOffre($estateid);
        // If no exist a offer in this estate a new one is created
        if (empty($offre)) {
            try {
                // Create new offer of the estate
                EstateOffre::create([
                    'estate_id' => $data['estate_id'],
                    'price_seller' => $data['price_seller'],
                    'price_wesold' => $data['price_wesold'],
                    'price_market' => $data['price_market'],
                    'other_offer' => $data['other_offer']
                ]);
                $response = array( // If response is true
                    'status' => true,
                    'message' => 'Le offre site immobilier a été créée',
                    'data' => $data,
                );
            } catch (\Exception $e) {
                $message = ""; // If
                if ($e->getCode() == 23000) {
                    $message = 'Le offre site immobilier existe déjà';
                }
                $response = array(
                    'status' => false,
                    'message' => $message,
                    'data' => $data
                );
            }

            // Return response
            return response($response)->header('Content-Type', 'application/json');
        } else { // If exist a offer in this estate only a new one is updated
            // Init updated
            $updated = false;
            // Init the reponse
            $response = array(
                'status' => false, // Reponse status
                'message' => 'Le offre n\'a pas été mise à jour ou les informations n\'ont pas été modifiées.' // Response message
            );
            // Get keys of data
            foreach ($data as $key => $dat) { // Foreach key of data
                if ($key != 'other_offer') {
                    if ($dat == NULL) {
                        $dat = 0;
                    }
                }
                if ($key !== '_token') {
                    $updated = $this->updateData(app("App\\Models\\EstateOffre"), $key, $dat, $offre['id'], $estateid, $key); // Updatate data
                }
            }
            if ($updated) { // If updated is true
                $response = array(
                    'status' => true,
                    'message' => 'Le offre a été mise à jour'
                );
            }

            if (!$updated) { // If updated is false
                $reponse = array(
                    'status' => false,
                    'message' => 'Certaines données n\'ont pas pu être mises à jour ou ont la même valeur, veuillez réessayer plus tard ...'
                );
            }

            // Return response
            return response($response)->header('Content-Type', 'application/json');
        }
    }

    /**
     * Get estate remark of the estate
     */
    private function getEstateRemark($estate_id)
    {
        $remarks = EstateRemark::where('estate_id', '=', $estate_id)->get();
        $remarkArray = array();
        foreach ($remarks as $remark) {
            $remarkArray = array(
                'id' => $remark->id,
                'estate_id' => $remark->estate_id,
                'interior_state' => $remark->interior_state,
                'exterior_state' => $remark->exterior_state,
                'district_state' => $remark->district_state,
                'interior_highlights' => $remark->interior_highlights,
                'exterior_highlights' => $remark->exterior_highlights,
                'interior_weak_point' => $remark->interior_weak_point,
                'exterior_weak_point' => $remark->exterior_weak_point,
                'desires_to_sell' => $remark->desires_to_sell,
                'his_estimate' => $remark->his_estimate,
                'accept_price' => $remark->accept_price,
                'agent_notice' => $remark->agent_notice
            );
        }
        return $remarkArray;
    }

    /**
     * Update remarks of the estate
     */
    public function updateRemark(Request $request)
    {
        // Get all data of request
        $data = $request->all();
        // dd($data);
        $estateid = $data['estate_id'];
        $remark = $this->getEstateRemark($estateid);
        // Init the reponse
        $response = array(
            'status' => false, // Reponse status
            'message' => 'Le remarque n\'a pas été supprimé' // Response message
        );
        // If no exist a offer in this estate a new one is created
        if (empty($remark)) {
            // Create new offer of the estate
            EstateRemark::create([
                'estate_id' => ($data['estate_id'] == NULL) ? '' : $data['estate_id'],
                'interior_state' => ($data['interior_state'] == NULL) ? '' : $data['interior_state'],
                'exterior_state' => ($data['exterior_state'] == NULL) ? '' : $data['exterior_state'],
                'district_state' => ($data['district_state'] == NULL) ? '' : $data['district_state'],
                'interior_highlights' => ($data['interior_highlights'] == NULL) ? '' : $data['interior_highlights'],
                'exterior_highlights' => ($data['exterior_highlights'] == NULL) ? '' : $data['exterior_highlights'],
                'interior_weak_point' => ($data['interior_weak_point'] == NULL) ? '' : $data['interior_weak_point'],
                'exterior_weak_point' => ($data['exterior_weak_point'] == NULL) ? '' : $data['exterior_weak_point'],
                'desires_to_sell' => ($data['desires_to_sell'] == NULL) ? 0 : $data['desires_to_sell'],
                'his_estimate' => ($data['his_estimate'] == NULL) ? 0 : $data['his_estimate'],
                'accept_price' => '',
                'agent_notice' => ($data['agent_notice'] == NULL) ? '' : $data['agent_notice'],
            ]);
            $response = array( // If response is true
                'status' => true,
                'message' => 'Le remarque a été créée',
                'data' => $data,
            );
            // Return response
            return response($response)->header('Content-Type', 'application/json');
        } else { // If exist a offer in this estate only a new one is updated
            // Init updated
            $updated = false;
            // Init the reponse
            $response = array(
                'status' => false, // Reponse status
                'message' => 'Le remarque n\'a pas été mise à jour ou les informations n\'ont pas été modifiées.' // Response message
            );
            // Get keys of data
            foreach ($data as $key => $dat) { // Foreach key of data
                if ($dat == NULL) {
                    $dat = '';
                    if ($key === "desires_to_sell" || $key === "his_estimate") {
                        $dat = 0;
                    }
                }
                if ($key !== '_token' && $key !== 'estate_id') {
                    $updated = $this->updateData(app("App\\Models\\EstateRemark"), $key, $dat, $remark['id'], $estateid, $key); // Updatate data
                }
            }
            if ($updated) { // If updated is true
                $response = array(
                    'status' => true,
                    'message' => 'Le remarque a été mise à jour'
                );
            }
            if (!$updated) { // If updated is false
                $reponse = array(
                    'status' => false,
                    'message' => 'Certaines données n\'ont pas pu être mises à jour ou ont la même valeur, veuillez réessayer plus tard ...'
                );
            }

            // Return response
            return response($response)->header('Content-Type', 'application/json');
        }
    }

    /**
     * Mark estate as visited
     */
    public function estateVisited($id, $option)
    {
        $updated = $this->updateData(app("App\\Models\\Estate"), 'module_visit', 1, $id, $id, 'module_visit'); // Updatate data
        if ($option == 'true') {
            $updated = $this->updateData(app("App\\Models\\Estate"), 'category', 15, $id, $id, 'category'); // Updatate data
        }
        return back()->with('message', 'Propriété mise à jour avec succès.');
        //return redirect()->route('visits');
    }

    /**
     * Get templates
     */
    private function getTemplates()
    {
        $templates = Template::get();
        $templatesArray = array();
        foreach ($templates as $template) {
            $templatesArray[] = array(
                'id' => $template->id,
                'name' => $template->name,
                'subject' => $template->subject,
                'file' => $template->file,
                'type' => $template->type
            );
        }
        return $templatesArray;
    }

    /**
     * Get templates of process of the reminders
     */
    private function getTemplatesReminders()
    {
        $templates = TemplateReminder::get();
        $templatesArray = array();
        foreach ($templates as $template) {
            $templatesArray[] = array(
                'id' => $template->id,
                'name' => $template->name,
                'reminder' => $template->reminder,
                'type' => $template->type
            );
        }
        return $templatesArray;
    }

    /**
     * Send SMS
     */
    public function sendSMS(Request $request)
    {
        // Get all data of request
        $data = $request->all();
        try {
            $basic = new \Nexmo\Client\Credentials\Basic('20c3b951', '3C9zf1Y4cH2UH5Xu');
            $client = new \Nexmo\Client($basic);
            // Sending SMS
            $message = $client->message()->send([
                'to' => '527731951309',
                'from' => 'Wesold',
                'text' => $data['form_phone_message']
            ]);

            $response = array(
                'status' => true,
                'message' => 'Le sms a été envoyé'
            );
        } catch (Exception $e) {
            $reponse = array(
                'status' => false,
                'message' => 'Le sms n\'a pas pu être envoyé, veuillez réessayer plus tard...'
            );
        }

        // Return response
        return response($response)->header('Content-Type', 'application/json');
    }

    /**
     * Send Email
     */
    public function sendEmail(Request $request)
    {
        $data = new \stdClass(); // Create new object called $data
        $data->form_phone_message = $request->form_phone_message; // Save body of the email
        $data->subject = $request->form_offer_title; // Save subject of the email
        $data->from = Auth::user()->email; // Save email of the user authenticated
        $data->fromName = Auth::user()->name; // Save name of the user authenticaded

        try {
            // Sending email
            Mail::to($request->seller_email)->send(new MailableEmail($data));
            $response = array( // If response is true
                'status' => true,
                'message' => 'Le e-mail a été envoyé'
            );
        } catch (Exception $e) {
            $reponse = array( // If response is false
                'status' => false,
                'message' => 'Le e-mail n\'a pas pu être envoyé, veuillez réessayer plus tard...'
            );
        }
        // Return response
        return response($response)->header('Content-Type', 'application/json');
    }

    /**
     * Send offer
     */
    public function sendOffer(Request $request)
    {
        // Array to send data to the pdf template
        $inf = array(
            'img_logo' => asset('img/logo_pdf.png'),
            'img_firm' => asset('img/company.png'),
            'header' => asset('img/FigsHeader.png'),
            'footer' => asset('img/FigsFooter.png'),
            'address' => $request->address_estate,
            'price' => $request->price_estate
        );
        $historic = array(
            'localisation' => $request->inf_localisation,
            'seller' => $request->inf_seller,
            'assign' => $request->inf_assign,
            'cherche' => $request->inf_cherche,
            'comment' => $request->inf_comment,
            'description' => $request->inf_description,
            'interior' => $request->inf_interior,
            'exterior' => $request->inf_exterior,
            'problems' => $request->inf_problems,
            'remarks' => $request->inf_remarks,
            'offer' => $request->inf_offer,
            'validpd' => $request->show_photos_doc,
            'photos' => $request->photos,
            'documents' => $request->documents,
            'img_logo' => asset('img/logo_pdf.png'),
            'header' => asset('img/FigsHeader.png'),
            'footer' => asset('img/FigsFooter.png')
        );
        // CREATE PDF HISTORICAL
        // Load variable data in the view
        view()->share('historic', $historic);
        // Load funcion PDF
        $pdfHistoric = \PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('pdf.historical');
        // CREATE PDF OFFER
        // Load variable data in the view
        view()->share('data', $inf);
        // Load funcion PDF
        $pdf = \PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('pdf.pdf');
        // Generate the output of file pdf
        $time = time();
        $output = $pdf->output();
        $outputh = $pdfHistoric->output();
        \Storage::disk('pdfs')->put($time . "-offre.pdf", $output);
        \Storage::disk('pdfs')->put($time . "-historical.pdf", $outputh);
        $file[] = public_path('pdfs/' . $time . "-offre.pdf");
        $file[] = public_path('pdfs/' . $time . "-historical.pdf");
        $data = new \stdClass(); // Create new object called $data
        $data->form_phone_message = $request->form_message; // Save body of the email
        $data->subject = $request->form_offer_title; // Save subject of the email
        $data->from = Auth::user()->email; // Save email of the user authenticated
        $data->fromName = Auth::user()->name; // Save name of the user authenticaded
        $data->files = $file;

        try {
            // Sending email
            Mail::to($request->seller_email)->send(new EmailT($data));
            \Storage::disk('pdfs')->delete($time . "-offre.pdf");
            \Storage::disk('pdfs')->delete($time . "-historical.pdf");
            $response = array( // If response is true
                'status' => true,
                'message' => 'Le e-mail a été envoyé'
            );
        } catch (Exception $e) {
            $reponse = array( // If response is false
                'status' => false,
                'message' => 'Le e-mail n\'a pas pu être envoyé, veuillez réessayer plus tard...'
            );
        }

        // Return response
        return response($response)->header('Content-Type', 'application/json');
    }

    /**
     * Export CSV
     */
    public function exportCSV(Request $request)
    {
        $data = $request->all();
        // Get status
        $status = $this->getStatus($data['estate_id']);
        $filename = "estates_status_" . $data['estate_id'] . ".csv";
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=" . $filename,
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];
        $columns = array('Statut', 'Start', 'Stop', 'User');
        $callback = function () use ($status, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($status as $stat) {
                fputcsv($file, array($stat['categoryname'], $stat['start_at'], $stat['stop_at'], $stat['username']));
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Save PDF in Offer
     */
    public function savePDFoffer(Request $request)
    {
        // Array to send data to the pdf template
        $inf = array(
            'img_logo' => asset('img/logo_pdf.png'),
            'img_firm' => asset('img/company.png'),
            'header' => asset('img/FigsHeader.png'),
            'footer' => asset('img/FigsFooter.png'),
            'info' => $request->bodyPDF
        );
        // CREATE PDF OFFER
        // Load variable data in the view
        view()->share('data', $inf);
        // Load funcion PDF
        $pdf = \PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('pdf.pdf');
        // Generate the output of file pdf
        $time = time();
        $output = $pdf->output();
        \Storage::disk('pdfs')->put($time . "-offre.pdf", $output);
        $file = public_path('pdfs/' . $time . "-offre.pdf");

        // Get offer
        $offer = $this->getOffre($request->estate_id);
        if (empty($offer)) {
            EstateOffre::create([
                'estate_id' => $request->estate_id,
                'price_seller' => 0,
                'price_wesold' => $request->price_wesold,
                'price_market' => 0,
                'other_offer' => '',
                'notaire' => $request->notaire,
                'condition_offer' => $request->condition_offer,
                'validity' => $request->validity,
                'textadded' => $request->texteadded,
                'pdf' => $time . "-offre.pdf"
            ]);
        } else {
            \Storage::disk('pdfs')->delete($offer['pdf']);
            $this->updateData(app("App\\Models\\EstateOffre"), 'pdf', $time . "-offre.pdf", $offer['id'], $request->estate_id, 'pdf'); // Updatate data
            $this->updateData(app("App\\Models\\EstateOffre"), 'price_wesold', $request->price_wesold, $offer['id'], $request->estate_id, 'price_wesold'); // Updatate data
            $this->updateData(app("App\\Models\\EstateOffre"), 'notaire', $request->notaire, $offer['id'], $request->estate_id, 'notaire'); // Updatate data
            $this->updateData(app("App\\Models\\EstateOffre"), 'condition_offer', $request->condition_offer, $offer['id'], $request->estate_id, 'condition_offer'); // Updatate data
            $this->updateData(app("App\\Models\\EstateOffre"), 'validity', $request->validity, $offer['id'], $request->estate_id, 'validity'); // Updatate data
            $updated = $this->updateData(app("App\\Models\\EstateOffre"), 'textadded', $request->texteadded, $offer['id'], $request->estate_id, 'textadded'); // Updatate data
        }
        $response = array( // If response is true
            'status' => true,
            'message' => 'Le PDF a été créé'
        );

        // Return response
        return response($response)->header('Content-Type', 'application/json');
    }

    /**
     * Send Email of the offer
     */
    public function sendEmailOffer(Request $request)
    {
        // Get request
        $data = $request->all();
        // dd($data);
        // $data = new \stdClass(); // Create new object called $data
        // $data->form_phone_message = $request->body; // Save body of the email
        // $data->subject = $request->subject; // Save subject of the email
        // $data->from = Auth::user()->email; // Save email of the user authenticated
        // $data->fromName = Auth::user()->name; // Save name of the user authenticaded

        try {
            foreach ($data['emails'] as $email) {
                // Create new ticket and send email
                $pname = explode("@", $email);
                $ticket_id = $this->objectTicket->create(
                    [
                        "name" => $pname[0],
                        "email" => $email,
                    ],
                    $data['subject'],
                    $data['body'],
                    []
                );
                // Save the union of the ticket wiht the estate
                EstateTicket::create([
                    'estate_id' => $data['estate_id'],
                    'ticket_id' => $ticket_id,
                    'no_answer' => 0
                ]);
                // Save information in the log
                EstateLog::create([
                    'estate_id' => $data['estate_id'],
                    'user_id' => Auth::user()->id,
                    'old_value' => '',
                    'new_value' => 'Offer envoyer à ' . $email,
                    'field' => 'demandetel'
                ]);
            }
            $response = array( // If response is true
                'status' => true,
                'message' => 'Le offer a été envoyé'
            );

            // $offer = $this->getOffre($request->estate_id);
            // $data->files[] = asset('pdfs/'.$offer['pdf']);
            // $data->emails = $request->emails;
            // // Sending email
            // Mail::to($request->seller_email)->send(new EmailT($data));
            // $response = array( // If response is true
            // 	'status' => true,
            // 	'message' => 'Le e-mail a été envoyé'
            // );
        } catch (Exception $e) {
            $reponse = array( // If response is false
                'status' => false,
                'message' => 'Le e-mail n\'a pas pu être envoyé, veuillez réessayer plus tard...'
            );
        }
        //Create new log about update
        EstateLog::create([
            'estate_id' => $request->estate_id,
            'user_id' => Auth::user()->id,
            'old_value' => '',
            'new_value' => 'E-mail de l\'offre envoyé à ' . $request->seller_email,
            'field' => 'email_envoye'
        ]);

        // Return response
        return response($response)->header('Content-Type', 'application/json');
    }

    /**
     * Send Email of the offer
     */
    public function saveReminder(Request $request)
    {
        //Get data of the request
        $data = $request->all();
        // dd($data);
        //Init the response
        $response = array(
            'status' => false,
            'message' => 'Le rappel n\'a pas pu être créée. Réessayez plus tard.'
        );
        try {
            // Structure to save reminder only of type task
            if (isset($data['only_task'])) {
                $d = \DateTime::createFromFormat('U.u', microtime(true));
                $_task[] = array(
                    'id' => $d->format("His.u"),
                    'type' => 'task',
                    'subject' => ($data['subject_template'] == null) ? '' : $data['subject_template'],
                    'content' => $data['text-task-rappel'],
                    'date' => $this->isWeekend($data['date_changed']),
                    'seller_id' => $data['seller_id'],
                    'sent' => 0
                );
                EstateReminder::create([
                    'estate_id' => $data['estate_id'],
                    'user_id' => Auth::user()->id,
                    'name_reminder' => $data['name_reminder'],
                    'content' => serialize($_task),
                    'sent' => 0,
                    'hide' => 0,
                    'next_reminder' => 0,
                ]);
                $response = array(
                    'status' => true,
                    'message' => 'Le rappel a été créée',
                    'data' => $data,
                );
            }
            // If no exist process charged
            if (!isset($data['charge_process']) && !isset($data['only_task'])) {
                $allReminders = array();
                // If the reminder is instant
                if (!isset($data['change_date']) && !isset($data['charge_process'])) {
                    $currentDate = date("Y-m-d"); // Current date
                    // Structure to the first reminder "by default"
                    $d = \DateTime::createFromFormat('U.u', microtime(true));
                    $firstReminder = array(
                        'id' => $d->format("His.u"),
                        'type' => $data['type_template_r'],
                        'subject' => ($data['subject_template'] == null) ? '' : $data['subject_template'],
                        'content' => $data['body_mail_reminder'],
                        'date' => $this->isWeekend($currentDate),
                        'seller_id' => $data['seller_id'],
                        'sent' => 1
                    );
                    $allReminders[] = $firstReminder; // Add to the parent array of reminders
                    // Structure to the second reminder "by default"
                    if (isset($data['type_tem_create'])) { // If exist second "by default"
                        $d = \DateTime::createFromFormat('U.u', microtime(true));
                        $secondReminder = array(
                            'id' => $d->format("His.u"),
                            'type' => $data['type_tem_create'],
                            'subject' => ($data['subject_template_s'] == null) ? '' : $data['subject_template_s'],
                            'content' => $data['body_mail_reminder_r'],
                            'date' => $this->isWeekend(date('Y-m-d', strtotime($currentDate . "+ " . $data['days_reminder_r'] . "days"))),
                            'seller_id' => $data['seller_id'],
                            'sent' => 0
                        );
                        $allReminders[] = $secondReminder;
                    }
                    // Structure to reminder dynamics
                    if (isset($data['type_rappel'])) {
                        $aux = array();
                        $increment = 0;
                        $reminders = array();
                        foreach ($data['type_rappel'] as $key => $reminder) {
                            $increment = $increment + ((int)$data['days_reminder'][$key]);
                            $d = \DateTime::createFromFormat('U.u', microtime(true));
                            $reminders[] = array(
                                'id' => $d->format("His.u"),
                                'type' => $reminder,
                                'subject' => ($data['subject_template_d'][$key] == null) ? '' : $data['subject_template_d'][$key],
                                'content' => $data['content_reminder'][$key],
                                'date' => ($increment == 0) ? $this->isWeekend($currentDate) : $this->isWeekend(date('Y-m-d', strtotime($currentDate . "+ " . $increment . "days"))),
                                'seller_id' => $data['seller_id'],
                                'sent' => 0
                            );
                        }
                        // Save reminders dynamics in the parent array
                        foreach ($reminders as $reminder) {
                            $allReminders[] = $reminder;
                        }
                    }
                    // Send instant if the first reminder is type email
                    if ($data['type_template_r'] == 'email' || $data['type_template_r'] == 'task') {
                        $ticket_id = $this->objectTicket->create(
                            [
                                "name" => $data['seller_name'], // Name to which the ticket will be sent
                                "email" => $data['seller_email'], // Email to which the ticket will be sent
                            ],
                            $data['subject_template'], // Subject to the email
                            $data['body_mail_reminder'], // Content to the email
                            []
                        );
                        // Save the union of the ticket wiht the estate
                        EstateTicket::create([
                            'estate_id' => $data['estate_id'],
                            'ticket_id' => $ticket_id,
                            'no_answer' => 0
                        ]);
                    }
                    // // Send instant if first reminder is type sms
                    // if ($data['type_template_r'] == 'sms') {
                    // 	$basic	= new \Nexmo\Client\Credentials\Basic('20c3b951', '3C9zf1Y4cH2UH5Xu');
                    // 	$client = new \Nexmo\Client($basic);
                    // 	// Sending SMS
                    // 	$message = $client->message()->send([
                    // 		'to' => '527731951309',//$data['seller_phone'],
                    // 		'from' => 'Wesold',
                    // 		'text' => $data['body_mail_reminder']
                    // 	]);
                    // 	// Updatate data to next reminder
                    // 	$updated = $this->updateData(app("App\\Models\\EstateReminder"), 'next_reminder', (0 + 1), $reminder['id'], $reminder['estate_id'], 'next_reminder', $reminder['user_id']);
                    // }
                } // End - If the reminder is instant
                // If the reminder isn't instant
                if (isset($data['change_date']) && $data['change_date'] == 'on' && !isset($data['charge_process'])) {
                    // Structure to the first reminder "by default"
                    $d = \DateTime::createFromFormat('U.u', microtime(true));
                    $firstReminder = array(
                        'id' => $d->format("His.u"),
                        'type' => $data['type_template_r'],
                        'subject' => ($data['subject_template'] == null) ? '' : $data['subject_template'],
                        'content' => $data['body_mail_reminder'],
                        'date' => $this->isWeekend($data['date_changed']),
                        'seller_id' => $data['seller_id'],
                        'sent' => 0
                    );
                    $allReminders[] = $firstReminder; // Add to the parent array of reminders
                    // Structure to the second reminder "by default"
                    if (isset($data['type_tem_create'])) { // If exist second "by default"
                        $d = \DateTime::createFromFormat('U.u', microtime(true));
                        $secondReminder = array(
                            'id' => $d->format("His.u"),
                            'type' => $data['type_tem_create'],
                            'subject' => ($data['subject_template_s'] == null) ? '' : $data['subject_template_s'],
                            'content' => $data['body_mail_reminder_r'],
                            'date' => $this->isWeekend(date('Y-m-d', strtotime($data['date_changed'] . "+ " . $data['days_reminder_r'] . "days"))),
                            'seller_id' => $data['seller_id'],
                            'sent' => 0
                        );
                        $allReminders[] = $secondReminder;
                    }
                    // Structure to reminder dynamics
                    if (isset($data['type_rappel'])) {
                        $aux = array();
                        $increment = 0;
                        $reminders = array();
                        foreach ($data['type_rappel'] as $key => $reminder) {
                            $increment = $increment + ((int)$data['days_reminder'][$key]);
                            $d = \DateTime::createFromFormat('U.u', microtime(true));
                            $reminders[] = array(
                                'id' => $d->format("His.u"),
                                'type' => $reminder,
                                'subject' => ($data['subject_template_d'][$key] == null) ? '' : $data['subject_template_d'][$key],
                                'content' => $data['content_reminder'][$key],
                                'date' => ($increment == 0) ? $this->isWeekend($data['date_changed']) : $this->isWeekend(date('Y-m-d', strtotime($data['date_changed'] . "+ " . $increment . "days"))),
                                'seller_id' => $data['seller_id'],
                                'sent' => 0
                            );
                        }
                        // Save reminders dynamics in the parent array
                        foreach ($reminders as $reminder) {
                            $allReminders[] = $reminder;
                        }
                    }
                } // End - If the reminder isn't instant

                // Save reminder in the DB
                $b = EstateReminder::create([
                    'estate_id' => $data['estate_id'],
                    'user_id' => Auth::user()->id,
                    'name_reminder' => $data['name_reminder'],
                    'content' => serialize($allReminders),
                    'sent' => 0,
                    'hide' => 0,
                    'next_reminder' => 0,
                ]);
                // Updatate data to next reminder
                $updated = $this->updateData(app("App\\Models\\EstateReminder"), 'next_reminder', (0 + 1), $b->id, $data['estate_id'], 'next_reminder', Auth::user()->id);
                $response = array(
                    'status' => true,
                    'message' => 'Le rappel a été créée',
                    'data' => $data,
                );
            }
            // If exist process charged
            if (isset($data['charge_process']) && !isset($data['only_task'])) {
                $allReminders = array();
                // If the reminder is instant
                if (!isset($data['change_date'])) {
                    $currentDate = date("Y-m-d"); // Current date
                    // Structure to reminder dynamics
                    if (isset($data['type_rappel'])) {
                        $aux = array();
                        $increment = 0;
                        $reminders = array();
                        foreach ($data['type_rappel'] as $key => $reminder) {
                            $increment = $increment + ((int)$data['days_reminder'][$key]);
                            // IF is the first reminder to send as instant
                            if ($key == 0) {
                                // Save data of the first reminder instant
                                $d = \DateTime::createFromFormat('U.u', microtime(true));
                                $reminders[] = array(
                                    'id' => $d->format("His.u"),
                                    'type' => $reminder,
                                    'subject' => ($data['subject_template_d'][$key] == null) ? '' : $data['subject_template_d'][$key],
                                    'content' => $data['content_reminder'][$key],
                                    'date' => $this->isWeekend($currentDate),
                                    'seller_id' => $data['seller_id'],
                                    'sent' => 0
                                );
                                // if ($reminder == 'email' || $reminder == 'task') {
                                // 	// This is to send a email as a ticket
                                // 	$ticket_id = $this->objectTicket->create(
                                // 		[
                                // 			"name" => $data['seller_name'][$key],// Name to which the ticket will be sent
                                // 			"email" => $data['seller_email'][$key],// Email to which the ticket will be sent
                                // 		],
                                // 		$data['subject_template'][$key],// Subject to the email
                                // 		$data['body_mail_reminder'][$key],// Content to the email
                                // 		[]
                                // 	);
                                // 	// Save the union of the ticket wiht the estate
                                // 	EstateTicket::create([
                                // 		'estate_id' => $data['estate_id'],
                                // 		'ticket_id' => $ticket_id,
                                // 		'no_answer' => 0
                                // 	]);
                                // 	// Updatate data to next reminder
                                // 	$updated = $this->updateData(app("App\\Models\\EstateReminder"), 'next_reminder', ($key + 1), $reminder['id'], $reminder['estate_id'], 'next_reminder', $reminder['user_id']);
                                // }
                                // if ($reminder == 'sms') {
                                // 	$basic	= new \Nexmo\Client\Credentials\Basic('20c3b951', '3C9zf1Y4cH2UH5Xu');
                                // 	$client = new \Nexmo\Client($basic);
                                // 	// Sending SMS
                                // 	$message = $client->message()->send([
                                // 		'to' => '527731951309',
                                // 		'from' => 'Wesold',
                                // 		'text' => $data['body_mail_reminder'][$key]
                                // 	]);
                                // 	// Updatate data to next reminder
                                // 	$updated = $this->updateData(app("App\\Models\\EstateReminder"), 'next_reminder', ($key + 1), $reminder['id'], $reminder['estate_id'], 'next_reminder', $reminder['user_id']);
                                // }
                            }
                            if ($key !== 0) {
                                $subject = '';
                                if (isset($data['subject_template_d'][$key])) {
                                    $subject = ($data['subject_template_d'][$key] == null) ? '' : $data['subject_template_d'][$key];
                                }
                                $d = \DateTime::createFromFormat('U.u', microtime(true));
                                $reminders[] = array(
                                    'id' => $d->format("His.u"),
                                    'type' => $reminder,
                                    'subject' => $subject,
                                    'content' => $data['content_reminder'][$key],
                                    'date' => ($increment == 0) ? $this->isWeekend($currentDate) : $this->isWeekend(date('Y-m-d', strtotime($currentDate . "+ " . $increment . "days"))),
                                    'seller_id' => $data['seller_id'],
                                    'sent' => 0
                                );
                                if ($reminder == 'email' || $reminder == 'task') {
                                    // This is to send a email as a ticket
                                    $ticket_id = $this->objectTicket->create(
                                        [
                                            "name" => $data['seller_name'][$key], // Name to which the ticket will be sent
                                            "email" => $data['seller_email'][$key], // Email to which the ticket will be sent
                                        ],
                                        $data['subject_template'][$key], // Subject to the email
                                        $data['body_mail_reminder'][$key], // Content to the email
                                        []
                                    );
                                    // Save the union of the ticket wiht the estate
                                    EstateTicket::create([
                                        'estate_id' => $data['estate_id'],
                                        'ticket_id' => $ticket_id,
                                        'no_answer' => 0
                                    ]);
                                }
                                if ($reminder == 'sms') {
                                    $basic = new \Nexmo\Client\Credentials\Basic('20c3b951', '3C9zf1Y4cH2UH5Xu');
                                    $client = new \Nexmo\Client($basic);
                                    // Sending SMS
                                    $message = $client->message()->send([
                                        'to' => '527731951309',
                                        'from' => 'Wesold',
                                        'text' => $data['body_mail_reminder'][$key]
                                    ]);
                                    // Updatate data to next reminder
                                    $updated = $this->updateData(app("App\\Models\\EstateReminder"), 'next_reminder', ($key + 1), $reminder['id'], $reminder['estate_id'], 'next_reminder', $reminder['user_id']);
                                }
                            }
                        }
                        // Save reminders dynamics in the parent array
                        foreach ($reminders as $reminder) {
                            $allReminders[] = $reminder;
                        }
                    }
                } // End - If the reminder is instant
                // If the reminder isn't instant
                if (isset($data['change_date'])) {
                    // Structure to reminder dynamics
                    if (isset($data['type_rappel'])) {
                        $aux = array();
                        $increment = 0;
                        $reminders = array();
                        foreach ($data['type_rappel'] as $key => $reminder) {
                            $increment = $increment + ((int)$data['days_reminder'][$key]);
                            // IF is the first reminder to send as instant
                            if ($key == 0) {
                                // Save data of the first reminder instant
                                $d = \DateTime::createFromFormat('U.u', microtime(true));
                                $reminders[] = array(
                                    'id' => $d->format("His.u"),
                                    'type' => $reminder,
                                    'subject' => ($data['subject_template_d'][$key] == null) ? '' : $data['subject_template_d'][$key],
                                    'content' => $data['content_reminder'][$key],
                                    'date' => $this->isWeekend($data['date_changed']),
                                    'seller_id' => $data['seller_id'],
                                    'sent' => 0
                                );
                                // if ($reminder == 'email' || $reminder == 'task') {
                                // 	// This is to send a email as a ticket
                                // 	$ticket_id = $this->objectTicket->create(
                                // 		[
                                // 			"name" => $data['seller_name'][$key],// Name to which the ticket will be sent
                                // 			"email" => $data['seller_email'][$key],// Email to which the ticket will be sent
                                // 		],
                                // 		$data['subject_template'][$key],// Subject to the email
                                // 		$data['body_mail_reminder'][$key],// Content to the email
                                // 		[]
                                // 	);
                                // 	// Save the union of the ticket wiht the estate
                                // 	EstateTicket::create([
                                // 		'estate_id' => $data['estate_id'],
                                // 		'ticket_id' => $ticket_id,
                                // 		'no_answer' => 0
                                // 	]);
                                // }
                                // if ($reminder == 'sms') {
                                // 	$basic	= new \Nexmo\Client\Credentials\Basic('20c3b951', '3C9zf1Y4cH2UH5Xu');
                                // 	$client = new \Nexmo\Client($basic);
                                // 	// Sending SMS
                                // 	$message = $client->message()->send([
                                // 		'to' => '527731951309',
                                // 		'from' => 'Wesold',
                                // 		'text' => $data['body_mail_reminder'][$key]
                                // 	]);
                                // }
                            }
                            if ($key !== 0) {
                                $subject = '';
                                if (isset($data['subject_template_d'][$key])) {
                                    $subject = ($data['subject_template_d'][$key] == null) ? '' : $data['subject_template_d'][$key];
                                }
                                $d = \DateTime::createFromFormat('U.u', microtime(true));
                                $reminders[] = array(
                                    'id' => $d->format("His.u"),
                                    'type' => $reminder,
                                    'subject' => $subject,
                                    'content' => $data['content_reminder'][$key],
                                    'date' => ($increment == 0) ? $this->isWeekend($data['date_changed']) : $this->isWeekend(date('Y-m-d', strtotime($data['date_changed'] . "+ " . $increment . "days"))),
                                    'seller_id' => $data['seller_id'],
                                    'sent' => 0
                                );
                                // if ($reminder == 'email' || $reminder == 'task') {
                                // 	// This is to send a email as a ticket
                                // 	$ticket_id = $this->objectTicket->create(
                                // 		[
                                // 			"name" => $data['seller_name'][$key],// Name to which the ticket will be sent
                                // 			"email" => $data['seller_email'][$key],// Email to which the ticket will be sent
                                // 		],
                                // 		$data['subject_template'][$key],// Subject to the email
                                // 		$data['body_mail_reminder'][$key],// Content to the email
                                // 		[]
                                // 	);
                                // 	// Save the union of the ticket wiht the estate
                                // 	EstateTicket::create([
                                // 		'estate_id' => $data['estate_id'],
                                // 		'ticket_id' => $ticket_id,
                                // 		'no_answer' => 0
                                // 	]);
                                // }
                                // if ($reminder == 'sms') {
                                // 	$basic	= new \Nexmo\Client\Credentials\Basic('20c3b951', '3C9zf1Y4cH2UH5Xu');
                                // 	$client = new \Nexmo\Client($basic);
                                // 	// Sending SMS
                                // 	$message = $client->message()->send([
                                // 		'to' => '527731951309',
                                // 		'from' => 'Wesold',
                                // 		'text' => $data['body_mail_reminder'][$key]
                                // 	]);
                                // }
                            }
                        }
                        // Save reminders dynamics in the parent array
                        foreach ($reminders as $reminder) {
                            $allReminders[] = $reminder;
                        }
                    }
                } // End - If the reminder isnt instant
                // Save reminder in the DB
                $b = EstateReminder::create([
                    'estate_id' => $data['estate_id'],
                    'user_id' => Auth::user()->id,
                    'name_reminder' => $data['name_reminder'],
                    'content' => serialize($allReminders),
                    'sent' => 0,
                    'hide' => 0,
                    'next_reminder' => 0,
                ]);
                // Updatate data to next reminder
                $updated = $this->updateData(app("App\\Models\\EstateReminder"), 'next_reminder', (0 + 1), $b->id, $data['estate_id'], 'next_reminder', Auth::user()->id);
                $response = array(
                    'status' => true,
                    'message' => 'Le rappel a été créée',
                    'data' => $data,
                );
            }
        } catch (\Exception $e) {
            $message = '';
            // var_dump($e->getMessage());
            if ($e->getCode() == 23000) {
                $message = 'Le rappel existe déjà';
            }
            $response = array(
                'status' => false,
                'message' => $e->getMessage(),
                'data' => $data
            );
        }
        //Return response
        return response($response)->header('Content-Type', 'application/json');
    }

    private function isWeekend($date)
    {
        // Convert string to time
        // Get day name from the date
        $dt2 = date("l", strtotime($date));
        // Convert day name to lower case
        $dt3 = strtolower($dt2);
        $date_ = $date;
        // Check if day name is "saturday" or "sunday"
        if (($dt3 == "saturday") || ($dt3 == "sunday")) {
            // If the date is weekend then
            $date_ = date('Y-m-d', strtotime($date . "+ 1 days"));
            $date_ = $this->isWeekend($date_);
        }
        return $date_;
    }

    /**
     * Get reminders of one estate
     */
    public function getReminders($idestate)
    {
        $reminders = EstateReminder::where('estate_id', '=', $idestate)->where('hide', '=', 0)->get();
        $remindersArray = array();
        foreach ($reminders as $reminder) {
            $remindersArray[] = array(
                'id' => $reminder->id,
                'estate_id' => $reminder->estate_id,
                'user_id' => $reminder->user_id,
                'name_reminder' => $reminder->name_reminder,
                'content' => unserialize($reminder->content),
                'sent' => $reminder->sent,
                'hide' => $reminder->hide,
                'next_reminder' => $reminder->next_reminder
            );
        }
        return $remindersArray;
    }

    /**
     * Create edit reminder
     */
    public function editReminder(Request $request)
    {
        // Get all data of request
        $data = $request->all();
        // Init updated
        $updated = false;
        // Init the reponse
        $response = array(
            'status' => false, // Reponse status
            'message' => 'Le rappel n\'a pas été mise à jour ou les informations n\'ont pas été modifiées.' // Response message
        );
        // Get keys of data
        $reminders = array();
        foreach ($data['date_edit'] as $key => $date) {
            $d = \DateTime::createFromFormat('U.u', microtime(true));
            $reminders[] = array(
                'id' => $d->format("His.u"),
                'type' => $data['type_template_edit'][$key],
                'subject' => $data['subject_edit'][$key],
                'content' => $data['content_edit'][$key],
                'date' => $data['date_edit'][$key],
                'seller_id' => $data['seller_id'],
                'sent' => 0
            );
        }
        $reminders = serialize($reminders);
        foreach ($data as $key => $dat) { // Foreach key of data
            $updated = $this->updateData(app("App\\Models\\EstateReminder"), 'content', $reminders, $data['reminder_id'], $data['estate_id'], 'content'); // Update data
        }
        if ($updated) { // If updated is true
            $response = array(
                'status' => true,
                'message' => 'Le rappel a été mise à jour'
            );
        }

        if (!$updated) { // If updated is false
            $reponse = array(
                'status' => false,
                'message' => 'Certaines données n\'ont pas pu être mises à jour ou ont la même valeur, veuillez réessayer plus tard ...'
            );
        }

        // Return response
        return response($response)->header('Content-Type', 'application/json');
    }

    /**
     * Get reminders of one estate
     */
    public function getRemindersTask()
    {
        // $reminders = EstateReminder::where('user_id', '=', Auth::user()->id)->where('type_template', '=', 'task')->where('hide', '=', 0)->where('sent', '=', 0)->get();
        $remindersArray = array();
        // foreach ($reminders as $reminder) {
        // 	$remindersArray[] = array(
        // 		'id' => $reminder->id,
        // 		'estate_id' => $reminder->estate_id,
        // 		'details_estate' => $this->getEstate($reminder->estate_id),
        // 		'type_template' => $reminder->type_template,
        // 		'name_reminder' => $reminder->name_reminder,
        // 		'subject' => $reminder->subject,
        // 		'content' => $reminder->content,
        // 		'date' => $reminder->date,
        // 		'sent' => $reminder->sent
        // 	);
        // }
        return $remindersArray;
    }

    /**
     * Delete reminder of one estate
     */
    public function deleteRappel($id)
    {
        // Init updated
        $deleted = false;
        // Init the reponse
        $response = array(
            'status' => false, // Reponse status
            'message' => 'Le rappel n\'a pas été supprimé' // Response message
        );

        // Deleting the user
        $deleted = $this->deleteData(app("App\\Models\\EstateReminder"), $id);

        // If updated is true
        if ($deleted) {
            $response = array(
                'status' => true,
                'message' => 'Le rappel a été supprimé'
            );
        }
        // If updated is false
        if (!$deleted) {
            $reponse = array(
                'status' => false,
                'message' => 'Certaines données n\'ont pas pu être supprimées, veuillez réessayer plus tard ...'
            );
        }
        // Return response
        return response($response)->header('Content-Type', 'application/json');
    }

    /**
     * Hide reminder of one estate
     */
    public function hideReminder($id, $estateid)
    {
        // Deleting the user
        $updated = $this->updateData(app("App\\Models\\EstateReminder"), 'hide', 1, $id, $estateid, 'hide'); // Updatate data
        // Return response
        return back();
    }

    /**
     * Get the list of tickets of a agent
     */
    private function listTickets()
    {
        $estate = Estate::where('agent', '=', Auth::user()->id)->get();
        $estateArray = array();
        $tickets = array();
        foreach ($estate as $_estate) {
            $estateArray[] = $this->getSellerToTicket($_estate->seller)['email'];
        }
        $result = array_unique($estateArray);
        foreach ($result as $value) {
            if (!empty($this->objectTicket->get($value))) {
                $tickets[] = $this->objectTicket->get($value);
            }
        }
        return $tickets;
    }

    /**
     * Get the list of tickets of a estate
     */
    private function listTicketsEstate($estateid)
    {
        $estateticket = EstateTicket::where('estate_id', '=', $estateid)->get();
        $estates = array();
        $tickets = array();
        foreach ($estateticket as $value) {
            $tickets[] = $this->objectTicket->find($value->ticket_id);
        }
        return $tickets;
    }

    /**
     * Get the list of tickets solved of a agent
     */
    private function listTicketsSolved()
    {
        $estate = Estate::where('agent', '=', Auth::user()->id)->get();
        $estateArray = array();
        $tickets = array();
        foreach ($estate as $_estate) {
            $estateArray[] = $this->getSellerToTicket($_estate->seller)['email'];
        }
        $result = array_unique($estateArray);
        foreach ($result as $value) {
            if (!empty($this->objectTicket->get($value))) {
                $tickets[] = $this->objectTicket->get($value, 'solved');
            }
        }
        return $tickets;
    }

    /**
     * Get the list of open tickets of a estate
     */
    private function listAllTicketsEstate($estateid)
    {
        $allTickets = array();

        // Get relation of tickets - estate
        $ticketsEstate = $this->listTicketsEstate($estateid);
        foreach ($ticketsEstate as $ticket) {
            $noanswer = false;
            $allTickets[] = $this->objectTicket->find($ticket->id);
            $dataTicket = $this->objectTicket->find(70);
            $comments = $dataTicket->comments; //Includes the initial comment
            if (isset($comments[0]->author->no_reply)) {
                $noanswer = true;
            }
            if ($noanswer) {
                EstateTicket::where('ticket_id', '=', $ticket->id)->update(['no_answer' => 1]);
            }
        }
        return $allTickets;
    }

    /**
     * Get the list to the manager can see all tickets of his agents
     */
    private function listTicketsToManager($managerid)
    {
        $agents = $this->getAgentManager($managerid);
        $estate = array();
        foreach ($agents as $value) {
            $estate[] = Estate::where('agent', '=', $value['agent_id'])->get();
        }
        $estateArray = array();
        $tickets = array();
        foreach ($estate as $_estate) {
            foreach ($_estate as $value) {
                $estateArray[] = $this->getSellerToTicket($value->seller)['email'];
            }
        }
        $result = array_unique($estateArray);
        foreach ($result as $value) {
            if (!empty($this->objectTicket->get($value))) {
                $tickets[] = $this->objectTicket->get($value);
            }
        }
        return $tickets;
    }

    /**
     * Get the list of tickets solved of all the agents of a manager
     */
    private function listTicketsSolvedToManager($managerid)
    {
        $agents = $this->getAgentManager($managerid);
        $estate = array();
        foreach ($agents as $value) {
            $estate[] = Estate::where('agent', '=', $value['agent_id'])->get();
        }
        $estateArray = array();
        $tickets = array();
        foreach ($estate as $_estate) {
            foreach ($_estate as $value) {
                $estateArray[] = $this->getSellerToTicket($value->seller)['email'];
            }
        }

        $result = array_unique($estateArray);
        foreach ($result as $value) {
            if (!empty($this->objectTicket->get($value))) {
                $tickets[] = $this->objectTicket->get($value, 'solved');
            }
        }
        return $tickets;
    }

    /**
     * Get seller
     */
    private function getSellerToTicket($seller_id)
    {
        $seller = Seller::where('id', '=', $seller_id)->get();
        $sellerArray = array();
        foreach ($seller as $_seller) {
            $sellerArray = array(
                'email' => $_seller->email
            );
        }
        return $sellerArray;
    }

    /**
     * Create new ticket
     */
    public function createTicket(Request $request)
    {
        // Save all data of request
        $data = $request->all();
        //Init the response
        $response = array(
            'status' => false,
            'message' => 'Le ticket n\'a pas pu être créé. Réessayez plus tard.'
        );
        try {
            $ticket_id = $this->objectTicket->create(
                [
                    "name" => $data['name_seller'],
                    "email" => $data['email_seller'],
                ],
                $data['subject'],
                $data['body_ticket'],
                []
            );
            // Save the union of the ticket wiht the estate
            EstateTicket::create([
                'estate_id' => $data['estate_id'],
                'ticket_id' => $ticket_id,
                'no_answer' => 0
            ]);
            $response = array(
                'status' => true,
                'message' => 'Le ticket a été créée',
                'data' => $data,
            );
        } catch (\Exception $e) {
            $message = "";
            if ($e->getCode() == 23000) {
                $message = 'Le ticket existe déjà';
            }
            $response = array(
                'status' => false,
                'message' => $e->getMessage(),
                'data' => $data
            );
        }
        //Return response
        return response($response)->header('Content-Type', 'application/json');
    }

    /**
     * Create new ticket
     */
    public function comment(Request $request)
    {
        // Save all data of request
        $data = $request->all();
        //Init the response
        $response = array(
            'status' => false,
            'message' => 'Le commentaire n\'a pas pu être créé. Réessayez plus tard.'
        );
        try {
            $oneTicket = $this->objectTicket->find($data['ticket_id']);
            if (isset($data['resolved'])) {
                $oneTicket->addComment($data['comment'], true);
            } else {
                $oneTicket->addComment($data['comment']);
            }
            $response = array(
                'status' => true,
                'message' => 'Le commentaire a été créée',
                'data' => $data,
            );
        } catch (\Exception $e) {
            $message = "";
            if ($e->getCode() == 23000) {
                $message = 'Le commentaire existe déjà';
            }
            $response = array(
                'status' => false,
                'message' => $message,
                'data' => $data
            );
        }
        //Return response
        return response($response)->header('Content-Type', 'application/json');
    }

    /**
     * Send RDV how ticket
     */
    public function sendRDV(Request $request)
    {
        // Save all data of request
        $data = $request->all();
        //Init the response
        $response = array(
            'status' => false,
            'message' => 'Le invitation n\'a pas pu être créé. Réessayez plus tard.'
        );
        try {
            // Create new ticket and send email
            $ticket_id = $this->objectTicket->create(
                [
                    "name" => $data['seller_name'],
                    "email" => $data['seller_email'],
                ],
                $data['subject'],
                $data['body'],
                []
            );
            // Save the union of the ticket wiht the estate
            EstateTicket::create([
                'estate_id' => $data['estate_id'],
                'ticket_id' => $ticket_id,
                'no_answer' => 0
            ]);
            // Save information in the log
            EstateLog::create([
                'estate_id' => $data['estate_id'],
                'user_id' => Auth::user()->id,
                'old_value' => '',
                'new_value' => 'RDV envoyé à ' . $data['seller_name'],
                'field' => 'sentrdv'
            ]);
            // Updatate data to next reminder
            $updated = $this->updateData(app("App\\Models\\Estate"), 'rdv', 1, $data['estate_id'], $data['estate_id'], 'rdv');
            if ($updated) {
                $response = array(
                    'status' => true,
                    'message' => 'Le invitation a été envoyé',
                    'data' => $data,
                );
            }
        } catch (\Exception $e) {
            $message = "";
            $response = array(
                'status' => false,
                'message' => $message,
                'data' => $data
            );
        }
        //Return response
        return response($response)->header('Content-Type', 'application/json');
    }

    /**
     * Send confirmation of RDV
     */
    public function confirmationRDV(Request $request)
    {
        // Save all data of request
        $data = $request->all();
        $visit = $data['modal_date'];
        $start = $visit . " " . $data['modal_date_confirm_start'];
        $start = date('Y-m-d\TH:i:sO', strtotime($start));
        $end = $visit . " " . $data['modal_date_confirm_end'];
        $end = date('Y-m-d\TH:i:sO', strtotime($end));
        $dateConf = 'Visite le ' . date('Y-m-d', strtotime($data['modal_date_confirm_start'])) . ' de ' . date('H:m', strtotime($data['modal_date_confirm_start'])) . ' à ' . date('H:m', strtotime($data['modal_date_confirm_end']));
        // Save the client
        $client = $this->getClient(Auth::user()->google_token);
        $service = new Google_Service_Calendar($client);
        //Init the response
        $response = array(
            'status' => false,
            'message' => 'Le confirmation n\'a pas pu être créé. Réessayez plus tard.'
        );
        try {

            // Create new ticket and send email
            $ticket_id = $this->objectTicket->create(
                [
                    "name" => $data['seller_name'],
                    "email" => $data['seller_email'],
                ],
                $data['subject'],
                $data['body'],
                []
            );
            // Save the union of the ticket wiht the estate
            EstateTicket::create([
                'estate_id' => $data['estate_id'],
                'ticket_id' => $ticket_id,
                'no_answer' => 0
            ]);
            // Save information in the log
            EstateLog::create([
                'estate_id' => $data['estate_id'],
                'user_id' => Auth::user()->id,
                'old_value' => '',
                'new_value' => 'RDV confirmé pour E-mail',
                'field' => 'confirmrdv'
            ]);
            // Get to show events
            $events = $this->showEvents($data['estate_id']);
            $start_ = explode('+', $start);
            foreach ($events['events'] as $event) {
                $date = explode('+', $event->start->dateTime);

                if ($date[0] !== $start_[0]) {
                    $service->events->delete('primary', $event->id);
                    EstateEvent::where('event_id', '=', $event->id)->delete();
                }
            }
            // Save date of the visit in DB
            $up = Estate::where('id', '=', $data['estate_id'])
                ->update(['visit_date_at' => $dateConf, 'rdv' => 1]);
            $response = array(
                'status' => true,
                'message' => 'Le confirmation a été envoyé',
                'data' => $data,
            );
        } catch (\Exception $e) {
            $message = "";
            $response = array(
                'status' => false,
                'message' => $message,
                'data' => $data
            );
        }
        //Return response
        return response($response)->header('Content-Type', 'application/json');
    }

    /**
     * Send confirmation sms of RDV
     */
    public function confirmationsmsRDV(Request $request)
    {
        // Save all data of request
        $data = $request->all();
        $visit = $data['modal_date'];
        $start = $visit . " " . $data['modal_date_confirm_start'];
        $start = date('Y-m-d\TH:i:sO', strtotime($start));
        $end = $visit . " " . $data['modal_date_confirm_end'];
        $end = date('Y-m-d\TH:i:sO', strtotime($end));
        $dateConf = 'Visite le ' . date('Y-m-d', strtotime($data['modal_date_confirm_start'])) . ' de ' . date('H:m', strtotime($data['modal_date_confirm_start'])) . ' à ' . date('H:m', strtotime($data['modal_date_confirm_end']));

        // Save the client
        $client = $this->getClient(Auth::user()->google_token);
        $service = new Google_Service_Calendar($client);

        //Init the response
        $response = array(
            'status' => false,
            'message' => 'Le confirmation n\'a pas pu être créé. Réessayez plus tard.'
        );
        try {
            $basic = new \Nexmo\Client\Credentials\Basic('20c3b951', '3C9zf1Y4cH2UH5Xu');
            $client = new \Nexmo\Client($basic);
            // Sending SMS
            $message = $client->message()->send([
                'to' => '527731951309',
                'from' => 'Wesold',
                'text' => $data['body']
            ]);
            // Save information in the log
            EstateLog::create([
                'estate_id' => $data['estate_id'],
                'user_id' => Auth::user()->id,
                'old_value' => '',
                'new_value' => 'RDV confirmé pour sms',
                'field' => 'confirmrdv'
            ]);
            // Get to show events
            $events = $this->showEvents($data['estate_id']);
            $start_ = explode('+', $start);
            foreach ($events['events'] as $event) {
                $date = explode('+', $event->start->dateTime);

                if ($date[0] !== $start_[0]) {
                    $service->events->delete('primary', $event->id);
                    EstateEvent::where('event_id', '=', $event->id)->delete();
                }
            }
            // Save date of the visit in DB
            $up = Estate::where('id', '=', $data['estate_id'])
                ->update(['visit_date_at' => $dateConf, 'rdv' => 1]);

            $response = array(
                'status' => true,
                'message' => 'Le confirmation a été envoyé',
                'data' => $data,
            );
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $response = array(
                'status' => false,
                'message' => $message,
                'data' => $data
            );
        }
        //Return response
        return response($response)->header('Content-Type', 'application/json');
    }

    /**
     * Change statut en RDV pris
     */
    public function changeStatus(Request $request)
    {
        // Save all data of request
        $data = $request->all();
        // Init updated
        $updated = false;
        // Init the reponse
        $response = array(
            'status' => false, // Reponse status
            'message' => 'L\'heure n\'a pas été mise à jour ou L\'heure n\'ont pas été modifiée.' // Response message
        );

        $updated = $this->updateData(app("App\\Models\\Estate"), 'category', 14, $data['estate_id'], $data['estate_id'], 'category'); // Update data

        if ($updated) { // If updated is true
            $response = array(
                'status' => true,
                'message' => 'L\'heure a été mise à jour'
            );
        }

        if (!$updated) { // If updated is false
            $reponse = array(
                'status' => false,
                'message' => 'Certaines données n\'ont pas pu être mises à jour ou ont la même valeur, veuillez réessayer plus tard ...'
            );
        }
        //Return response
        return response($response)->header('Content-Type', 'application/json');
    }

    /**
     * Change statut en RDV pris
     */
    public function changeTime(Request $request)
    {
        // Save all data of request
        $data = $request->all();
        // Init updated
        $updated = false;
        // Init the reponse
        $response = array(
            'status' => false, // Reponse status
            'message' => 'L\'heure n\'a pas été mise à jour ou L\'heure n\'ont pas été modifiée.' // Response message
        );

        $updated = $this->updateData(app("App\\Models\\Estate"), 'date_send_reminder', $data['time_to_send_reminder'], $data['estate_id'], $data['estate_id'], 'date_send_reminder'); // Update data

        if ($updated) { // If updated is true
            $response = array(
                'status' => true,
                'message' => 'L\'heure a été mise à jour'
            );
        }

        if (!$updated) { // If updated is false
            $reponse = array(
                'status' => false,
                'message' => 'Certaines données n\'ont pas pu être mises à jour ou ont la même valeur, veuillez réessayer plus tard ...'
            );
        }
        // Return response
        return response($response)->header('Content-Type', 'application/json');
    }

    /**
     * Change statut en RDV pris
     */
    public function validateConfirmation(Request $request)
    {
        // Save all data of request
        $data = $request->all();

        $visit = $data['date_confirm'];
        $start = $visit . " " . $data['date_confirm_start'];
        $start = date('Y-m-d\TH:i:sO', strtotime($start));

        $end = $visit . " " . $data['date_confirm_end'];
        $end = date('Y-m-d\TH:i:sO', strtotime($end));
        $dateConf = 'Visite le ' . date('Y-m-d', strtotime($data['date_confirm_start'])) . ' de ' . date('H:m', strtotime($data['date_confirm_start'])) . ' à ' . date('H:m', strtotime($data['date_confirm_end']));

        // Save the client
        $client = $this->getClient(Auth::user()->google_token);
        $service = new Google_Service_Calendar($client);

        // Get to show events
        $events = $this->showEvents($data['estate_id']);
        $start_ = explode('+', $start);
        foreach ($events['events'] as $event) {
            $date = explode('+', $event->start->dateTime);

            if ($date[0] !== $start_[0]) {
                $service->events->delete('primary', $event->id);
                EstateEvent::where('event_id', '=', $event->id)->delete();
            }
        }
        // Save date of the visit in DB
        $up = Estate::where('id', '=', $data['estate_id'])
            ->update(['visit_date_at' => $dateConf, 'rdv' => 1]);

        $response = array(
            'status' => true,
            'message' => 'La confirmation a été enregistrée'
        );

        // Return response
        return response($response)->header('Content-Type', 'application/json');
    }

    public function saveReminderHalfEight($id, $val)
    {

        try {
            // Save date of the visit in DB
            $up = Estate::where('id', '=', $id)
                ->update(['send_reminder_half_past_eight' => $val]);
            $response = array(
                'status' => true,
                'message' => 'Le rappel a été enregistrée'
            );
        } catch (Exception $e) {
            // Init the reponse
            $response = array(
                'status' => false, // Reponse status
                'message' => 'le rappel n\'a pas pu être enregistré.' // Response message
            );
        }

        // Return response
        return response($response)->header('Content-Type', 'application/json');
    }
}
