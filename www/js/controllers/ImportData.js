/** Příklad továrničky pro komunikaci s modelem */
angular.module('app').factory('ImportData', ['$http', function ($http) {

    /** Cesta ke složce s PHP skripty. */
    var serviceBase = 'js/scripts/Import/';

    var obj = {};


    /**
     * 
     * @returns {array} Seznam zastupitelstev.
     */
    obj.getSessionsBySittingId = function (sittindId) {
        return $http.post(serviceBase + 'getSessionsBySittingId.php',sittindId).then(function (results) {
            return results.data;
        });
    };
    
    return obj;
}]);
