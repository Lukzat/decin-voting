/** Příklad továrničky pro komunikaci s modelem */
angular.module('app').factory('loginData', ['$http', function ($http) {

    /** Cesta ke složce s PHP skripty. */
    var serviceBase = 'js/scripts/login/';

    var obj = {};



/**
 * Ověření přihlašovacích údajů a případné přihlášení.
 * @param {type} Přihlašovací údaje
 * @returns {unresolved}
 */
    obj.verify = function (info) {
        return $http.post(serviceBase + 'verify.php',info).then(function (results) {
            return results.data;
        });
    };
    
    
    return obj;
}]);
