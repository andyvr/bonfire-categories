<script type="text/javascript">
  var base_url = '<?php echo site_url("/"); ?>';
  var permission_matrix = {
	'category_view': <?php echo has_permission('Categories.Content.View') ? 'true' : 'false'; ?>,
	'category_edit': <?php echo has_permission('Categories.Content.Edit') ? 'true' : 'false'; ?>
  };
</script>
<div class="admin-box" id="main-container" ng-app="categoriesApp">
		<div id="main" class="wrapper clearfix container static-page ng-cloak" ng-cloak ng-controller="CategoriesCtrl">
			<article>    

                <div class="header clearfix">
					<h1 class="pull-left">
                    	<span>Manage Categories</span>
                        <span class="h1buttons" ng-show="permission_matrix.category_edit">
                            <button type="button" class="inline-button" title="Create a new menu" ng-click="add_menu()"><i class="icon-plus"></i> new menu</button>
                        </span>
                    </h1>
				</div>
                
                <div class="main-cont ng-cloak" ng-cloak>
                    <div class="row-fluid">
                    
                        <div class="span6">
                        	<div class="well">
                                <div class="alert alert-info" ng-hide="menus.length">
                                	You can create a new menu by clicking on "new menu" button!
                                </div>
                                <div class="clearfix" ng-show="menus.length">
                                    <h4>Menus</h4>
                                    <ul class="nav nav-tabs nav-stacked" ui-sortable>
                                        <li ng-repeat="menu in menus" ng-class="{'selected':menu.id==cur_menu.id}" ng-click="select_menu(menu, $event)">
                                            <a href="javascript:void(0)" class="clearfix">
                                                <span class="link">{{menu.name}}</span>
                                                <button type="button" class="details inline-button" ng-click="tggl=!tggl;" ng-init="tggl=false">
                                                    <i ng-hide="tggl" class="icon-chevron-down" title="Show details"></i>
                                                    <i ng-show="tggl" class="icon-chevron-up" title="Hide details"></i>
                                                </button>
                                                <div class="pull-right">
                                                    <span class="mini">menu id: {{menu.id}}</span>
                                                    <span class="date">Modified: {{makeDate(menu.date)}} {{makeTime(menu.date)}}</span>
                                                    <button ng-show="permission_matrix.category_edit" type="button" class="remove inline-button" title="Remove Menu" ng-click="remove_cat(menu)"><i class="icon-trash remove"></i></button>
                                                </div>
                                            </a>
                                            <div class="content" ng-class="{hide:!tggl}">
                                                <div>
                                                    <span>Name: </span>
                                                    <input ng-disabled="!permission_matrix.category_edit" ng-model="menu.name" type="text" placeholder="type the menu name here..." ng-change="category_change(menu)" />
                                                </div>
                                                <div>
                                                    <span>Description: </span>
                                                    <input ng-disabled="!permission_matrix.category_edit" ng-model="menu.description" type="text" placeholder="type the menu description here..." ng-change="category_change(menu)" />
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                        	</div>
                            
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="buttons">
                                        <button ng-show="permission_matrix.category_edit" type="button" class="btn" title="Save" ng-click="process_cats()"><i class="icon-download-alt "></i> Save Changes</button>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        
                        <div class="span6">
                        	<div class="well">
                                <div class="clearfix" ng-show="cur_menu">
                                	<h4 class="pull-left" ng-show="cur_menu.nodes.length">Menu Items/Categories</h4>
                                    <button ng-show="permission_matrix.category_edit" style="margin-top: 10px;" type="button" class="inline-button pull-right" title="Create a new category" ng-click="add_cat()">
                                    	<i class="icon-plus"></i> new category
                                    </button>
                                </div>
                                <div class="alert alert-info" ng-show="!cur_menu">Please select a menu to list it's items.</div>
                                <div class="alert alert-info" ng-show="cur_menu && !cur_menu.nodes.length">This menu has no child categories.</div>
                                <div ng-switch on="permission_matrix.category_edit">
                                    <ul ng-switch-when="false" class="nav nav-tabs nav-stacked hierarchy ui-sortable" ng-model="types_select_obj" ng-show="cur_menu.nodes.length" id="catitems">
                                        <li id="cat-{{item.id}}" ng-repeat="item in cur_menu.nodes" ng-include="'tree_item_renderer.html'" ng-class="get_node_classes(item)"></li>
                                    </ul>
                                    <ul ng-switch-when="true" class="nav nav-tabs nav-stacked hierarchy" ui-jq="nestedSortable" ng-model="types_select_obj" ng-show="cur_menu.nodes.length" id="catitems">
                                        <li id="cat-{{item.id}}" ng-repeat="item in cur_menu.nodes" ng-include="'tree_item_renderer.html'" ng-class="get_node_classes(item)"></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    
                </div>
                
                <!--*** Templates & View Partials ***-->
                <!-- Nested Category View -->
                <script type="text/ng-template"  id="tree_item_renderer.html">
					<span class="ctrl_bttns">
						<button class="inline-button expand" type="button" ng-click="set_collapsed(item, false)" title="Expand"><i class="icon-plus"></i></button>
						<button class="inline-button collaps" type="button" ng-click="set_collapsed(item, true)" title="Collapse"><i class="icon-minus"></i></button>
					</span>
					<a href="javascript:void(0)" ng-click="accordion_slide($event);tggl=!tggl;" ng-init="tggl=false" class="clearfix">
						<div class="pull-left">
							<span>{{item.name}}</span>
							<i ng-hide="tggl" class="icon-chevron-down inline-button"></i>
							<i ng-show="tggl" class="icon-chevron-up inline-button"></i>
						</div>
						<div class="pull-right">
							<span class="date">Modified: {{makeDate(item.date)}} {{makeTime(item.date)}}</span>
							<button ng-show="permission_matrix.category_edit" type="button" class="remove inline-button" title="Remove" ng-click="remove_cat(item)"><i class="icon-trash remove"></i></button>
						</div>
					</a>
					<div class="content hide">
						<div>
							<span>Name: </span>
							<input ng-disabled="!permission_matrix.category_edit" ng-model="item.name" ng-change="category_change(item)" placeholder="type the category name here..." type="text" />
						</div>
						<div>
							<span>Description: </span>
							<input ng-disabled="!permission_matrix.category_edit" ng-model="item.description" ng-change="category_change(item)" placeholder="type the category description here..." type="text" />
						</div>
						<div>
							<span>Link: </span>
							<input ng-disabled="!permission_matrix.category_edit" ng-model="item.url" ng-change="category_change(item)" placeholder="type the category url here..." type="text" />
						</div>
					</div>
					<ul class="nav nav-tabs nav-stacked" ng-show="item.nodes && !item.collapsed">
						<li id="cat-{{item.id}}" ng-repeat="item in item.nodes" ng-include="'tree_item_renderer.html'" ng-class="get_node_classes(item)"></li>
					</ul>
				</script>
                
            </article>
		</div> <!-- #main -->
</div> <!-- #main-container -->