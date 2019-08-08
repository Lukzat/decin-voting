/** Kontroler pro přihlašovací stránku. */
angular.module('app').controller('VotesCtrl', ['$stateParams', '$rootScope', '$location', '$scope','VotesData', function ($stateParams, $rootScope, $location, $scope,VotesData) {
    
        
        
    $scope.votesArray = [];
    $scope.cityId = $stateParams.cityId;
    $scope.sittingId = $stateParams.sitId;
    $scope.sessionId = $stateParams.sesId;
    
    
    if($rootScope.logCheck === false)
    {
        $location.path('/Reload');
    }

    $scope.getVotes = function(){
        VotesData.getVotesBySessionId($scope.sessionId).then(function(result){
            $scope.votesArray=result.slice();
        });
    };
    $scope.getVotes();
}]);

