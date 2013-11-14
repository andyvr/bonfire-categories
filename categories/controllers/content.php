<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class content extends Admin_Controller {

	//--------------------------------------------------------------------


	public function __construct()
	{
		parent::__construct();

		$this->auth->restrict('Categories.Content.View');
		$this->load->model('categories_model', null, true);
		$this->lang->load('categories');
		//CLEAR ASSETS CACHE
		Assets::clear_cache();
		//Main css file
		Assets::add_module_css('categories', 'style.css');
		// jQuery UI sortable
		Assets::add_module_js('categories', '../jquery-ui-custom/js/jquery-ui-custom.min.js');
		// NestedSortable jQuery plugin
		Assets::add_module_js('categories', '../nestedsortable/jquery.mjs.nestedSortable.js');
		// AngularJS
		Assets::add_module_js('categories', '../angular/angular.min.js');
		Assets::add_module_js('categories', '../angular/angular-cookies.min.js');
		Assets::add_module_js('categories', '../angular-ui/build/angular-ui.min.js');
		Assets::add_module_js('categories', '../js/categories.js');
		// Bootstrap Bootbox
		Assets::add_module_js('categories', '../bootbox/bootbox.min.js');
		
		Template::set_block('sub_nav', 'content/_sub_nav');
	}

	//--------------------------------------------------------------------



	/*
		Method: index()

		Displays a list of form data.
	*/
	public function index()
	{	
		Template::set('toolbar_title', 'Manage Categories');
		Template::render();
	}

	//--------------------------------------------------------------------

}