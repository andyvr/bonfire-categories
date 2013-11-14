<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Install_categories extends Migration {

	public function up()
	{
		$prefix = $this->db->dbprefix;

		$fields = array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 12,
				'auto_increment' => TRUE,
			),
			'parent_id' => array(
				'type' => 'INT',
				'constraint' => 12,
				'default' => 0,
			),
			'sort_order' => array(
				'type' => 'INT',
				'constraint' => 12,
				'default' => 1,
			),
			'collapsed' => array(
				'type' => 'BOOL',
				'default' => 0,
			),
			'name' => array(
				'type' => 'VARCHAR',
				'constraint' => 200,
			),
			'description' => array(
				'type' => 'VARCHAR',
				'constraint' => 1000,
			),
			'url' => array(
				'type' => 'VARCHAR',
				'constraint' => 500,
				'default' => '',
			),
			'modified_on' => array(
				'type' => 'datetime',
				'default' => '0000-00-00 00:00:00',
			),
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table('categories');

	}

	//--------------------------------------------------------------------

	public function down()
	{
		$prefix = $this->db->dbprefix;

		$this->dbforge->drop_table('categories');

	}

	//--------------------------------------------------------------------

}