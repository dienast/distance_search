<?php
/*
Plugin Name: Distance Search
Plugin URI: http://www.osclass.org/
Description: Get search results based on the distance between cities - v1.0 by dienast
Version: 1
Author: dienast
Author URI: http://www.osclass.org/
Short Name: distance_search
*/

require 'searchDistance.php';

/**
 * Update cities table for d_coord_lat and d_coord_long values of cities
 */
function ds_call_after_install() {
	searchDistance::newInstance()->updateTable_Cities();
}

/**
 * Show dropdown with distance kilometer values in search form (you are able to edit them)
 */
function ds_search_form() {
	include("search_form.php");
}

/**
* Extend search query with foreign keys possible cities 
*/
function ds_search_conditions($results) {
	$fks = searchDistance::newInstance()->distancefks($result);		
	Search::newInstance()->addConditions(sprintf(" 1 = 1 ".$ORs." "));	
	if ($fks) {	
		foreach ($fks as &$value) {
			$ORs = $ORs.$queryORs = " OR oc_t_item_location.fk_i_item_id = '".$value["fk_i_item_id"]."' ";				
			if ($value["s_city"] != $_GET["sCity"]) {
				Search::newInstance()->addCity(sprintf($value["s_city"]));
			}
		}
	}
}

// Hook for registering plugin 
osc_register_plugin(osc_plugin_path(__FILE__), 'ds_call_after_install');	

// Hook for adding new search conditions
osc_add_hook('search_conditions', 'ds_search_conditions', 1);

// Hook for showing extra fields in search form
osc_add_hook('search_form', 'ds_search_form', 1);
?>
