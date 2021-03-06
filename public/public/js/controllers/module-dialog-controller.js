(function(app) {
	app.controller('ModuleDialogController',
    ['$scope','$mdDialog','module','parentNode','gateway', 'json','client','GatewayService','MessageUtil',
    function ($scope, $mdDialog, module, parentNode, gateway, json,client, GatewayService, MessageUtil) {
  
        $scope.module = {};
       
        $scope.promise = null;
         $scope.cancel = function() {
           $mdDialog.cancel();
        };

        if(!module){
          $scope.module.PKY = "";
          $scope.module.NAME = "";
          $scope.module.DATE = new Date();
          $scope.module.ENDPOINTS = [];
        } else {
          $scope.module = angular.copy(module);
        }

        console.log("Module", module);
        console.log("ParenNode", parentNode);


        $scope.save = function(){

          var json_to_send =  GatewayService.buildJsonByNewModule(json, parentNode.source, $scope.module);
         
          var params = {
            json: JSON.stringify(json_to_send)
          };

          if(client && client.id){
            params.client_number = client.sap_number;
          }
           console.log("params"+params);

            $scope.promise = GatewayService.execute_changes(gateway.id, params);
            
            $scope.promise.then(
                function(result){
                  console.log("result",result);
                  $mdDialog.hide(result);
                  },
                function(error){
                  console.log("error",error);
                  MessageUtil.showError(error.data.message);
                }
              );
         
        };
        
    }]);
})(meister);