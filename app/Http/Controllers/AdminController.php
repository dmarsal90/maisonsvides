<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

// Include Models
use App\Models\User;
use App\Models\Date;
use App\Models\Agent;
use App\Models\Seller;
use App\Models\Notary;
use App\Models\Estate;
use App\Models\Setting;
use App\Models\Template;
use App\Models\UserType;
use App\Models\Category;
use App\Models\RealEstate;
use App\Models\EstateDetail;
use App\Models\TemplateReminder;

use App\Mail\MailableEmail;
use App\Mail\EmailT;
use Illuminate\Support\Facades\Mail;


class AdminController extends Controller {

	private $updated;
	private $deleted;

	/**
	 * Set variables
	 */
	public function __construct() {
		// To know is value was updated
		$this->updated = false;
		// To know is value was deleted
		$this->deleted = false;
	}

	/**
	 * Settings
	 */
	public function index() {
		// Get users
		$users = $this->getUsers();
		// Get user types
		$userTypes = UserType::get();
        $userTypes = $userTypes->toArray();
		// Get categories
		$categories = $this->getCategories();
        $aCategories = array();
		foreach ($categories as $category) {
			$aCategories[$category['id']] = $category;
		}

		// Get templates
		$templates = $this->getTemplates();
        //dd($templates);die;
		// Get all RealEstates
		$realestates = $this->getRealestate();
		// Get notaries
		$notaries = $this->getNotaires();
		// Get reminders
        $reminders = TemplateReminder::all();
        $reminders = $reminders->toArray();

        //dd($reminders);die;
		//$reminders = $this->getReminders();

		// Get agents without Gestionnaire de zone
		$agentsW = $this->getAgents();
        //dd(Auth::user());die;
		$teamOfManager = array();
		if (Auth::user()->type == 2 || Auth::user()->type == 1) { // If the user logged is secretary
			$teamOfManager = $this->getAgentsOfAManager(Auth::user()->id);
			$teamOfManager[] = Auth::user()->id;
           // dd($teamOfManager);die;
		}
		$teamOfAgent = array();
		if (Auth::user()->type == 3) { // If the user logged is secretary
			$managerid = $this->getManagerOfAgent(Auth::user()->id);
			$teamOfAgent = $this->getAgentsOfAManager($managerid);
			$teamOfAgent[] = $managerid;
		}
		// Get hollydays dates
		$dates = $this->getDateSpecial();
		// Get relation with agent and manager
		$agentManager = $this->getAgentManager();
		// Get type of menu
		$user = User::where('id', '=', Auth::user()->id)->get();
		$typemenu = $user[0]->menu;
		// Return view Settings
		return view('settings.settings', ['users' => $users, 'userTypes' => $userTypes, 'categories' => $categories, 'realestates' => $realestates, 'aCategories' => $aCategories, 'templates' => $templates, 'notaries' => $notaries, 'reminders' => $reminders, 'agentsW' => $agentsW, 'agentManager' => $agentManager, 'dates' => $dates, 'typemenu' => $typemenu, 'teamOfManager' => $teamOfManager, 'teamOfAgent' => $teamOfAgent]);
	}

	/**
	 * Update reminders
	 */
	public function updateReminders(Request $request) {
		// Get data of the request
		$data = $request->all();
		// Init the reponse
		$response = array(
			'status' => false,
			'message' => 'Les rappels n\'ont pas pu être mis à jour, veuillez réessayer plus tard.'
		);
		// For each reminder
		foreach($data['reminder_ids'] as $id) {
			$reminders = serialize($data['reminders_'.$id]); // Get array of reminders by category
			$reminder = Setting::where('id', '=', $id) // Where ID
					->update(['value' => $reminders]); // Update the value of reminders
		}
		$response = array(
			'status' => true,
			'message' => 'Les rappels ont été mis à jour.',
			'data' => $data,
		);
		// Return response
		return response($response)->header('Content-Type', 'application/json');
	}

	/**
	 * Update Data generic
	 * @var model is model to update
	 * @var field is the field to update NOTE : the name of field should be exactly as database
	 * @var value is the new value to put
	 * @var id is the id for register to update
	 */
	private function updateData($model, $field, $value, $id) {
		// Get all register
		$register = $model::find($id);
		// Verify if the new value is different to current value
		if($register->$field != $value) {
			try {
				// Update value where
				$model::where('id', '=', $id)
					->update([$field => $value]);
				// Updated true
				$this->updated = true;
			}
			catch(\Exception $e) {
				// // Updated false
				// var_dump($e->getMessage());
				$this->updated = false;
			}
		}
		// Return updated
		return $this->updated;
	}

