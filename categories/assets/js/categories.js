// UI MODULE
var categoriesApp = angular.module('categoriesApp', ['ui','ngCookies']).config(['$routeProvider','$locationProvider',function ($routeProvider,$locationProvider) {
  // route provider config
  // location provider config
  $locationProvider.html5Mode(false).hashPrefix('!');
}]);
//setup angular ui
categoriesApp.value('ui.config', {
   // The ui-jq directive namespace
   jq: {
	  nestedSortable: {
         doNotClear			: false,
		 handle				: 'a',
         items				: 'li',
         toleranceElement	: '> a',
		 listType			: 'ul',
		 isTree				: true,
		 startCollapsed		: false,
		 branchClass		: 'ns-branch',
		 collapsedClass		: 'ns-collapsed',
		 expandedClass		: 'ns-expanded',
		 hoveringClass		: 'ns-hovering',
		 leafClass			: 'ns-leaf',
		 dataChanged		: function(items) {},
      }
   }
});

// MAIN
function CategoriesCtrl($scope, $http, $routeParams, $location, $filter, $cookies)
{
	//MODELS
	$scope.menus		= [];
	$scope.cur_menu		= false;
	//get parameters
	$scope.cat_parent	= "";
	$scope.cat_id		= "";
	$scope.cat_limit	= "";
	$scope.permission_matrix = permission_matrix;
	
	//FUNCTIONS
	//store categories / Lists
	$scope.process_cats = function()
	{
		var serialized = jQuery('ul.hierarchy').nestedSortable('toArray', {startDepthCount: 0});
		angular.forEach(serialized, function(val, itm) {
		  if(val.item_id) {
			  var objx = findWithAttr($scope.menus, 'id', val.item_id, 'nodes');
			  if (val.parent_id) objx.parent_id = val.parent_id;
			  else objx.parent_id = $scope.cur_menu.id;
			  objx.sort_order = itm;
		  }
		});
		$scope.store_cats();
	}
	$scope.store_cats = function()
	{
		var csrf = "";
		if(angular.isDefined($cookies.ci_csrf_token)) csrf = $cookies.ci_csrf_token;
		$http({
		  method: 'POST',
		  url: base_url + 'categories/admin_categories/set',
		  data: {
			  	'categories'	: angular.toJson($scope.menus),
				'ci_csrf_token'	: csrf
		  },
		  transformRequest: function(data){ return jQuery.param(data); },
		  headers: {
			'Accept': 'application/json',
			'X-HTTP-Method-Override': 'POST',
			'Content-Type': 'application/x-www-form-urlencoded'
		  },
		  cache: false,
		}).success(function(data) {
			$scope.get_cats();
		});
	}
	//get categories / Lists
	$scope.get_cats = function()
	{
		$http.get(base_url + 'categories/admin_categories/get?id=' + $scope.cat_id + '&parent=' + $scope.cat_parent + '&limit=' + $scope.cat_limit).success(function(data) {
			$scope.menus = data;
			jQuery("#catitems li").last().detach();
			if($scope.cur_menu) 
				$scope.select_menu($filter('filter')(data, {"id": $scope.cur_menu.id})[0], false);
		});
	}
	//remove/delete category or menu
	$scope.remove_cat = function(itm)
	{
		if(itm.parent_id == 0) var type = "Menu";
		else var type = "Category/Menu Item";
		bootbox.dialog("Are you sure want to remove this "+ type +"?", [{
			"label" : "Cancel",
			"class" : "plain"
		}, {
			"label" : "Ok",
			"class" : "btn-danger",
			"callback": function() {
				$http.get(base_url + 'categories/admin_categories/set?id=' + itm.id + '&op=delete').success(function(data) {
				  $scope.get_cats();
				  //$scope.to_trash(itm);
				});
			}
		}]);
	}
	$scope.add_cat = function()
	{
		var model = {
            id			: "",
			parent_id	: $scope.cur_menu.id,
			name		: "New Category",
            description	: "",
			url			: "",
			date		: $scope.makeUnixTime(),
        }
		if(!angular.isArray($scope.cur_menu.nodes)) $scope.cur_menu.nodes = [];
		$scope.cur_menu.nodes.push(model);
		$scope.store_cats();
	}
	$scope.category_change = function(itm)
	{
		itm.date = $scope.makeUnixTime();
	}
	$scope.add_menu = function()
	{
		var model = {
            id			: "",
			parent_id	: "0",
			name		: "New Menu",
            description	: "",
			nodes		: [],
			date		: $scope.makeUnixTime(),
        }
        $scope.menus.unshift(model);
		$scope.store_cats();
	}
	//select current menu and show it's children
	$scope.select_menu = function(menu, evt)
	{	
		if(!angular.isArray(menu.nodes)) menu.nodes = [];
		$scope.cur_menu = menu;
		if(evt) 
		{
			//select the menu item
			jQuery(evt.srcElement).siblings().removeClass("selected");
			jQuery(evt.srcElement).addClass("selected");
		}
	}
	$scope.accordion_slide = function(evt)
	{
		$scope.cur_category = 1;
		if (!jQuery(evt.srcElement).hasClass('remove'))
		{
			jQuery(evt.currentTarget).siblings(".content").slideToggle();
		}
	}
	//push a deleted category to trash
	$scope.to_trash = function(itm)
	{
		$scope.trash_cats.push(itm);
		$scope.trash('set');
	}
	//untrash a category from trash
	$scope.from_trash = function(itm)
	{
		itm.id = "";
		itm.parent_id = "0";
		$scope.categories.unshift(itm);
		$scope.remove_trash(itm);
		$scope.store_cats();
	}
	//permanently remove a category from trash
	$scope.remove_trash = function(itm)
	{
		var idx = $scope.trash_cats.indexOf(itm); // Find the index
		if(idx!=-1)
		{
			$scope.trash_cats.splice(idx, 1);
			$scope.trash('set');
		}
	}
	//get-set trash from remote server
	$scope.trash = function(action)
	{
		if (action == 'get') var url = base_url + 'categories/admin_categories/trash?get=1';
		else var url = base_url + 'categories/admin_categories/trash';
		$http.post(url, {'trash': $scope.trash_cats}).success(function(data) {
		  $scope.trash_cats = data;
		});
	}
	//get node classes
	$scope.get_node_classes = function(model)
	{
		var out = "";
		var element = jQuery("#cat-" + model.id);
		if(model.collapsed){
			element.addClass("ns-collapsed");
			element.removeClass("ns-expanded");
		}
		else {
			element.removeClass("ns-collapsed");
			element.addClass("ns-expanded");
		}
		if(model.nodes && model.nodes.length) 
		{
			out += "ns-branch ";
		}
		else out += "ns-leaf ";
		return out;
	}
	//set node's collapsed state
	$scope.set_collapsed = function(model, state)
	{
		model.collapsed = state;
	}
	
	//SERVICE FUNCTIONS
	$scope.computed_property = function(model)
	{
		var output = "";
		jQuery.each( model, function(itm, val){
		  if (val) output += val + " ";
		});
		output = output.slice(0,-1);
		return output;
	}
	//create date portion out of unix timestamp
	$scope.makeDate = function(timestamp) {
		return $scope.getDateObj(timestamp).toLocaleDateString();
	}
	//create time portion out of unix timestamp
	$scope.makeTime = function(timestamp) {
		return $scope.getDateObj(timestamp).toLocaleTimeString();
	}
	//get date object out of unix timestamp
	$scope.getDateObj = function(timestamp) {
		return new Date(timestamp*1000);
	}
	//get date object out of unix timestamp
	$scope.makeUnixTime = function() {
		return parseInt(new Date().getTime()/1000);
	}
	
	//scope safe apply
	$scope.safeApply = function(fn) {
		var phase = this.$root.$$phase;
		if(phase == '$apply' || phase == '$digest') {
			if(fn && (typeof(fn) === 'function')) {
				fn();
			}
		} else {
			this.$apply(fn);
		}
	};
	
	//INIT LOGIC
    $scope.get_cats();
	//$scope.trash('get');
}


//STANDALONE FUNCTIONS
function findWithAttr(array, attr, value, sub_arr_name) {
    var outobj = {};
	for(var i = 0; i < array.length; i += 1) {
        if(array[i][attr] === value) {
            return array[i];
        }
		else if (array[i][sub_arr_name])
		{
			outobj = findWithAttr(array[i][sub_arr_name], attr, value, sub_arr_name);
			if(outobj) {
				return outobj;
			}
		}
    }
}
//add leading zero to the numbers less than 10
function addzero(val) {
	if (val < 10) return '0' + val;
	else return val;
}