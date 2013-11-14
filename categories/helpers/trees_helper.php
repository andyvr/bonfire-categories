<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Misc helper functions
 */

//convert an array of objects to a tree like structure, (id_name, parent_name, sub_node_name - are names for the db columns)
// ex: $result = arrayofobj_to_tree($result, 'id', 'parent_id', 'nodes');
if ( ! function_exists('arrayofobj_to_tree'))
{
	function arrayofobj_to_tree($array, $id_name, $parent_name, $sub_node_name)
	{
		$childs = array();
		foreach($array as $item)
			$childs[$item->$parent_name][] = $item;
		foreach($array as $item) if (isset($childs[$item->$id_name]))
			$item->$sub_node_name = $childs[$item->$id_name];
		return array_shift($childs);
	}
}
//convert an array of arrays to a tree like structure, (id_name, parent_name, sub_node_name - are names for the db columns)
if ( ! function_exists('arrayofarr_to_tree'))
{
	function arrayofarr_to_tree($array, $id_name, $parent_name, $sub_node_name)
	{
		$childs = array();
		foreach($array as &$item) $childs[$item[$parent_name]][] = &$item;
		unset($item);
		foreach($array as &$item) if (isset($childs[$item[$id_name]]))
				$item[$sub_node_name] = $childs[$item[$id_name]];
		return $childs[0];
	}
}
//flatten the tree to the array
if ( ! function_exists('flatten_tree_obj'))
{
	function flatten_tree_obj($array, $sub_node_name)
	{
		$flat = array();
		foreach($array as $item)
		{
			if (isset($item->$sub_node_name))
			{
				$merge_arr = flatten_tree_obj($item->$sub_node_name, $sub_node_name);
				unset($item->$sub_node_name);
				$flat[] = $item;
				$flat = array_merge_recursive($flat, $merge_arr);
			}
			else $flat[] = $item;
		}
		return $flat;
	}
}


/* End of file */