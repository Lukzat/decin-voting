/** Příklad továrničky pro komunikaci s modelem */
angular.module('app').factory('GroupsData', ['$http', function ($http) {

    /** Cesta ke složce s PHP skripty. */
    var serviceBase = 'js/scripts/Groups/';

    var obj = {};


    /**
     * 
     * @returns {array} Seznam zastupitelstev.
     */
    obj.getSittingsByCityId = function (x) {
        return $http.post(serviceBase + 'getSittingsByCityId.php',x).then(function (results) {
            return results.data;
        });
    };
    
    obj.addSitting = function (sittingData) {
        return $http.post(serviceBase + 'addSitting.php',sittingData).then(function (results) {
            return results.data;
        });
    };
    
    obj.testNewDataOnAPI = function () {
        return $http.post(serviceBase + 'testNewDataOnAPI.php').then(function (results) {
            return results.data;
        });
    };
    
    obj.processTxtFile = function (x) {
        return $http({
            url: 'js/scripts/Groups/processCSVFile.php',
            method: 'POST',
            data: x,
            headers: {'Content-Type': undefined},
            transformRequest: angular.identity
        }).then(function(results){
            return results.data;
        });
    };
    
    obj.processCSVFileNew = function (url) {
        return $http.post(serviceBase + 'processCSVFileNew.php',url).then(function (results) {
            return results.data;
        });
    };
    
    return obj;
}]);
