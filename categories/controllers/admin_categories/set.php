<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Set extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->auth->restrict('Categories.Content.Edit');
		$this->load->model('categories_model', null, true);
	}
	
	public function index()
	{
		$this->output->set_content_type('application/json');
		$id = $this->input->get('id');
		$request = json_decode($this->input->post('categories'));
		$operation = $this->input->get('op');
		if ($operation == 'delete' && $id)
		{
			$set = $this->categories_model->setCategory("", $id, TRUE, TRUE);
		}
		else if ($operation == '' && $request)
		{
			$this->load->helper('trees');
			$request = flatten_tree_obj($request, 'nodes');
			foreach($request as $item)
			{
				unset($item->nodes);
				$set = $this->categories_model->setCategory($item);
			}
		}
		else
		{
			$this->output->set_status_header('400', 'Bad Request');
			return;
		}
		if ($set)
		{
			$this->output->set_status_header('200', 'OK');
			$this->output->set_output(json_encode(array('id'=>$set)));
		}
		else
		{
			$this->output->set_status_header('404', 'Not Found');
		}
		$this->output->enable_profiler(FALSE);
	}
}

/* End of file */