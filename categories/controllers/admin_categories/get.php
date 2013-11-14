<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Get extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->auth->restrict('Categories.Content.View');
		$this->load->model('categories_model', null, true);
	}
	
	public function index()
	{
		$this->output->set_content_type('application/json');
		$id = $this->input->get('id');
		$limit = $this->input->get('limit');
		$parent = $this->input->get('parent') ? $this->input->get('parent') : FALSE;
		$count = $this->input->get('count');
		//return one lead
		if ($id)
		{
			$result = $this->categories_model->getCategory($id);
		}
		else if ($count)
		{
			$result = $this->categories_model->getCategories($limit, $parent, TRUE);
			if ($result) $result = array('count' => $result);
		}
		else
		{
			$result = $this->categories_model->getCategories($limit);
		}
		if ($result)
			$this->output->set_output(json_encode($result));
		else
			$this->output->set_output(json_encode(array()));
		$this->output->enable_profiler(FALSE);
	}
}

/* End of file */