<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Categories helper functions
 */

// Return an array of menus
if ( ! function_exists('list_all_menus'))
{
	function list_all_menus()
	{
		$CI =& get_instance();
		$CI->load->model('categories_model', NULL, true);
		$result = $CI->categories_model->getCategories(FALSE, 0, FALSE, FALSE, FALSE);
		if($result) return $result;
		else return array();
	}
}

// Return all categories within a menu
if ( ! function_exists('list_menu_categories'))
{
	function list_menu_categories($menu_id)
	{
		$CI =& get_instance();
		$CI->load->model('categories_model', NULL, true);
		$results = $CI->categories_model->getCategories();
		foreach($results as $result)
			if($result->id == $menu_id && isset($result->nodes)) {
				$children = $result->nodes;
				break;
			}
		if(isset($children)) return $children;
		else return array();
	}
}

// Find the category by it's id or name
if ( ! function_exists('find_a_category'))
{
	function find_a_category($id, $name=FALSE)
	{
		$CI =& get_instance();
		$CI->load->model('categories_model', NULL, true);
		$result = $CI->categories_model->getCategory($id, $name, FALSE);
		return $result;
	}
}

/* End of file */