	/**
	 * Update Password
	 * @var model is model to update
	 * @var new is the new password
	 * @var old is the current password
	 * @var id is the id for register to update the password
	 * @var field is the field to password default is password NOTE : this parameter is optional in case of name differente put the correct name
	 */
	private function updatePassword($model, $new, $new_confirm, $id, $field = "password") {
		// Init the reponse
		$response = array(
			'status' => false, // Reponse status
			'message' => 'Le mot de passe n\'a pas été mis à jour.' // Response message
		);
		// Get user to update
		$user = $model::find($id);
		$currentPassword = $user->password;
		// Check if the old password entered matches the one registered
		if($new == $new_confirm) {
			// Check if the new password is not the current password
			if(!\Hash::check($new, $currentPassword)) {
				// Update password
				$model::where('id', '=', $user->id)
						->update(['password' => bcrypt($new)]);
				$response = array(
					'status' => true, // Reponse status
					'message' => 'Le mot de passe a été mis à jour.' // Response message
				);
			}
			else {
				$response = array(
					'status' => false, // Reponse status
					'message' => 'Le nouveau mot de passe ne peut pas être l\'ancien mot de passe !' // Response message
				);
			}
		}
		else {
			$response = array(
				'status' => false, // Reponse status
				'message' => 'L\'ancien mot de passe ne correspond pas' // Response message
			);
		}
		return $response;
	}

	/**
	 * Delete Data generic
	 * @var model is model to update
	 * @var id is the id for register to delete
	 */
	private function deleteData($model, $id) {
		try {
			// Delete value where
			$var = $model::where('id', '=', $id)
				->delete();
			// Deleted true
			$this->deleted = true;
		}
		catch(\Exception $e) {
			// Deleted false
			$this->deleted = false;
		}
		// Return deleted
		return $this->deleted;
	}

	/**
	 * Get users
	 */
	public function getUsers() {
		$users = User::select(
			"users.id",
			"users.name",
			"users.email",
			"users.login",
			'users.type',
			"user_types.name as type_name",
			"google_email"
		)->join("user_types", "user_types.id", "=", "users.type")
		->where('users.id', "!=", 1)
		->orderBy("users.id", "asc")
		->get();
		$usersArray = array();
		foreach ($users as $user) {
            $nameParts = explode(" ", $user->name);
            $name = $nameParts[0];
			$usersArray[] = array(
				'id' => $user->id,
				'username' => $user->login,
				'name' => $name,
				'firstname' => explode(" ", $user->name)[0],
				'type' => $user->type,
				'type_name' => $user->type_name,
				'email' => $user->email,
				'google_email' => $user->google_email,
			);
		}
		return $usersArray;
	}

