/** Příklad továrničky pro komunikaci s modelem */
angular.module('app').factory('VotesData', ['$http', function ($http) {

    /** Cesta ke složce s PHP skripty. */
    var serviceBase = 'js/scripts/Votes/';

    var obj = {};

    obj.getVotesBySessionId = function (sessionId) {
        return $http.post(serviceBase + 'getVotesBySessionId.php',sessionId).then(function (results) {
            return results.data;
        });
    };
    
    return obj;
}]);
