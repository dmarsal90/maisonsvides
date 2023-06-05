<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


/**
 * Routes Auth
 */
Auth::routes();

/**
 * Routes Auth
 */
Route::get('/', 'Auth\LoginController@login')->name('login');
Route::post('/', 'Auth\LoginController@login')->name('login');
Route::get('/login', 'Auth\LoginController@login')->name('login');
Route::post('/login', 'Auth\LoginController@login')->name('login');
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

/**
 * Routes App
 */

// Dashboard
Route::get('/dashboard', 'EstateController@index')->name('dashboard')->middleware('auth');
Route::get('/home', 'EstateController@index')->name('home')->middleware('auth');

// Listing estates
Route::get('/estates', 'EstateController@listing')->name('estates')->middleware('auth');
Route::get('/estates/{category}', 'EstateController@listing')->name('estates')->middleware('auth');
Route::get('/estates/{category}/{subCategory}', 'EstateController@listing')->name('estates')->middleware('auth');

// View estate
Route::get('/estate', 'EstateController@view')->name('estate')->middleware('auth');
Route::get('/estate/{id}', 'EstateController@view')->name('estate')->middleware('auth');

// Search estate
Route::get('/search', 'EstateController@search')->name('search')->middleware('auth');

// Calendar
Route::get('calendar', 'EstateController@calendar')->name('calendar')->middleware('auth');
Route::get('connect', 'EstateController@connect')->name('connect')->middleware('auth');
Route::get('events', 'EstateController@getEvents')->name('events')->middleware('auth');
Route::post('setrdv', 'EstateController@setRdv')->name('setrdv')->middleware('auth');
Route::post('createevent', 'EstateController@createEvent')->name('createevent')->middleware('auth');

// Module visit
Route::get('visits', 'EstateController@visits')->name('visits')->middleware('auth');
Route::get('visit', 'EstateController@visit')->name('visit')->middleware('auth');
Route::get('visit/{id}', 'EstateController@visit')->name('visit')->middleware('auth');
Route::get('estatevisited/{id}/{option}', 'EstateController@estateVisited')->name('estatevisited')->middleware('auth');

// Settings
Route::get('settings', 'AdminController@index')->name('settings')->middleware('auth');
Route::post('updatereminders', 'AdminController@updateReminders')->name('updatereminders')->middleware('auth');
Route::post('newrealestate', 'AdminController@newRealestate')->name('newrealestate')->middleware('auth');
Route::post('newtemplatemail', 'AdminController@newTemplateEmail')->name('newtemplatemail')->middleware('auth');
Route::post('newtemplatecondition', 'AdminController@newTemplateCondition')->name('newtemplatecondition')->middleware('auth');
Route::post('newtemplatetextoffer', 'AdminController@newTemplateTexteOffer')->name('newtemplatetextoffer')->middleware('auth');
Route::post('newtemplatesubjectoffer', 'AdminController@newTemplateSubjectOffer')->name('newtemplatesubjectoffer')->middleware('auth');
Route::post('edittemplatemail', 'AdminController@editTemplateEmail')->name('edittemplatemail')->middleware('auth');
Route::post('newtemplatesms', 'AdminController@newTemplateSMS')->name('newtemplatesms')->middleware('auth');
Route::post('newtemplatetask', 'AdminController@newTemplateTask')->name('newtemplatetask')->middleware('auth');
Route::post('edittemplatesms', 'AdminController@editTemplateSMS')->name('edittemplatesms')->middleware('auth');
Route::get('deletetemplate/{id?}/{nametemplate?}', 'AdminController@deleteTemplate')->name('deletetemplate')->middleware('auth');
Route::get('deletesite/{id}', 'AdminController@deleteRealestate')->name('deletesite')->middleware('auth');
Route::post('sendemailtemplatetest', 'AdminController@sendEmailTemplateTest')->name('sendemailtemplatetest')->middleware('auth');
Route::post('sendsmstemplatetest', 'AdminController@sendSMSTemplateTest')->name('sendsmstemplatetest')->middleware('auth');
Route::post('saveremindera', 'AdminController@saveReminderA')->name('saveremindera')->middleware('auth');
Route::get('deletereminders/{id}', 'AdminController@deleteReminder')->name('deletereminders')->middleware('auth');
Route::post('savemenu', 'AdminController@saveMenu')->name('savemenu')->middleware('auth');
Route::post('edittemplates/{id}', 'AdminController@editTemplates')->name('edittemplates')->middleware('auth');

// Users
Route::get('getusers', 'AdminController@getUsers')->name('getusers')->middleware('auth');
Route::post('newuser', 'AdminController@newUser')->name('newuser')->middleware('auth');
Route::post('edituser', 'AdminController@updateUser')->name('edituser')->middleware('auth');
Route::get('deleteuser/{id}', 'AdminController@deleteUser')->name('deleteuser')->middleware('auth');


//Notaries
Route::post('newnotary', 'AdminController@newNotary')->name('newnotary')->middleware('auth');
Route::post('editnotary', 'AdminController@updateNotary')->name('editnotary')->middleware('auth');
Route::get('deletenotary/{id}', 'AdminController@deleteNotary')->name('deletenotary')->middleware('auth');