	/**
	 * Get estates relation with agent and manager
	 */
	private function getAgentManager() {
		$agents = Agent::get();
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
	 * Create user
	 */
	public function newUser(Request $request) {
		// Get data of the request
		$data = $request->all();
		// Init the reponse
		$response = array(
			'status' => false,
			'message' => 'L\'utilisateur n\'a pas pu être créé, veuillez réessayer plus tard.'
		);
		try {
			$user = User::create([
				'name' => $data['firstname']." ".$data['name'],
				'email' => $data['email'],
				'login' => $data['username'],
				'password' => bcrypt($data['password']),
				'type' => $data['type'],
				'google_email' => $data['google_email'],
				'active' => 1,
			]);
			if (isset($data['manager_id']) && $data['manager_id'] != 0) {
				if ($data['type'] == 3) {
					$join = Agent::create([
						'agent_id' => $user->id,
						'manager_id' => $data['manager_id']
					]);
				}
			}
			if (isset($data['agent_id']) && $data['agent_id'] != 0) {
				if ($data['type'] == 2) {
					foreach ($data['agent_id'] as $agent) {
						$join = Agent::create([
							'agent_id' => $agent,
							'manager_id' => $user->id
						]);
					}
				}
			}
			$response = array(
					'status' => true,
					'message' => 'L\'utilisateur a été créé.',
					'data' => $data,
			);
		}
		catch(\Exception $e) {
			$message = "";
			if($e->getCode() == 23000 || $e->getCode() == "23000") {
				$message = 'L\'utilisateur existe déjà';
			}
			$response = array(
				'status' => false,
				'message' => $message,
				'data' => $data,
			);
		}
		// Return response
		return response($response)->header('Content-Type', 'application/json');
	}

	/**
	 * Get agents without Gestionnaire de zone
	 */
	public function getAgents(){
        // Obtener todos los agentes
        $agents = Agent::all();

        // Obtener los IDs de los agentes
        $agentIds = $agents->pluck('agent_id');

        // Obtener los usuarios que son agentes sin un manager asignado
        $agentsWithoutManager = User::where('type', 3)
                                     ->whereNotIn('id', $agentIds)
                                     ->get();

        // Obtener los IDs de los usuarios que son agentes sin un manager asignado
        $agentIdsWithoutManager = $agentsWithoutManager->pluck('id');

        // Verificar si hay usuarios que sean agentes sin un manager asignado
        if ($agentIdsWithoutManager->isNotEmpty()) {
            // Retornar los IDs de los usuarios que son agentes sin un manager asignado
            return $agentIdsWithoutManager->toArray();
        } else {
            // Retornar un mensaje indicando que no hay agentes sin un manager asignado
            return "Il n'y a pas d'agents";
        }
    }

	/**
	 * Get agents without Gestionnaire de zone
	 */
	public function getAgentsOfAManager($idManager){
		$agents = Agent::where("manager_id", "=", $idManager)->get();
		$arrayAgents = array();
		foreach ($agents as $agent) {
			$arrayAgents[] = $agent->agent_id;
		}
		return $arrayAgents;
	}

	/**
	 * Get agents without Gestionnaire de zone
	 */
	private function getManagerOfAgent($idAgent){
		$manager = Agent::where("agent_id", "=", $idAgent)->get();
		$r = 0;
		if (isset($manager[0]->manager_id)) {
			$r = $manager[0]->manager_id;
		}
		return $r;
	}

	/**
	 * Update user
	 */
	public function updateUser(Request $request) {
		// Get all data of request
		$data = $request->all();
		// Initialize updatePassword
		$updatePassword = false;
		// Init updated
		$updated = false;
		// Init the reponse
		$response = array(
			'status' => false, // Reponse status
			'message' => 'L\'utilisateur n\'a pas été mis à jour ou les informations n\'ont pas été modifiées.' // Response message
		);
		if ($data['type'] == 2) {
			// Delete all his agents
			$var = Agent::where('manager_id', '=', $data['id'])
				->delete();
			// Add all his agents
			foreach ($data['agent_id'] as $value) {
				Agent::create([
					"agent_id" => $value,
					"manager_id" => $data['id']
				]);
			}
			// Get keys of data
			foreach($data as $key => $dat) { // Foreach key of data
				if($key !== "_token" && $key !== "id" && $key !== "new_password_confirm" && $key !== 'new_password' && $key !== 'firstname') { // Keys to prevent update
					if($key === "name") { // If name
						$updated = $this->updateData(app("App\\Models\\User"), $key, $data['firstname']." ".$dat, $data['id']); // Concat firstname and name
					}
					else {
						$updated = $this->updateData(app("App\\Models\\User"), $key, $dat, $data['id']); // Updatate data
					}
				}
			}
			$updated = true;
			$response = array(
					'status' => true,
					'message' => 'L\'utilisateur a été mis à jour'
				);
			if($data['new_password'] && $data['new_password_confirm']) {
				$response = $this->updatePassword(app("App\\Models\\User"), $data['new_password'], $data['new_password_confirm'], $data['id']);
				if($response['status'] && $updated) {
					$response = array(
						'status' => true,
						'message' => 'L\'utilisateur a été mis à jour'
					);
				}
			}
		} else {
			// Get keys of data
			foreach($data as $key => $dat) { // Foreach key of data
				if($key !== "_token" && $key !== "id" && $key !== "new_password_confirm" && $key !== 'new_password' && $key !== 'firstname') { // Keys to prevent update
					if($key === "name") { // If name
						$updated = $this->updateData(app("App\\Models\\User"), $key, $data['firstname']." ".$dat, $data['id']); // Concat firstname and name
					}
					else {
						$updated = $this->updateData(app("App\\Models\\User"), $key, $dat, $data['id']); // Updatate data
					}
				}
			}
			if($updated) { // If updated is true
				$response = array(
					'status' => true,
					'message' => 'L\'utilisateur a été mis à jour'
				);
			}
			if(!$updated) { // If updated is false
				$reponse = array(
					'status' => false,
					'message' => 'Certaines données n\'ont pas pu être mises à jour ou ont la même valeur, veuillez réessayer plus tard ...'
				);
			}
			if($data['new_password'] && $data['new_password_confirm']) {
				$response = $this->updatePassword(app("App\\Models\\User"), $data['new_password'], $data['new_password_confirm'], $data['id']);
				if($response['status'] && $updated) {
					$response = array(
						'status' => true,
						'message' => 'L\'utilisateur a été mis à jour'
					);
				}
			}
		}
		// Return response
		return response($response)->header('Content-Type', 'application/json');
	}

	/**
	 * Delete user
	 */
	public function deleteUser($id) {
		// Init updated
		$deleted = false;
		// Init the reponse
		$response = array(
			'status' => false, // Reponse status
			'message' => 'L\'utilisateur n\'a pas été supprimé.' // Response message
		);
		// Deleting the user
		$deleted = $this->deleteData(app("App\\Models\\User"), $id);
		// If updated is true
		if($deleted) {
			$response = array(
				'status' => true,
				'message' => 'L\'utilisateur a été supprimé'
			);
		}
		// If updated is false
		if(!$deleted) {
			$reponse = array(
				'status' => false,
				'message' => 'Certaines données n\'ont pas pu être supprimées, veuillez réessayer plus tard ...'
			);
		}
		// Return response
		return response($response)->header('Content-Type', 'application/json');
	}

	/**
	 * Create notary
	 */
	public function newNotary(Request $request) {
		// Get data of the request
		$data = $request->all();
		// Init the reponse
		$response = array(
			'status' => false,
			'message' => 'L\'notaire n\'a pas pu être créé, veuillez réessayer plus tard.'
		);
		try {
			$user = Notary::create([
				'title' => $data['title'],
				'name' => $data['firstname'],
				'lastname' => $data['name'],
				'address' => $data['address'],
				'phone' => $data['phone'],
				'email' => $data['email'],
				'key' => $data['key']
			]);
			$response = array(
				'status' => true,
				'message' => 'L\'notaire a été créé.',
				'data' => $data,
			);
		}
		catch(\Exception $e) {
			$message = "";
			if($e->getCode() == 23000) {
				$message = 'L\'notaire existe déjà';
			}
			$response = array(
				'status' => false,
				'message' => $message,
				'data' => $data,
			);
		}
		// Return response
		return response($response)->header('Content-Type', 'application/json');
	}

	/**
	 * Create notary
	 */
	public function updateNotary(Request $request) {
		// Get all data of request
		$data = $request->all();
		// Init updated
		$updated = false;
		// Init the reponse
		$response = array(
			'status' => false, // Reponse status
			'message' => 'Le notaire n\'a pas été mise à jour ou les informations n\'ont pas été modifiées.' // Response message
		);

		// Get keys of data
		foreach($data as $key => $dat) { // Foreach key of data
			if ($key !== 'notary_id' && $key !== '_token') {
				$updated = $this->updateData(app("App\\Models\\Notary"), $key, $dat, $data['notary_id']); // Updatate data
			}
		}
		if($updated) { // If updated is true
			$response = array(
				'status' => true,
				'message' => 'Le notaire été mise à jour'
			);
		}

		if(!$updated) { // If updated is false
			$reponse = array(
				'status' => false,
				'message' => 'Certaines données n\'ont pas pu être mises à jour ou ont la même valeur, veuillez réessayer plus tard ...'
			);
		}

		// Return response
		return response($response)->header('Content-Type', 'application/json');
	}

	/**
	 * Get notaires
	 */
	public function getNotaires() {
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

	/**
	 * Delete notary
	 */
	public function deleteNotary($id) {
		// Init updated
		$deleted = false;
		// Init the reponse
		$response = array(
			'status' => false, // Reponse status
			'message' => 'L\'notaire n\'a pas été supprimé.' // Response message
		);
		// Deleting the user
		$deleted = $this->deleteData(app("App\\Models\\Notary"), $id);
		// If updated is true
		if($deleted) {
			$response = array(
				'status' => true,
				'message' => 'L\'notaire a été supprimé'
			);
		}
		// If updated is false
		if(!$deleted) {
			$reponse = array(
				'status' => false,
				'message' => 'Certaines données n\'ont pas pu être supprimées, veuillez réessayer plus tard ...'
			);
		}
		// Return response
		return response($response)->header('Content-Type', 'application/json');
	}

	/**
	 * Get categories
	 */
	public function getCategories() {
		$categories = Category::select(
			"categories.id",
			"categories.name",
			"categories.slug",
			"categories.parent"
		)->get();
		$categoriesArray = array();
		foreach ($categories as $category) {
			$categoriesArray[] = array(
				'id' => $category->id,
				'name' => $category->name,
				'slug' => $category->slug,
				'parent' => $category->parent,
			);
		}
		return $categoriesArray;
	}

	/**
	 * Create category
	 */
	public function newCategory(Request $request){
		//Get data of the request
		$data = $request->all();
		//Init the response
		$response = array(
			'status' => false,
			'message' => 'La catégorie n\'a pas pu être créée. Réessayez plus tard.'
		);

		try {
			$category = Category::create([
				'name' => $data['name'],
				'slug' => $data['slug'],
				'parent' => $data['parent']
			]);
			$response = array(
				'status' => true,
				'message' => 'La catégorie a été créée',
				'data' => $data,
			);
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
				if($register->$field != $value) {
					try {
						// Update value where
						$model::where('id', '=', $id)
							->update([$field => $value]);
						// Updated true
						$this->updated = true;
					}
					catch(\Exception $e) {
						// Updated false
						$this->updated = false;
					}
				}
			}
		} catch (\Exception $e) {
			$message = "";
			if ($e->getCode() == 23000) {
				$message = 'La catégorie existe déjà';
			}
			$response = array(
				'status' => false,
				'message' => $message,
				'data' => $data
			);
		}
		$this->countCategories();

		//Return response
		return response($response)->header('Content-Type', 'application/json');
	}

	/**
	 * Count categories
	 */
	private function countCategories() {
		// Code to save if a category is parent
		$categories = $this->getCategories(); // Get categories
		$parent = array(); // Array to save all parents of the categories
		foreach ($categories as $category) {
			$parent[$category['parent']] = $category['parent'];// Save only the parent of the category
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
	 * Update category
	 */
	public function updateCategory(Request $request) {
		// Get all data of request
		$data = $request->all();
		// Init updated
		$updated = false;
		// Init the reponse
		$response = array(
			'status' => false, // Reponse status
			'message' => 'La catégorie n\'a pas été mise à jour ou les informations n\'ont pas été modifiées.' // Response message
		);

		// Get keys of data
		foreach($data as $key => $dat) { // Foreach key of data
			$updated = $this->updateData(app("App\\Models\\Category"), $key, $dat, $data['idCategory']); // Updatate data
		}
		// Code to save if a category is parent
		$categories = $this->getCategories(); // Get categories
		$parent = array(); // Array to save all parents of the categories
		foreach ($categories as $category) {
			$parent[$category['parent']] = $category['parent'];// Save only the parent of the category
		}
		Category::query()->update(['has_child' => 0]);
		foreach ($parent as $key => $value) {
			if ($value != 0 || $value != null) { // If parent is differnt of 0 or if is null
				// Update value where
					Category::where('id', '=', $value)->update(['has_child' => 1]);
			}
		}
		if($updated) { // If updated is true
			$response = array(
				'status' => true,
				'message' => 'La catégorie a été mise à jour'
			);
		}

		if(!$updated) { // If updated is false
			$reponse = array(
				'status' => false,
				'message' => 'Certaines données n\'ont pas pu être mises à jour ou ont la même valeur, veuillez réessayer plus tard ...'
			);
		}

		// Return response
		return response($response)->header('Content-Type', 'application/json');
	}

	/**
	 * Delete category
	 */
	public function deleteCategory($id) {
		// Init updated
		$deleted = false;
		// Init the reponse
		$response = array(
			'status' => false, // Reponse status
			'message' => 'La catégorie n\'a pas été supprimé' // Response message
		);
		// Deleting the user
		$deleted = $this->deleteData(app("App\\Models\\Category"), $id);
		// If updated is true
		if($deleted) {
			$response = array(
				'status' => true,
				'message' => 'La catégorie a été supprimé'
			);
		}
		// If updated is false
		if(!$deleted) {
			$reponse = array(
				'status' => false,
				'message' => 'Certaines données n\'ont pas pu être supprimées, veuillez réessayer plus tard ...'
			);
		}
		// Return response
		return response($response)->header('Content-Type', 'application/json');
	}

	/**
	 * Create new realestate
	 */
	public function newRealestate(Request $request) {
		//Get data of the request
		$data = $request->all();
		//Init the response
		$response = array(
			'status' => false,
			'message' => 'Le nouveau site immobilier n\'a pas pu être créée. Réessayez plus tard.'
		);

		try {
			RealEstate::create([
				'name' => $data['nameimmobilier']
			]);
			$response = array(
				'status' => true,
				'message' => 'Le nouveau site immobilier a été créée',
				'data' => $data,
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
	private function getRealestate(){
		$sities = RealEstate::all();
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
	 * Delete site immobilier
	 */
	public function deleteRealestate($id) {
		// Init updated
		$deleted = false;
		// Init the reponse
		$response = array(
			'status' => false, // Reponse status
			'message' => 'Le site n\'a pas été supprimé' // Response message
		);
		// Deleting the user
		$deleted = $this->deleteData(app("App\\Models\\RealEstate"), $id);
		// If updated is true
		if($deleted) {
			$response = array(
				'status' => true,
				'message' => 'Le site a été supprimé'
			);
		}
		// If updated is false
		if(!$deleted) {
			$reponse = array(
				'status' => false,
				'message' => 'Certaines données n\'ont pas pu être supprimées, veuillez réessayer plus tard ...'
			);
		}
		// Return response
		return response($response)->header('Content-Type', 'application/json');
	}

	/**
	 * Create new template to the email
	 */
	public function newTemplateEmail(Request $request) {
		// Init response
		$response = array('status' => false);
		//Get data of the request
		$data = $request->all();

		\File::put(public_path().'/templates/'.$data['file'].'.html', $data['templateBody']);
		$file = $data['file'].'.html';
		Template::create([
			'name' => $data['templateName'],
			'subject' => $data['templateSubject'],
			'file' => $file,
			'type' => $data['type'],
			'user' => Auth::user()->id
		]);
		$response = array(
			'status' => true, // Status true
			'message' => 'Le modèle a été créé'
		);
		//Return response
		return response($response)->header('Content-Type', 'application/json');
	}

	/**
	 * Create new template to the email
	 */
	public function newTemplateSubjectOffer(Request $request) {
		//Get data of the request
		$data = $request->all();
		Template::where('id', '=', $data['id_template'])
		->update(["file" => $data['subject_text']]);
	}

	/**
	 * Get templates
	 */
	private function getTemplates(){
		$templates = Template::get();
		$templatesArray = array();
		foreach ($templates as $template) {
			$templatesArray[] = array(
				'id' => $template->id,
				'name' => $template->name,
				'subject' => $template->subject,
				'file' => $template->file,
				'type' => $template->type,
				'user' => $this->getUser($template->user),
				'user_id' => $template->user
			);
		}
		return $templatesArray;
	}

	/**
	 * Get templates
	 */
	private function getUser($idUser) {
		$user = User::where('id', '=', $idUser)->get();
		return $user[0]->name;
	}

	/**
	 * Update template
	 */
	public function editTemplateEmail(Request $request) {
		// Init response
		$response = array('status' => false);
		//Get data of the request
		$data = $request->all();
		if (isset($data['templateName'])) {
			\File::put(public_path().'/templates/'.$data['file'].'.html', $data['templateBody']);
			$file = $data['file'].'.html';
			if (isset($data['templateName'])) {
				$updated = $this->updateData(app("App\\Models\\Template"), 'name', $data['templateName'], $data['id']); // Updatate data
			}
			if (isset($data['file'])) {
				$updated = $this->updateData(app("App\\Models\\Template"), 'file', $file, $data['id']); // Updatate data
			}
		}
		$response = array(
			'status' => true, // Status true
			'message' => 'Le modèle a été créé'
		);
		//Return response
		return response($response)->header('Content-Type', 'application/json');
	}

	/**
	 * Create new template to the email
	 */
	public function newTemplateSMS(Request $request) {
		//Get data of the request
		$data = $request->all();
		// Init response
		$response = array('status' => false);

		\File::put(public_path().'/templates/'.$data['file'].'.txt', $data['form_phone_message']);
		$file = $data['file'].'.txt';
		Template::create([
			'name' => $data['templateName'],
			'subject' => '',
			'file' => $file,
			'type' => $data['type'],
			'user' => Auth::user()->id
		]);

		$response = array(
			'status' => true, // Status true
			'message' => 'Le modèle a été créé'
		);
		//Return response
		return response($response)->header('Content-Type', 'application/json');
	}

	/**
	 * Create new template to the task
	 */
	public function newTemplateTask(Request $request) {
		// Init response
		$response = array('status' => false);
		//Get data of the request
		$data = $request->all();

		\File::put(public_path().'/templates/'.$data['file'].'.html', $data['form_phone_message']);
		$file = $data['file'].'.html';
		Template::create([
			'name' => $data['templateName'],
			'subject' => $data['estatus_task'],
			'file' => $file,
			'type' => $data['type'],
			'user' => Auth::user()->id
		]);
		$response = array(
			'status' => true, // Status true
			'message' => 'Le modèle a été créé'
		);
		//Return response
		return response($response)->header('Content-Type', 'application/json');
	}

	/**
	 * Update template
	 */
	public function editTemplates(Request $request, $id) {
		//Get data of the request
		$data = $request->all();
		// Init response
		$response = array(
			'status' => false,
			'message' => "Les informations n'ont pas pu être mises à jour"
		);
		// dd($data);
		if ($data['type'] == 'email' || $data['type'] == 'sms' || $data['type'] == 'task') {
			try {
				// Delete the file with the old template name
				\Storage::disk('templates')->delete($data['old_name_file']);
				// Create new file of content of the template in storage
				\File::put(public_path().'/templates/'.$data['old_name_file'], $data['templateBody']);
				$file = $data['old_name_file'];
				Template::where('id', '=', $id)->update([ 'subject' => ($data['templateSubject'] == null) ? '' : $data['templateSubject'] ]);

				$response = array(
					'status' => true, // Status true
					'message' => 'Les informations ont été modifiées'
				);
			} catch (\Exception $e) {
				$response = array(
					'status' => true, // Status true
					'message' => $e->getMessage()
				);
			}
		}
		//Return response
		return response($response)->header('Content-Type', 'application/json');
	}

	/**
	 * Update template
	 */
	public function editTemplateSMS(Request $request) {
		// Init response
		$response = array('status' => false);
		//Get data of the request
		$data = $request->all();
		// If the template name don't change
		if ($data['old_name_template'] == $data['template_name']) {
			// Create file (Replace only the content of the txt file)
			\File::put(public_path().'/templates/'.$data['file'], $data['text_of_message']);
			$updated = $this->updateData(app("App\\Models\\Template"), 'name', $data['template_name'], $data['template_id']); // Updatate data
			$response = array(
				'status' => true, // Status true
				'message' => 'Le modèle a été créé'
			);
		}
		// If the template name is changed
		if ($data['old_name_template'] != $data['template_name']) {
			// Delete the file with the old template name
			\Storage::disk('templates')->delete($data['file']);
			// Take the new name and turn it into a slug
			$new_name = $this->slugP($data['template_name']);
			// The new file with the new name is created
			\File::put(public_path().'/templates/'.$new_name.'.txt', $data['text_of_message']);
			$updated = $this->updateData(app("App\\Models\\Template"), 'name', $data['template_name'], $data['template_id']); // Updatate data
			$updated = $this->updateData(app("App\\Models\\Template"), 'file', $new_name.'.txt', $data['template_id']); // Updatate data
			$response = array(
				'status' => true, // Status true
				'message' => 'Le modèle a été créé'
			);
		}

		//Return response
		return response($response)->header('Content-Type', 'application/json');
	}

	/**
	 * Create new template to the condition notary
	 */
	public function newTemplateCondition(Request $request) {
		//Get data of the request
		$data = $request->all();
		// Init response
		$response = array('status' => false);

		if (isset($data['form_template_name'])) {
			\File::put(public_path().'/templates/'.$data['slug-template-name-condition'].'.txt', $data['form_condition']);
			$file = $data['slug-template-name-condition'].'.txt';
			Template::create([
				'name' => $data['form_template_name'],
				'file' => $file,
				'type' => $data['type'],
				'user' => Auth::user()->id
			]);
		}
		$response = array(
			'status' => true, // Status true
			'message' => 'Le modèle a été créé'
		);
		//Return response
		return response($response)->header('Content-Type', 'application/json');
	}

	/**
	 * Create new template to text to offer
	 */
	public function newTemplateTexteOffer(Request $request) {
		//Get data of the request
		$data = $request->all();
		// Init response
		$response = array('status' => false);

		if (isset($data['file'])) {
			\File::put(public_path().'/templates/'.$data['file'].'.html', $data['form_text_offer']);
			$file = $data['file'].'.html';
			Template::create([
				'name' => $data['file'],
				'file' => $file,
				'subject' => '',
				'type' => $data['type'],
				'user' => Auth::user()->id
			]);
		}
		$response = array(
			'status' => true, // Status true
			'message' => 'Le modèle a été créé'
		);
		//Return response
		return response($response)->header('Content-Type', 'application/json');
	}

	/**
	 * Create slug to file name of the templates
	 */
	private function slugP($string) {
		$table = array(
			'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
			'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
			'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
			'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
			'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
			'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
			'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
			'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r', '/' => '-', ' ' => '-'
		);
		// -- Remove duplicated spaces
		$stripped = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $string);
		// -- Returns the slug
		return strtolower(strtr($string, $table));
	}

	/**
	 * Delete template
	 */
	public function deleteTemplate($id, $name) {
		// Init updated
		$deleted = false;
		// Init the reponse
		$response = array(
			'status' => false, // Reponse status
			'message' => 'Le modèle n\'a pas été supprimé' // Response message
		);
		// Deleting the user
		\Storage::disk('templates')->delete($name);
		$deleted = Template::where('id', '=', $id)
				->delete();
		// If updated is true
		if($deleted) {
			$response = array(
				'status' => true,
				'message' => 'Le modèle a été supprimé'
			);
		}
		// If updated is false
		if(!$deleted) {
			$reponse = array(
				'status' => false,
				'message' => 'Certaines données n\'ont pas pu être supprimées, veuillez réessayer plus tard ...'
			);
		}
		// Return response
		return response($response)->header('Content-Type', 'application/json');
	}

	/**
	 * Send Email Template Test
	 */
	public function sendEmailTemplateTest(Request $request) {
       // dd($request);die;
		$data = new \stdClass(); // Create new object called $data
		$data->form_phone_message = $request->templateBody; // Save body of the email
		$data->subject = $request->subject_email; // Save subject of the email
		$data->from = Auth::user()->email; // Save email of the user authenticated
		$data->fromName = Auth::user()->name; // Save name of the user authenticated
		try {
			// Sending email
			Mail::to($request->email_test)->send(new EmailT($data));
			$response = array( // If response is true
				'status' => true,
				'message' => 'Le e-mail a été envoyé'
			);
		} catch (\Exception $e) {
			$reponse = array( // If response is false
				'status' => false,
				'message' => 'Le e-mail n\'a pas pu être envoyé, veuillez réessayer plus tard...'
			);
		}
		// Return response
		return response($response)->header('Content-Type', 'application/json');
	}

	/**
	 * Send SMS Template
	 */
	public function sendSMSTemplateTest(Request $request) {
		// Get all data of request
		$data = $request->all();
		try {
			$basic  = new \Nexmo\Client\Credentials\Basic('20c3b951', '3C9zf1Y4cH2UH5Xu');
			$client = new \Nexmo\Client($basic);
			// Sending SMS
			$message = $client->message()->send([
				'to' => $data['sms_test'],
				'from' => 'Wesold',
				'text' => $data['form_phone_message']
			]);

			$response = array(
				'status' => true,
				'message' => 'Le sms a été envoyé'
			);
		} catch (\Exception $e) {
			$reponse = array(
				'status' => false,
				'message' => 'Le sms n\'a pas pu être envoyé, veuillez réessayer plus tard...'
			);
		}

		// Return response
		return response($response)->header('Content-Type', 'application/json');
	}

    public function sendConfirmationEmail(Request $request)
    {

        dd($request);die;
        // Obtener los datos del formulario
        $seller_email = $request->input('seller_email');
        $seller_name = $request->input('seller_name');
        $estate_id = $request->input('estate_id');
        $estate_address = $request->input('estate_address');
        $estate_reference = $request->input('estate_reference');
        $modal_date = $request->input('modal_date');
        $modal_date_confirm_start = $request->input('modal_date_confirm_start');
        $modal_date_confirm_end = $request->input('modal_date_confirm_end');
        $template_id = $request->input('template_id');
        $subject = $request->input('subject');
        $body = $request->input('body');

        // Construir los datos para el correo electrónico
        $data = new \stdClass();
        $data->from = Auth::user()->email;
        $data->fromName = Auth::user()->name;
        $data->emails = array($seller_email);
        $data->subject = $subject;


        $body = str_replace('{seller_name}', $seller_name, $body);
        $body = str_replace('{estate_address}', $estate_address, $body);
        $body = str_replace('{modal_date}', $modal_date, $body);
        $body = str_replace('{modal_date_confirm_start}', $modal_date_confirm_start, $body);
        $body = str_replace('{modal_date_confirm_end}', $modal_date_confirm_end, $body);
        $data->body = html_entity_decode($body);


        try {
            Mail::to($seller_email)->send(new EmailT($data));
            $response = array(
                'status' => true,
                'message' => 'Le e-mail a été envoyé'
            );
        } catch (\Exception $e) {
            $response = array(
                'status' => false,
                'message' => 'Le e-mail n\'a pas pu être envoyé, veuillez réessayer plus tard...'
            );
        }

         if ($response['status']) {
            return back()->with('success', $response['message']);
        } else {
            return back()->with('error', $response['message']);
        }
    }

	/**
	 * Save reminder
	 */
	public function saveReminderA(Request $request) {
		// Get all data of request
		$data = $request->all();
		$totalReminders = count($data['type_rappel']);
		$reminders = array();
		for ($i=0; $i < $totalReminders; $i++) {
			$reminder["position"] = $i;
			$reminder["type_template"] = $data['type_rappel'][$i];
			$reminder["template"] = $data['type_rappel_choised'][$i];
			$reminder["days"] = $data['days'][$i];
			$reminders[] = $reminder;
		}
		$reminders = serialize($reminders);
		try {
			$a = TemplateReminder::create([
				'name' => $data['templateName'],
				'reminder' => $reminders,
				'user' => Auth::user()->id
			]);
			$response = array(
				'status' => true,
				'message' => 'Le processus a été créé'
			);
		} catch (\Exception $e) {
			$reponse = array(
				'status' => false,
				'message' => 'Le processus n\'a pas pu être créé'
			);
		}
		// Return response
		return response($response)->header('Content-Type', 'application/json');
	}

	/**
	 * Send SMS Template
	 */
	public function getReminders() {
        $reminders = TemplateReminder::all();
        $remindersArray = array();

        foreach ($reminders as $reminder) {
            $reminderArray = [
                'id' => $reminder->id,
                'name' => $reminder->name,
                'reminder' => null,
                'user' => $this->getUser($reminder->user)
            ];

            // Verificar si la cadena está serializada
            if (strpos($reminder->reminder, 's:') === 0) {
                // Si está serializada, deserializar la cadena
                $reminderArray['reminder'] = unserialize($reminder->reminder);
            } else {
                // Si no está serializada, guardar la cadena tal cual
                $reminderArray['reminder'] = $reminder->reminder;
            }

            $remindersArray[] = $reminderArray;
        }

        return $remindersArray;
    }

	/**
	 * Delete template reminder
	 */
	public function deleteReminder($id) {
		// Init updated
		$deleted = false;
		// Init the reponse
		$response = array(
			'status' => false, // Reponse status
			'message' => 'Le modèle n\'a pas été supprimé' // Response message
		);
		// Deleting the user
		$deleted = $this->deleteData(app("App\\Models\\TemplateReminder"), $id);
		// If updated is true
		if($deleted) {
			$response = array(
				'status' => true,
				'message' => 'Le modèle a été supprimé'
			);
		}
		// If updated is false
		if(!$deleted) {
			$reponse = array(
				'status' => false,
				'message' => 'Certaines données n\'ont pas pu être supprimées, veuillez réessayer plus tard ...'
			);
		}
		// Return response
		return response($response)->header('Content-Type', 'application/json');
	}

	/**
	 * Save hollyday date
	 */
	public function saveDateSpecial(Request $request) {
		// Get all data of request
		$data = $request->all();
		try {
			// Clean data
			$deleted = DB::table('dates')->delete();
			// Save each date
			foreach ($data['dates'] as $date) {
				Date::create([
					'date_special' => $date
				]);
			}
			$response = array(
				'status' => true,
				'message' => 'Les dates ont été enregistrées'
			);
		} catch (\Exception $e) {
			$reponse = array(
				'status' => false,
				'message' => $e
			);
		}
		// Return response
		return response($response)->header('Content-Type', 'application/json');
	}

	/**
	 * Save hollyday date
	 */
	public function getDateSpecial() {
		$dates = Date::get();
		$datesArray = array();
		foreach ($dates as $date) {
			$datesArray[] = array(
				'id' => $date->id,
				'date_special' => $date->date_special
			);
		}
		return $datesArray;
	}

	/**
	 * Save type of menu on view of details estate
	 */
	public function saveMenu(Request $request) {
		// Get all data of request
		$data = $request->all();
		// Init updated
		$updated = false;
		// Init the reponse
		$response = array(
			'status' => false, // Reponse status
			'message' => 'Le menu n\'a pas été mise à jour ou les informations n\'ont pas été modifiées.' // Response message
		);

		$updated = $this->updateData(app("App\\Models\\User"), 'menu', $data['menu'], Auth::user()->id); // Updated data

		if($updated) { // If updated is true
			$response = array(
				'status' => true,
				'message' => 'La menu a été mise à jour'
			);
		}

		if(!$updated) { // If updated is false
			$reponse = array(
				'status' => false,
				'message' => 'Certaines données n\'ont pas pu être mises à jour ou ont la même valeur, veuillez réessayer plus tard ...'
			);
		}

		// Return response
		return response($response)->header('Content-Type', 'application/json');
	}

}
