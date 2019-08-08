/** Příklad továrničky pro komunikaci s modelem */
angular.module('app').factory('CitiesData', ['$http', function ($http) {

    /** Cesta ke složce s PHP skripty. */
    var serviceBase = 'js/scripts/Cities/';

    var obj = {};


    /**
     * 
     * @returns {array} Seznam měst.
     */
    obj.getCities = function () {
        return $http.post(serviceBase + 'getCities.php').then(function (results) {
            return results.data;
        });
    };
    
    obj.addCity = function (cityName) {
        return $http.post(serviceBase + 'addCity.php',cityName).then(function (results) {
            return results.data;
        });
    };
    
    obj.editCity = function (cityInfo) {
        return $http.post(serviceBase + 'editCity.php',cityInfo).then(function (results) {
            return results.data;
        });
    };
    
    obj.removeCity = function (cityId) {
        return $http.post(serviceBase + 'removeCity.php',cityId).then(function (results) {
            return results.data;
        });
    };
    
    return obj;
}]);
