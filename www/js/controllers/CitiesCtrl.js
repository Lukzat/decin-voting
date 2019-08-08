/** Kontroler pro přihlašovací stránku. */
angular.module('app').controller('CitiesCtrl', ['$uibModal', '$rootScope', '$location', '$scope','CitiesData', function ($uibModal, $rootScope, $location, $scope,CitiesData) {

    $scope.CitiesArray = [];


    
    
    if($rootScope.logCheck === false)
    {
        $location.path('/Reload');
    }
    
    
    var getCities = function(){
        CitiesData.getCities().then(function(result){
            $scope.CitiesArray=result.slice();
        });
    };


    $scope.getCities = function(){
        CitiesData.getCities().then(function(result){
            $scope.CitiesArray=result.slice();
        });
    };
    
    $scope.addCity = function () {
        $uibModal.open({
                templateUrl: 'views/citySaveModal.html',
                backdrop: true,
                windowClass: 'modal',
                controller: function ($scope, $uibModalInstance) {
                    $scope.cityName = "";
                    $scope.go = function () {
                        CitiesData.addCity($scope.cityName).then(function (result){
                            $uibModalInstance.dismiss('close');
                            getCities();
                        },function(){

                        });
                    };
                    
                    $scope.close = function () {
                        $uibModalInstance.dismiss('close');
                    };
                }
            });
    };
    
    $scope.editCity = function (id, name) {
        $uibModal.open({
                templateUrl: 'views/citySaveModal.html',
                backdrop: true,
                windowClass: 'modal',
                controller: function ($scope, $uibModalInstance) {
                    $scope.cityName = name;
                    $scope.go = function () {
                        CitiesData.editCity({id:id,name:$scope.cityName}).then(function (result){
                            $uibModalInstance.dismiss('close');
                            getCities();
                        },function(){

                        });
                    };
                    
                    $scope.close = function () {
                        $uibModalInstance.dismiss('close');
                    };
                }
            });
    };
    
    $scope.removeCity = function (id) {
        $uibModal.open({
                templateUrl: 'views/deleteTreeModal.html',
                backdrop: true,
                windowClass: 'modal',
                controller: function ($scope, $uibModalInstance) {
                    $scope.go = function () {
                        CitiesData.removeCity(id).then(function (result){
                            $uibModalInstance.dismiss('close');
                            getCities();
                        },function(){

                        });
                    };
                    
                    $scope.close = function () {
                        $uibModalInstance.dismiss('close');
                    };
                }
            });
    };
    
    
    
    $scope.getCities();
}]);

