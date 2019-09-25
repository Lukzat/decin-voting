/** Kontroler pro přihlašovací stránku. */
angular.module('app').controller('GroupsCtrl', ['$uibModal','$stateParams','$rootScope', '$location', '$scope','GroupsData', function ($uibModal, $stateParams, $rootScope, $location, $scope,GroupsData) {

    angular.element('body').toggleClass('sidebar-hidden');
    $scope.sittingsArray = [];
    $scope.cityId = $stateParams.cityId;
    $scope.TestValue = true;
    
    
    
    if($rootScope.logCheck === false)
    {
        $location.path('/Reload');
    }
    
    var getSittingsByCityId = function(){
        GroupsData.getSittingsByCityId($scope.cityId).then(function(result){
            $scope.sittingsArray=result.slice();
        });
    };
    
    $scope.testNewDataOnAPI = function(){
        GroupsData.testNewDataOnAPI().then(function(result){
            if(result == "Up to date"){
                $scope.TestValue = true;
            }else{
                $scope.TestValue = false;
                $scope.DownloadableFile = result;
            }
        });
    };
    $scope.testNewDataOnAPI();
    
    $scope.getSittingsByCityId = function(){
        GroupsData.getSittingsByCityId($scope.cityId).then(function(result){
            $scope.sittingsArray=result.slice();
        });
    };
    
    $scope.newCSVFunction = function(id){
        GroupsData.processCSVFileNew({url:$scope.DownloadableFile, id:id}).then(function(result){
            console.log(result);
        });
    };
    
    $scope.picLoad1 = function (id) {
        $('#trueUploadBtn1').click();
        AddImage1 = function (files) {
            var formData = new FormData;
            formData.append('hlas', files[0]);
            formData.append('name', files[0].name);
            formData.append('sittingId', id);
            GroupsData.processTxtFile(formData).then(function(result){
                getSittingsByCityId();
            });
        };
    };
    
    $scope.addSitting = function (cId) {
        $uibModal.open({
                templateUrl: 'views/sittingsSaveModal.html',
                backdrop: true,
                windowClass: 'modal',
                controller: function ($scope, $uibModalInstance) {
                    $scope.sittingName = "";
                    $scope.from = "";
                    $scope.to = "";
                    $scope.go = function () {
                        GroupsData.addSitting({'name':$scope.sittingName,'from':$scope.from,
                        'to':$scope.to,'cityId':cId}).then(function (result){
                            $uibModalInstance.dismiss('close');
                            getSittingsByCityId();
                        },function(){

                        });
                    };
                    
                    $scope.close = function () {
                        $uibModalInstance.dismiss('close');
                    };
                }
            });
    };
    
    
    
    $scope.getSittingsByCityId();
}]);

