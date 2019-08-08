angular
.module('app')
.config(['$stateProvider', '$urlRouterProvider', '$ocLazyLoadProvider', function($stateProvider, $urlRouterProvider, $ocLazyLoadProvider) {

  $urlRouterProvider.otherwise('/Menu');

  $ocLazyLoadProvider.config({
    // Set to true if you want to see what and when is dynamically loaded
    debug: true
  });

//  $breadcrumbProvider.setOptions({
//    prefixStateName: 'app.carsTab',
//    includeAbstract: true,
//    template: '<li class="breadcrumb-item" ng-repeat="step in steps" ng-class="{active: $last}" ng-switch="$last || !!step.abstract"><a ng-switch-when="false" href="{{step.ncyBreadcrumbLink}}">{{step.ncyBreadcrumbLabel}}</a><span ng-switch-when="true">{{step.ncyBreadcrumbLabel}}</span></li>'
//  });

  $stateProvider
  .state('app', {
    abstract: true,
    templateUrl: 'views/common/layouts/full.html',
    //page title goes here
//    ncyBreadcrumb: {
//      label: 'Root',
//      skip: true
//    },
    resolve: {
      loadCSS: ['$ocLazyLoad', function($ocLazyLoad) {
        // you can lazy load CSS files
        return $ocLazyLoad.load([{
          serie: true,
          name: 'Font Awesome',
          files: ['css/font-awesome.min.css']
        },{
          serie: true,
          name: 'Simple Line Icons',
          files: ['css/simple-line-icons.css']
        }]);
      }],
      loadPlugin: ['$ocLazyLoad', function ($ocLazyLoad) {
        // you can lazy load files for an existing module
        return $ocLazyLoad.load([{
          serie: true,
          name: 'chart.js',
          files: [
            'bower_components/chart.js/dist/Chart.min.js',
            'bower_components/angular-chart.js/dist/angular-chart.min.js'
          ]
        }]);
      }]
    }
  })
  
  .state('appSimple', {
    abstract: true,
    templateUrl: 'views/common/layouts/simple.html',
    resolve: {
      loadPlugin: ['$ocLazyLoad', function ($ocLazyLoad) {
        // you can lazy load files for an existing module
        return $ocLazyLoad.load([{
          serie: true,
          name: 'Font Awesome',
          files: ['css/font-awesome.min.css']
        },{
          serie: true,
          name: 'Simple Line Icons',
          files: ['css/simple-line-icons.css']
        }]);
      }]
    }
  })

  // Additional Pages
  .state('appSimple.login', {
    url: '/Administrace',
    templateUrl: 'views/pages/login.html',
    controller: 'loginCtrl'
  })
  .state('app.Cities', {
    url: '/Cities',
    templateUrl: 'views/Cities.html',
    controller: 'CitiesCtrl'
  })
  .state('app.Groups/:cityId', {
    url: '/Groups/:cityId',
    templateUrl: 'views/Groups.html',
    controller: 'GroupsCtrl'
  })
  .state('app.Detail/:cityId/:sitId', {
    url: '/Detail/:cityId/:sitId',
    templateUrl: 'views/Import.html',
    controller: 'ImportCtrl'
  })
  .state('app.Votes/:cityId/:sitId/:sesId', {
    url: '/Votes/:cityId/:sitId/:sesId',
    templateUrl: 'views/votes.html',
    controller: 'VotesCtrl'
  })
  .state('appSimple.Menu/:city', {
    url: '/Menu',
    templateUrl: 'views/main.html',
    controller: 'mainMenuCtrl'
  });
}]);
