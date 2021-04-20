<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Include Models
use App\Models\User;
use App\Models\Media;
use App\Models\Seller;
use App\Models\Estate;
use App\Models\Category;
use App\Models\EstateDetail;
use App\Models\EstateStatus;

class ApiController extends Controller
{

	/**
	 * Get Sellers Service
	 */
	public function dataMaisonsVides(Request $request) {
		$data = $request->all();
		$details = json_encode($data);

		// Variable to seller name
		$firstName = (isset($data['firstName'])) ? $data['firstName'] : '';
		$name = (isset($data['lastName'])) ? $data['lastName'] : '';
		try {
			$seller = Seller::get();
			$sellerArray = array();
			foreach ($seller as $_seller) {
				$sellerArray[] = array(
					'id' => $_seller->id,
					'email' => $_seller->email
				);
			}
			foreach ($sellerArray as $seller) {
				if ($data['email'] == $seller['email']) {
					$sellerId['id'] = $seller['id'];
					$sellerId['email'] = $seller['email'];
				} 
				
			}
			if (!empty($sellerId)) {
				$idseller = $sellerId['id'];
			} else {
				//Create new seller
				$seller = Seller::create([
					'name' => $firstName.' '.$name,
					'email' => $data['email'],
					'phone' => (isset($data['tel'])) ? $data['tel'] : '',
					'type' => '',
					'contact_by' => '',
					'reason_sale' => '',
					'looking_property' => '',
					'want_stay_tenant' => 0,
					'when_to_buy' => '',
				]);
				$idseller = $seller->id;
			}

			//Create new estate
			$estate = Estate::create([
				'name' => '',
				'type_estate' => (isset($data['type_bien'])) ? $data['type_bien'] : '',
				'category' => 2,
				'visit_date_at' => NULL,
				'main_photo' => '',
				'street' => (isset($data['address_estate'])) ? $data['address_estate'] : '',
				'number' => 0,
				'box' => 0,
				'code_postal' => 0,
				'city' => '',
				'seller' => $idseller,
				'when_want_sell' => '',
				'want_tenant_after_sell' => '',
				'want_buy_wesold' => '',
				'agent' => 1,
				'notary' => 1,
				'estimate' => 0,
				'market' => 0,
				'type_of_sale' => '',
				'attempt_via_agency' => 0,
				'attempt_via_client' => 0,
				'agency_name' => '',
				'price_published_agence' => 0,
				'date_of_sale_agence' => '',
				'price_published_himself' => 0,
				'date_of_sale_himself' => '',
				'information_additional' => '',
				'module_visit' => 0,
				'date_send_reminder' => '',
				'send_reminder_half_past_eight' => 0,
				'rdv' => 0,
				'information_additional' => (isset($data['commentaire'])) ? $data['commentaire'] : '',
			]);
			//Information converted in json to field 'details (I need to create it)'
			//Create estate details
			$estateDetails = EstateDetail::create([
				'estate_id' => $estate->id,
				'description' => '',
				'comment' => '',
				'problems' => '', //If there is a problem put details of the problem
				'encode' => $details, //Add data of details in format json,
				'adapte' => '',
				'year_construction' => (isset($data['year_construction'])) ? $data['year_construction'] : 0,
				'year_renovation' => 0,
				'coordinate_x' => '',
				'coordinate_y' => '',
				'peb' => '',
				'price_evaluated' =>  0,
				'price_market' => 0,
				'visit_remarks' => '',
			]);

			// Create status log
			EstateStatus::create([
				'estate_id' => $estate->id,
				'category_id' => 2,
				'user_id' => 1,
				'start_at' => date('Y-m-d H:i:s'),
				'stop_at' => null
			]);
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

			return response(['isValidRequest' => true])->header('Content-Type', 'application/json');

		} catch (\Exception $e) {
			return response(['isValidRequest' => false, 'validationErrors' => $e->getMessage()])->header('Content-Type', 'application/json');
		}
	}

	
	/**
	 * Get categories
	 */
	private function getCategories() {
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

}
