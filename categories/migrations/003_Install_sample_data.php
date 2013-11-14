<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Install_sample_data extends Migration {

	// data to migrate
	private $values = array(
	  array('id' => '1', 'parent_id' => '0', 'sort_order' => '1', 'collapsed' => '0', 'name' => 'Menu', 'description' => 'this is a sample menu', 'url' => '', 'modified_on' => '2013-11-14 13:52:05'),
	  array('id' => '2', 'parent_id' => '1', 'sort_order' => '1', 'collapsed' => '0', 'name' => 'Home Page', 'description' => '', 'url' => 'home', 'modified_on' => '2013-11-14 13:52:05'),
	  array('id' => '3', 'parent_id' => '2', 'sort_order' => '2', 'collapsed' => '0', 'name' => 'Admin Page', 'description' => '', 'url' => 'admin', 'modified_on' => '2013-11-14 13:52:05'),
	  array('id' => '4', 'parent_id' => '0', 'sort_order' => '1', 'collapsed' => '0', 'name' => 'Another Menu', 'description' => 'this is another menu', 'url' => '', 'modified_on' => '2013-11-14 13:52:05'),
	  array('id' => '5', 'parent_id' => '4', 'sort_order' => '1', 'collapsed' => '0', 'name' => 'No Link', 'description' => '', 'url' => '#', 'modified_on' => '2013-11-14 13:52:05'),
	  array('id' => '6', 'parent_id' => '3', 'sort_order' => '3', 'collapsed' => '0', 'name' => 'Login Page', 'description' => '', 'url' => 'login', 'modified_on' => '2013-11-14 13:52:05'),
	);

	//--------------------------------------------------------------------
	
	public function up()
	{
		$prefix = $this->db->dbprefix;

		// set data
		foreach ($this->values as $value)
		{
			$this->db->insert("categories", $value);
		}
	}

	//--------------------------------------------------------------------

	public function down()
	{
		$prefix = $this->db->dbprefix;

        // remove data
		foreach ($this->values as $value)
		{
			$this->db->delete("categories", array('id' => $value['id']));
		}
	}

	//--------------------------------------------------------------------
}