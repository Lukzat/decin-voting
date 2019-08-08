/** Kontroler pro domocskou stránku. */
angular.module('app').controller('mainMenuCtrl', ['$uibModal','$sce', '$rootScope','$scope','mainMenuData','$stateParams','$location', function ($uibModal, $sce, $rootScope, $scope,mainMenuData,$stateParams,$location) {
        
        
        $stateParams.city = "Děčín";
        $scope.Sitting = [];
        $scope.Person = [];
        $scope.MonthsAvailable = [];
        $scope.Months = [
            {"name":"Leden","value":1},
            {"name":"Únor","value":2},
            {"name":"Březen","value":3},
            {"name":"Duben","value":4},
            {"name":"Květen","value":5},
            {"name":"Červen","value":6},
            {"name":"Červenec","value":7},
            {"name":"Srpen","value":8},
            {"name":"Září","value":9},
            {"name":"Říjen","value":10},
            {"name":"Listopad","value":11},
            {"name":"Prosinec","value":12}];
        $scope.SittingsArray = [];
        $scope.SessionsArray = [];
        $scope.PersonsArray = [];
        $scope.PersonsDataArray = [];
        $scope.NameAndSurname = [];
        $scope.Names = [];
        $scope.uniqueSessionNames = [];
        $scope.SubstituteForPerson = [];
        $scope.tableArray = [];
        $scope.Sitting = [];
        $scope.Session = [];
        $scope.ShowNavButtons = true;
        $scope.ShowContentSearch = false;
        $scope.showExportButton = false;
        $scope.currentCity = "";
        $scope.ShowAdvancedSearch = true;
        $scope.quickSearchText = "";
        $scope.quickSearchResultShow = false;
        $scope.labels = ["Ano", "Ne", "Zdržel se", "Omluven", "Nehlasoval"];
        $scope.colorsPie = ['#4CAF4F', '#F54235', '#9E9E9E', '#795548', '#000000'];
        $scope.isMobile = false;
        $scope.isDesktop = false;
        $scope.showSearchBarTop = false;
        $scope.showInitialGraphData = true;
        $scope.bodyProgramuDisabled = true;
        $scope.politickeStranyDisabled = true;
        $scope.jmenoAPrijmeniDisabled = true;
        $scope.showExportButton = false;
        
        var mobileTest = function(a){
            if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))){
                $scope.isMobile = true;
                $scope.showSearchBarTop = true;
            }else{
                $scope.isDesktop = true;
            }
        };
        
        $scope.reloadPage = function(){
            location.reload();
        };
        
        $scope.exportTableArrayToCSV = function(){
            mainMenuData.exportTableArrayToCSV($scope.tableArray).then(function(result){
                url = location.origin + location.pathname + "/CSVHolder/" + result;
                var link = document.createElement("a");
                link.download = result;
                link.href = url;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                delete link;
            });
        };
        
        $scope.activateTopSearchBar = function(){
            $scope.showSearchBarTop = true;
        };
        $scope.deactivateTopSearchBar = function(){
            $scope.showSearchBarTop = false;
        };
        
        mobileTest(navigator.userAgent||navigator.vendor||window.opera);
        
        if($stateParams.city !== ""){
            $scope.currentCity = $stateParams.city;
            $scope.ShowContentSearch = true;
            $scope.ShowNavButtons = false;
            mainMenuData.getZastupitelstvaByCityName($stateParams.city).then(function(result){
                $scope.SittingsArray=result;
            });
        }
        
        angular.element('body').toggleClass('sidebar-hidden');
        
        $scope.sideNewSittingClicker = function(sittingId){
            $scope.Sitting = [sittingId];
            $scope.MonthsAvailable = [];
            $scope.monthChosen = [];
            $scope.Session = [];
            $scope.pGroups = [];
            $scope.NameAndSurname = [];
            $scope.uniqueSessionNames = [];
            $scope.uniqueSessionPoints = [];
            $scope.searchButtonParamClicked();
        };
        
        $scope.getSideNewestSessions = function(){
            mainMenuData.getSideNewestSessions().then(function(result){
                var ses = result[0];
                var key = Object.keys(ses)[0];
                $scope.latestDate = ses[key].date;
                $scope.newSideSessions=ses;
                $scope.newSittings = result[1];
                $scope.numberOfProcessedFiles = result[2];
                $scope.sittingsLabels = result[3];
                $scope.hlasovaloResults = result[4];
                $scope.nehlasovaloResults = result[5];
                new Chart(document.getElementById("bar-chart-grouped"), {
                    type: 'bar',
                    data: {
                      labels: $scope.sittingsLabels,
                      datasets: [
                        {
                          label: "Neschváleno",
                          backgroundColor: "#000000",
                          data: $scope.nehlasovaloResults
                        }, {
                          label: "Schváleno",
                          backgroundColor: "#4CAF4F",
                          data: $scope.hlasovaloResults
                        }
                      ]
                    },
                    options: {
                      title: {
                        display: true,
                        text: 'Přehled schválených/neschválených bodů hlasování'
                      }
                    }
                });
            });
        };
        $scope.getSideNewestSessions();
        
        $scope.enterPress = function(keyEvent) {
            $scope.showExportButton = false;
            $scope.tableArray = [];
            $scope.quickSearchSessionsResultArray = [];
            if (keyEvent.which === 13){
                $scope.ShowAdvancedSearch = true;
                $scope.quickSearchResultArray = [];
                $scope.Sitting = [];
                $scope.Session = [];
                $scope.Person = [];
                $scope.quickSearchGo();
                $scope.sittingValueChanged();
            }
        };
        
        
        $scope.openPersonDetailModal = function (persName) {
            $uibModal.open({
                    templateUrl: 'views/personDetailModal.html',
                    backdrop: true,
                    windowClass: 'modal',
                    controller: function ($scope,$uibModalInstance,$sce,$rootScope,$stateParams,$location) {
                        $scope.personName = persName;
                        $scope.labels2 = ["Ano", "Ne", "Zdržel se", "Omluven", "Nehlasoval"];
                        $scope.colorsPie2 = ['#4CAF4F', '#F54235', '#9E9E9E', '#795548', '#000000'];
                        var sendPersonNameDetail =  {"name":$scope.personName,
                                                    "aditional":"Adam"};
                        mainMenuData.getPersonInformation(sendPersonNameDetail).then(function (result){
                            $scope.modalViewData = result[2];
                            $scope.voteCluster = result[1];
                            $scope.sessinnsCluster = result[0];
                        });
                        

                        $scope.close = function () {
                            $uibModalInstance.dismiss('close');
                        };
                    }
                });
        };
        
        $scope.openSelectSittingModal = function (SelectData) {
            $uibModal.open({
                    templateUrl: 'views/selectSittingModal.html',
                    backdrop: true,
                    windowClass: 'modal',
                    controller: function ($scope,$uibModalInstance,$sce,$rootScope,$stateParams,$location) {
                        $scope.SelectData = SelectData;
                        
                        $scope.sittingSelected = function(searchName){
                            findNewSide(searchName);
                            $scope.close();
                        };

                        $scope.close = function () {
                            $uibModalInstance.dismiss('close');
                        };
                    }
                });
        };
        
        var findNewSide = function(searchName){
            $scope.newSideSessions = [];
            var sendParamData =  {"searchName":searchName,
                                "plusBit":"chosen"};
            mainMenuData.getSideNewestSessionsByType(sendParamData).then(function (result){
                var test = Object.assign({}, result);
                $scope.newSideSessions = test;
                $scope.latestDate = $scope.newSideSessions[0].date;
            });
        };
        
        $scope.quickSearchGo = function(){
            $scope.showExportButton = false;
            $scope.showInitialGraphData = false;
            $scope.ShowAdvancedSearch = true;
            $scope.quickSearchResultArray = [];
            $scope.Sitting = [];
            $scope.Session = [];
            $scope.Person = [];
            $scope.monthChosen = [];
            if($scope.quickSearchText !== ""){
                $scope.quickSearchResultArray = [];
                $scope.SubstituteForPerson = [];
                mainMenuData.quickSearch({'word':$scope.quickSearchText}).then(function(result){
                    $scope.quickSearchSessionsResultArray = result[0];
                    $scope.quickSearchVotedResultArray = result[1];
//                    for(var key in $scope.quickSearchVotedResultArray){
//                        mainMenuData.getsessionById($scope.quickSearchVotedResultArray[key][0].sessionId).then(function(result){
//                            $scope.SubstituteForPerson.push(result);
//                        });
//                    }
                    $scope.quickSearchResultShow = true;
                });
            }else{
                $scope.Sitting = [];
                $scope.Session = [];
                $scope.Person = [];
                $scope.tableArray = [];
                $scope.quickSearchResultArray = [];
                $scope.quickSearchResultShow = false;
            }
        };
        //$scope.NameAndSurname
        $scope.searchButtonParamClicked = function(){
            $scope.showExportButton = true;
            $scope.showInitialGraphData = false;
            $scope.tableArray = [];
            var filtered = $scope.tableArray.filter(function (el) {
              return el !== null;
            });
            $scope.tableArray = filtered;
            $scope.quickSearchSessionsResultArray = [];
            $scope.quickSearchResultShow = false;
            var sendParamData =  {"sittingTypes":$scope.Sitting,
                                "mesicData":$scope.monthChosen,
                                "bodProgramuData":$scope.Session,
                                "politickaStranaData":$scope.pGroups,
                                "jmenoData":$scope.NameAndSurname};
            mainMenuData.getMultiParamSearchResults(sendParamData).then(function(result){
                //console.log(result);
                //$scope.tableArray.push({ses:result[0],per:result[1]});
                $scope.PersonsArray = result[1];
                for(i=0;i<result[0].length;i++){
                    $scope.tableArray.push({ses:result[0][i],per:result[1][i],char:result[2][i]});
                }
            });
            $scope.showExportButton = true;
        };
        
        
        
        $scope.getOneSearchResult = function(sessinId){
            $scope.showExportButton = true;
            $scope.showInitialGraphData = false;
            $scope.tableArray = [];
            $scope.quickSearchSessionsResultArray = [];
            $scope.quickSearchResultShow = false;
            var sendParamData =  {"sessionId":sessinId,
                                "jmenoData":"Adam"};
            mainMenuData.getOneResult(sendParamData).then(function(result){
                //console.log(result);
                //$scope.tableArray.push({ses:result[0],per:result[1]});
                $scope.PersonsArray = result[1];
                for(i=0;i<result[0].length;i++){
                    $scope.tableArray.push({ses:result[0][i],per:result[1][i],char:result[2][i]});
                }
            });
            $scope.showExportButton = true;
        };
        
        $scope.personValueChanged = function(){
            $scope.tableArray = [];
            //console.log($scope.Person);
            $scope.getPersonsBySessions($scope.Person);
        };
        
        $scope.getPersonsBySessions = function(sessions){
            mainMenuData.getPersonsBySessions(sessions).then(function(result){
                //console.log(result);
                $scope.PersonsArray = result[0];
                for(i=0;i<$scope.Person.length;i++){
                    for(e=0;e<$scope.uniqueSessionPoints.length;e++){
                        if($scope.uniqueSessionPoints[e].id.toString() === $scope.Person[i].toString()){
                            $scope.tableArray.push({ses:$scope.uniqueSessionPoints[e],per:result[0][i],char:result[1][i]});
                        }
                    }
                }
            });
        };
        
        $scope.showAdvancedSearch = function(){
            if($scope.ShowAdvancedSearch === true){
                $scope.ShowAdvancedSearch = true;
                $scope.quickSearchResultShow = false;
                $scope.quickSearchResultArray = [];
            }else{
                $scope.ShowAdvancedSearch = true;
                $scope.quickSearchResultShow = false;
                $scope.quickSearchResultArray = [];
            }
        };
        
        $scope.politickaStranaChanged = function(){
            if($scope.pGroups.length > 0){
                $scope.jmenoAPrijmeniDisabled = false;
            }else{
                $scope.jmenoAPrijmeniDisabled = true;
            }
            $scope.NameAndSurname = [];
            $scope.uniquePeopleNames = [];
            var NameSurSendData = {"session":$scope.Session,
                                    "pGroups":$scope.pGroups};
            mainMenuData.getAvaibleNameAndSurname(NameSurSendData).then(function(result){
                $scope.uniquePeopleNames = result;
            });
        };
        
        $scope.sessionValueChanged = function(){
            if($scope.Session.length > 0){
                $scope.politickeStranyDisabled = false;
            }else{
                $scope.politickeStranyDisabled = true;
            }
            $scope.pGroups = [];
            $scope.NameAndSurname = [];
            $scope.uniqueSessionPoints = [];
            $scope.uniquePoliticalGroups = [];
            for(a=0;a<$scope.Session.length;a++){
                for(i=0;i<$scope.SessionsArray.length;i++){
                    if($scope.SessionsArray[i].type === $scope.Session[a]){
                        $scope.uniqueSessionPoints.push($scope.SessionsArray[i]);
                    }
                }
            }
            mainMenuData.getAvaiblePoliticalGroups($scope.Session).then(function(result){
                $scope.uniquePoliticalGroups = result;
            });
        };
        
        $scope.clearAdvancedSearch = function(){
            $scope.Sitting = [];
            $scope.monthChosen = [];
            $scope.Session = [];
            $scope.pGroups = [];
            $scope.NameAndSurname = [];
            $scope.uniqueSessionNames = [];
            $scope.uniqueSessionPoints = [];
            $scope.bodyProgramuDisabled = true;
            $scope.politickeStranyDisabled = true;
            $scope.jmenoAPrijmeniDisabled = true;
        };
        
        $scope.monthValueChanged = function(){
            $scope.Session = [];
            $scope.pGroups = [];
            $scope.NameAndSurname = [];
            $scope.uniqueSessionNames = [];
            $scope.uniqueSessionPoints = [];
            $scope.getSessionsBySittings($scope.Sitting);
        };
        
        $scope.sittingValueChanged = function(){
            if($scope.Sitting.length > 0){
                $scope.bodyProgramuDisabled = false;
            }else{
                $scope.bodyProgramuDisabled = true;
            }
            $scope.MonthsAvailable = [];
            $scope.monthChosen = [];
            $scope.Session = [];
            $scope.pGroups = [];
            $scope.NameAndSurname = [];
            $scope.uniqueSessionNames = [];
            $scope.uniqueSessionPoints = [];
            $scope.getSessionsBySittings($scope.Sitting);
//            mainMenuData.getAvaibleMonths($scope.Sitting).then(function(result){
//                angular.forEach($scope.Months, function(value, key) {
//                    if(result.includes(value.value)){
//                        $scope.MonthsAvailable.push(value);
//                    }
//                });
//            });
        };
        
        $scope.getSessionsBySittings = function(sittings){
            mainMenuData.getSessionsBySittings(sittings).then(function(result){
                $scope.SessionsArray=result;
                for(i=0;i<$scope.SessionsArray.length;i++){
                    //console.log($scope.SessionsArray[i]);
                    if(!$scope.uniqueSessionNames.includes($scope.SessionsArray[i].type)){
                        $scope.uniqueSessionNames.push($scope.SessionsArray[i].type);
                    }
                }
            });
        };
        
        var getZastupitelstvaByCityName = function(cityName){
            mainMenuData.getZastupitelstvaByCityName(cityName).then(function(result){
                $scope.SittingsArray=result;
            });
        };
        
        $scope.resetSearch = function(){
            $scope.ShowNavButtons = true;
            $scope.ShowContentSearch = false;
        };
        
        $scope.NavButtonClick = function(cityName){
//            $scope.currentCity = cityName;
//            $scope.ShowContentSearch = true;
//            $scope.ShowNavButtons = false;
//            getZastupitelstvaByCityName(cityName);
//            $stateParams.city = cityName;
//            console.log($stateParams.city);
            $location.path('/Menu/'+cityName);
        };
        
        $scope.trustAsHtml = function(string) {
            return $sce.trustAsHtml(string);
        };
        
}]);

