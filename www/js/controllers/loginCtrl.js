/** Kontroler pro přihlašovací stránku. */
angular.module('app').controller('loginCtrl', ['$rootScope', '$location', '$scope','loginData', function ($rootScope, $location, $scope,loginData) {
        
        
        $scope.name="";
        $scope.pass="";
        $rootScope.logCheck = false;
        
        
        
        /**
         * Ověření přihlašovacích údajů a případné přihlášení.
         * @returns {shoda s databází}
         */
        $scope.verify=function(){
            loginData.verify({'name':$scope.name,'pass':$scope.pass}).then(function (result) {
            if(result === "Mismatch"){
                
                if (confirm("Zadaná kombinace jména a hesla je nesprávná.")) {
                    $location.path('/Reload');
                }else{
                    $location.path('/Reload');
                    $rootScope.logCheck = false;
                }
            }else{
                $location.path('/Cities');
                $rootScope.logCheck = true;
            }
            });
        };
        
        $scope.enterPress = function(keyEvent) {
          if (keyEvent.which === 13)
            $scope.verify();
        };
        
}]);

