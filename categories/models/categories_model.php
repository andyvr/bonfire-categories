<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Categories_model extends BF_Model {

	protected $table		= "categories";
	protected $table_name	= "categories";
	protected $key			= "id";
	protected $soft_deletes	= false;
	protected $date_format	= "datetime";
	protected $set_created	= false;
	protected $set_modified = true;
	protected $modified_field = "modified_on";
	
	
	// Insert/Update/Delete the the record
	// takes data in the form of array, optional will take a record id or a removal flag
	public function setCategory ($data, $id=FALSE, $remove=FALSE, $removechildren=FALSE)
	{
		$this->load->helper('date');
		$data = (array)$data;
		if (isset($data['date'])) 
		{
			$data['modified_on'] = unix_to_human($data['date'], TRUE, 'eu');
			unset($data['date']);
		}
		if (!$id && isset($data['id'])) $id = $data['id'];
		if ($id && $remove)
		{
			$this->db->where('id', $id);
			$this->db->delete($this->table_name);
			if($removechildren)
			{
				$this->db->where('parent_id', $id);
				$this->db->delete($this->table_name);
			}
			else
			{	//change parent for subcategories
				$data = array("parent_id" => 0);
				$this->db->where('parent_id', $id);
				$this->db->update($this->table_name, $data);
			}
			return $id;
		}
		// store data
		if ($id)
		{
			$this->db->where('id', $id);
			$this->db->update($this->table_name, $data);
		}
		else
		{
			unset($data['id']);
			$this->db->insert($this->table_name, $data);
			$id = $this->db->insert_id();
		}
		return $id;
	}
	//get single lead by id ($check_user= check if user is logged and verify it)
	public function getCategory ($id, $name=FALSE, $admin_only=TRUE)
	{
		if (!$id && !$name) return FALSE;
		//get data
		if ($id) $this->db->where('id', $id);
		else if ($name) 
		{
			$name = urldecode($name);
			$this->db->where('name', str_replace("_", " ", $name));
		}
		$query = $this->db->get($this->table_name, 1);
		$rec = $query->result();
		if (isset($rec[0])) return $rec[0];
		else return FALSE;
	}
	//forms the part of child categories + a parent category, by a $cat_id
	/* Returns: Parent object,
	*  children - array of objects; sets active property for the active category
	*/
	public function getCatNavigation ($cat_id)
	{
		$cur_cat = $this->getCategory($cat_id, FALSE, FALSE);
		if (!$cur_cat) return FALSE;
		// this is parent category
		if (!$cur_cat->parent_id)
		{
			$parent = $cur_cat;
			$parent->active = TRUE;
		}
		else
		{
			//find parent category
			$parent = $this->getCategory($cur_cat->parent_id, FALSE, FALSE);
		}
		$children = $this->getCategories(FALSE, $parent->id, FALSE, FALSE);
		if ($children) 
			foreach($children as $key => $val)
			{
				if ($cat_id == $val->id) $children[$key]->active = TRUE;
			}
		else $children = array();
		$res = new stdClass;
		$res->parentcat = $parent;
		$res->children = $children;
		return $res;
	}
	public function searchCategories ($name, $limit=FALSE)
	{
		$this->db->like('name', $name, 'both');
		if ($limit)
			$query = $this->db->get($this->table_name, $limit);
		else
			$query = $this->db->get($this->table_name);
		if (!($result = $query->result())) return FALSE;
		else return $result;
	}
	//get multiple categories with $limit or only children of $parent, or $count_only results
	public function getCategories ($limit=FALSE, $parent=FALSE, $count_only=FALSE, $admin_only=TRUE, $show_as_tree=TRUE)
	{
		
		if ($parent !== FALSE) $this->db->where('parent_id', $parent);
		$this->db->order_by("sort_order", "asc");
		if ($count_only)
			return $this->db->count_all_results($this->table_name);
		else if ($limit)
			$query = $this->db->get($this->table_name, $limit);
		else
			$query = $this->db->get($this->table_name);
		if (!($result = $query->result())) return FALSE;
		else 
		{
			foreach($result as $key => $val)
			{
				$result[$key]->collapsed = (bool)$val->collapsed;
				$result[$key]->date = strtotime($val->modified_on);
			}
			if($parent === FALSE || $show_as_tree)
			{
				$this->load->helper('trees');
				$result = arrayofobj_to_tree($result, 'id', 'parent_id', 'nodes');
			}
			return $result;
		}
	}
	
}
