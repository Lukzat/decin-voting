<!--
 * CoreUI - Open Source Bootstrap Admin Template
 * @version v1.0.0-alpha.6 wtfawdawdaw
 * @link http://coreui.io
 * Copyright (c) 2017 creativeLabs Łukasz Holeczek
 * @license MIT
 -->
<!DOCTYPE html>
<html lang="cz" ng-app="app">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="CoreUI - Open Source Bootstrap Admin Template">
    <meta name="author" content="Łukasz Holeczek">
    <meta name="keyword" content="Bootstrap,Admin,Template,Open,Source,AngularJS,Angular,Angular2,Angular 2,Angular4,Angular 4,jQuery,CSS,HTML,RWD,Dashboard,React,React.js,Vue,Vue.js">
    <link rel="shortcut icon" href="img/favicon.png">
    
    
    
    <link rel="stylesheet" type="text/css" href="./node_modules/chosen-js/chosen.css" />

    <title>Hlasování zastupitelstva</title>

    <!-- Main styles for this application -->
    <link href="css/style.css" rel="stylesheet">
    

</head>

<!-- BODY options, add following classes to body to change options

	// Header options
	1. '.header-fixed'					- Fixed Header

	// Sidebar options
	1. '.sidebar-fixed'					- Fixed Sidebar
	2. '.sidebar-hidden'				- Hidden Sidebar
	3. '.sidebar-off-canvas'		- Off Canvas Sidebar
  4. '.sidebar-minimized'			- Minimized Sidebar (Only icons)
  5. '.sidebar-compact'			  - Compact Sidebar

	// Aside options
	1. '.aside-menu-fixed'			- Fixed Aside Menu
	2. '.aside-menu-hidden'			- Hidden Aside Menu
	3. '.aside-menu-off-canvas'	- Off Canvas Aside Menu

	// Footer options
	1. 'footer-fixed'						- Fixed footer

	-->

<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">

    <!-- User Interface -->
    <ui-view></ui-view>

    <!-- Bootstrap and necessary plugins -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script>
    <script src="bower_components/tether/dist/js/tether.min.js"></script>
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- TinyMCE -->
    <script type="text/javascript" src="bower_components/tinymce/tinymce.js"></script>
    
    <!-- AngularJS -->
    <script src="bower_components/angular/angular.min.js"></script>
    
    <!-- TinyMCE-UI -->
    <script type="text/javascript" src="bower_components/angular-ui-tinymce/src/tinymce.js"></script>

    <!-- AngularJS plugins -->
    <script src="bower_components/angular-ui-router/release/angular-ui-router.min.js"></script>
    <script src="bower_components/oclazyload/dist/ocLazyLoad.min.js"></script>
    <script src="bower_components/angular-breadcrumb/dist/angular-breadcrumb.min.js"></script>
    <script src="bower_components/angular-loading-bar/build/loading-bar.min.js"></script>
    
    
    <!-- Angular chosen -->
    <script src="./node_modules/chosen-js/chosen.jquery.js"></script>
    <script src="./node_modules/angular-chosen-localytics/dist/angular-chosen.min.js"></script>

    <!-- AngularJS CoreUI App scripts -->

    <script src="js/app.js"></script>

    <script src="js/routes.js"></script>

    <script src="js/demo/routes.js"></script>

    <script src="js/controllers.js"></script>
    <script src="js/directives.js"></script>
    
    
    
    <!--Pop-eye-->
    <script src="node_modules/angular-popeye/release/popeye.js"></script>
    
    <!--Sanitize-->
    <script src="node_modules/angular-sanitize/angular-sanitize.min.js"></script>
    
    <!--UI-Select-->
    <script src="node_modules/ui-select/dist/select.min.js"></script>

    <!--Excel-export-->
    <script src="node_modules/alasql/alasql.min.js"></script>
    <script src="node_modules/xlsx/xlsx.core.min.js"></script>
    
    <!--Charts-->
    <script src="node_modules/chart.js/dist/Chart.min.js"></script>
    <script src="node_modules/angular-chart.js/dist/angular-chart.min.js"></script>

    <!--ui-Bootstrap-->
    <script src="node_modules/angular-ui-bootstrap/dist/ui-bootstrap-tpls.js"></script>



<!--MainMenu-->
<script type="text/javascript" src="js/controllers/mainMenuCtrl.js"></script>
<script type="text/javascript" src="js/controllers/mainMenuData.js"></script>
<!--Import-->
<script type="text/javascript" src="js/controllers/ImportCtrl.js"></script>
<script type="text/javascript" src="js/controllers/ImportData.js"></script>
<!--Groups-->
<script type="text/javascript" src="js/controllers/GroupsCtrl.js"></script>
<script type="text/javascript" src="js/controllers/GroupsData.js"></script>
<!--Cities-->
<script type="text/javascript" src="js/controllers/CitiesCtrl.js"></script>
<script type="text/javascript" src="js/controllers/CitiesData.js"></script>
<!--Votes-->
<script type="text/javascript" src="js/controllers/VotesCtrl.js"></script>
<script type="text/javascript" src="js/controllers/VotesData.js"></script>
<!--Login-->
<script type="text/javascript" src="js/controllers/loginCtrl.js"></script>
<script type="text/javascript" src="js/controllers/loginData.js"></script>


</body>

</html>