//Categories
Route::get('getcategories', 'AdminController@getCategories')->name('getcategories')->middleware('auth');
Route::post('newcategory', 'AdminController@newCategory')->name('newcategory')->middleware('auth');
Route::post('editcategory', 'AdminController@updateCategory')->name('editcategory')->middleware('auth');
Route::get('deletecategory/{id}', 'AdminController@deleteCategory')->name('deletecategory')->middleware('auth');

//Comments
Route::post('newcomment', 'EstateController@newComment')->name('newcomment')->middleware('auth');

//Resolutions
Route::post('newresolution', 'EstateController@newResolution')->name('newresolution')->middleware('auth');

//Informations & commentaires of the estate
Route::post('editinformations', 'EstateController@editInformations')->name('editinformations')->middleware('auth');

//Estate
Route::post('editdetails', 'EstateController@editDetails')->name('editdetails')->middleware('auth');
Route::post('editdetailsaadapte', 'EstateController@editDetailsAdapte')->name('editdetailsaadapte')->middleware('auth');
Route::post('newadvertisement', 'EstateController@newAdvertisement')->name('newadvertisement')->middleware('auth');
Route::post('updatereminderse', 'EstateController@updateReminders')->name('updatereminderse')->middleware('auth');
Route::post('uploadphoto/{disk}', 'EstateController@uploadPhoto')->name('uploadphoto')->middleware('auth');
Route::get('deletemedia/{id}', 'EstateController@deleteMedia')->name('deletemedia')->middleware('auth');
Route::post('sendsms', 'EstateController@sendSMS')->name('sendsms')->middleware('auth');
Route::post('sendemail', 'EstateController@sendEmail')->name('sendemail')->middleware('auth');
Route::post('sendemailoffer', 'EstateController@sendEmailOffer')->name('sendemailoffer')->middleware('auth');
Route::post('sendoffer', 'EstateController@sendOffer')->name('sendoffer')->middleware('auth');
Route::post('savepdfoffer', 'EstateController@savePDFoffer')->name('savepdfoffer')->middleware('auth');
Route::post('savereminder', 'EstateController@saveReminder')->name('savereminder')->middleware('auth');
Route::get('deleterappel/{id}', 'EstateController@deleteRappel')->name('deleterappel')->middleware('auth');
Route::get('hidereminder/{id}/{estateid}', 'EstateController@hideReminder')->name('hidereminder')->middleware('auth');
Route::post('editreminder', 'EstateController@editReminder')->name('editreminder')->middleware('auth');
Route::post('editremindertask', 'EstateController@editReminderTask')->name('editremindertask')->middleware('auth');
Route::get('savereminderhalfeight/{id}/{val}', 'EstateController@saveReminderHalfEight')->name('savereminderhalfeight')->middleware('auth');
Route::post('sendrdv', 'EstateController@sendRDV')->name('sendrdv')->middleware('auth');
Route::post('confirmationrdv', 'EstateController@confirmationRDV')->name('confirmationrdv')->middleware('auth');
Route::post('confirmationsmsrdv', 'EstateController@confirmationsmsRDV')->name('confirmationsmsrdv')->middleware('auth');
Route::post('newcommentrdv', 'EstateController@newCommentRDV')->name('newcommentrdv')->middleware('auth');
Route::post('changestatus', 'EstateController@changeStatus')->name('changestatus')->middleware('auth');
Route::post('changetime', 'EstateController@changeTime')->name('changetime')->middleware('auth');
Route::post('validateconfirmation', 'EstateController@validateConfirmation')->name('validateconfirmation')->middleware('auth');
Route::post('createticket_e', 'EstateController@createTicket')->name('createticket_e')->middleware('auth');
Route::post('addfiles', 'EstateController@addFiles')->name('addfiles')->middleware('auth');



//Offres
Route::post('updateoffer', 'EstateController@updateOffer')->name('updateoffer')->middleware('auth');

//Estate remarks
Route::post('updateremark', 'EstateController@updateRemark')->name('updateremark')->middleware('auth');

// Page thanks
Route::get('thanks/{user_id}/{ids}', 'EstateController@thanks')->name('thanks');
Route::get('confirm/{estate_id}', 'EstateController@confirm')->name('confirm');

// Export CSV
Route::post('exportcsv', 'EstateController@exportCSV')->name('exportcsv');

// Tickets
Route::get('viewTickets', 'TicketsController@viewTickets')->name('viewTickets');
Route::get('viewoneticket/{id}', 'TicketsController@viewOneTicket')->name('viewoneticket');
Route::get('viewoneticketdash/{id}', 'TicketsController@viewOneTicketDash')->name('viewoneticketdash');
Route::get('viewoneticketdetails/{id}/{estateid}', 'TicketsController@viewOneTicketDetails')->name('viewoneticketdetails');
//Route::post('createticket', 'TicketsController@createTicket')->name('createticket');
Route::post('createticket', 'TicketNewController@createTicket')->name('createticket');
Route::post('comment', 'TicketsController@comment')->name('comment');

// Hollyday date
Route::post('savedatespecial', 'AdminController@saveDateSpecial')->name('savedatespecial');

Route::post('/sendconfirmationemail', [\App\Http\Controllers\EmailController::class, 'sendConfirmationEmail'])->name('sendconfirmationemail')->middleware('auth');

Route::post('/send-sms', [\App\Http\Controllers\SmsController::class, 'sendSmsReminder'])->name('sendsmsreminder')->middleware('auth');
