<div>
	<h1 class="page-header">Categories</h1>
</div>
<br />

<?php 
	function build_nested_menu($categories, $outstring){
		if(empty($categories)) return $outstring;
		$outstring .= PHP_EOL.'<ul> '.PHP_EOL;
		foreach($categories as $cat) {
			$outstring .= '<li><a href="'.$cat->url.'">'.$cat->name.'</a> ';
				if(isset($cat->nodes)) return build_nested_menu($cat->nodes, $outstring) .'</ul> '.PHP_EOL;
			$outstring .= '</li> '.PHP_EOL;
		}
		$outstring .= '</ul> '.PHP_EOL;
		return $outstring;
	} 
?>

<?php $this->load->helper('categories/categories'); ?>

<h4>List of Menus:</h4>

<?php foreach(list_all_menus() as $menu): ?>
	<p>
    	Name: <b><?php echo $menu->name ?></b><br />
        <small><em>Description: <b><?php echo $menu->description ?></b></em></small>
    </p>
<?php endforeach; ?>

<hr />

<h4>Menu Categories:</h4>

<?php foreach(list_all_menus() as $menu): ?>
	<p>Menu: <b><?php echo $menu->name ?></b></p>
    <p><?php echo build_nested_menu(list_menu_categories($menu->id), "") ?></p>
<?php endforeach; ?>