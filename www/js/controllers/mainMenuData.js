/** Příklad továrničky pro komunikaci s modelem */
angular.module('app').factory('mainMenuData', ['$http', function ($http) {

    /** Cesta ke složce s PHP skripty. */
    var serviceBase = 'js/scripts/mainMenu/';

    var obj = {};
    
    
    /**
     * 
     * @returns {unresolved} Vrací možnosti selectu pro podrobné vyhledávání.
     */
    obj.getCompleteNewDataVariableParams = function (data) {
        return $http.post(serviceBase + 'getCompleteNewDataVariableParams.php',data).then(function (results) {
            return results.data;
        });
    };
    
    /**
     * 
     * @returns {unresolved} Vrací session pro dané sessionId.
     */
    obj.getsessionById = function (sessionId) {
        return $http.post(serviceBase + 'getsessionById.php',sessionId).then(function (results) {
            return results.data;
        });
    };
    
    obj.exportTableArrayToCSV = function (tableArray) {
        return $http.post(serviceBase + 'exportTableArrayToCSV.php',tableArray).then(function (results) {
            return results.data;
        });
    };
    
    obj.getSideNewestSessionsByType = function (typeSearchPart) {
        return $http.post(serviceBase + 'getSideNewestSessionsByType.php',typeSearchPart).then(function (results) {
            return results.data;
        });
    };
    
    obj.getSideNewestSessions = function () {
        return $http.post(serviceBase + 'getSideNewestSessions.php').then(function (results) {
            return results.data;
        });
    };
    
    obj.getAvaibleNameAndSurname = function (SessionAndGroupsData) {
        return $http.post(serviceBase + 'getAvaibleNameAndSurname.php',SessionAndGroupsData).then(function (results) {
            return results.data;
        });
    };
    
    obj.getAvaiblePoliticalGroups = function (types) {
        return $http.post(serviceBase + 'getAvaiblePoliticalGroups.php',types).then(function (results) {
            return results.data;
        });
    };
    
    obj.getNumberOfProcessesFiles = function () {
        return $http.post(serviceBase + 'getNumberOfProcessesFiles.php').then(function (results) {
            return results.data;
        });
    };
    
    obj.getAvaibleMonths = function (sittingIds) {
        return $http.post(serviceBase + 'getAvaibleMonths.php',sittingIds).then(function (results) {
            return results.data;
        });
    };
    
    /**
     * 
     * @returns {unresolved} Vrací výsledky hledání pro quicksearch.
     */
    obj.quickSearch = function (word) {
        return $http.post(serviceBase + 'quickSearch.php',word).then(function (results) {
            return results.data;
        });
    };
    
    obj.getPersonInformation = function (Params) {
        return $http.post(serviceBase + 'getOnePersonInformation.php',Params).then(function (results) {
            return results.data;
        });
    };
    
    obj.getOneResult = function (Params) {
        return $http.post(serviceBase + 'getOneResult.php',Params).then(function (results) {
            return results.data;
        });
    };
    
    obj.getMultiParamSearchResults = function (Params) {
        return $http.post(serviceBase + 'getMultiParamSearchResults.php',Params).then(function (results) {
            return results.data;
        });
    };
    
    /**
     * 
     * @returns {unresolved} Vrací seznam lidí pro daná sezení.
     */
    obj.getPersonsBySessions = function (sessions) {
        return $http.post(serviceBase + 'getPersonsBySessions.php',sessions).then(function (results) {
            return results.data;
        });
    };
    
    /**
     * 
     * @returns {unresolved} Vrací seznam sezení pro daná zastupitelstva.
     */
    obj.getSessionsBySittings = function (sittings) {
        return $http.post(serviceBase + 'getSessionsBySittings.php',sittings).then(function (results) {
            return results.data;
        });
    };
    
    /**
     * 
     * @returns {unresolved} Vrací seznam zastupitelstev pro dané město.
     */
    obj.getZastupitelstvaByCityName = function (cityName) {
        return $http.post(serviceBase + 'getZastupitelstvaByCityName.php',cityName).then(function (results) {
            return results.data;
        });
    };
    
    /**
     * 
     * @returns {unresolved} Vrací seznam volebních sezení z databáze.
     */
    obj.getSessions = function () {
        return $http.get(serviceBase + 'getSessions.php').then(function (results) {
            return results.data;
        });
    };
    
    /**
     * 
     * @param {type} sessionIds
     * @returns {unresolved} Vrací seznam volebních rozhodnutí z databáze.
     */
    obj.getVotesBySessions = function (sessionIds) {
        return $http.post(serviceBase + 'getVotesBySessions.php',sessionIds).then(function (results) {
            return results.data;
        });
    };
    
    /**
     * 
     * @param {type} sessionIds
     * @returns {unresolved} Vrací data pro zobrazení v tabulce.
     */
    obj.getVotesByPersonNames = function (names) {
        return $http.post(serviceBase + 'getVotesByPersonNames.php',names).then(function (results) {
            return results.data;
        });
    };
    
    
    return obj;
}]);
