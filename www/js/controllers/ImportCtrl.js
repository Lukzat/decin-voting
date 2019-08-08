/** Kontroler pro přihlašovací stránku. */
angular.module('app').controller('ImportCtrl', ['$stateParams', '$rootScope', '$location', '$scope','ImportData', function ($stateParams, $rootScope, $location, $scope,ImportData) {
    
        
        
    $scope.sessionsArray = [];
    $scope.cityId = $stateParams.cityId;
    $scope.sittingId = $stateParams.sitId;

    
    
    if($rootScope.logCheck === false)
    {
        $location.path('/Reload');
    }
    
    
    $scope.getSessions = function(){
        ImportData.getSessionsBySittingId($scope.sittingId).then(function(result){
            $scope.sessionsArray=result.slice();
        });
    };
    $scope.getSessions();
}]);

