var meister = angular.module('meister', ['ngMaterial', 'ngAnimate','ngSanitize', 'ngMessages',
 'ngAria', 'ui.router','ngCookies','md.data.table','chart.js','TreeWidget','angular-oauth2',
 'ng.jsoneditor','angular-loading-bar','ui.ace','ang-drag-drop']);

meister.constant('SERVER_BASE_URL', "./");
meister.constant('CLIENT_SECRET_KEY',"GzkU62Ruwo29riFgJHVDPw377k8hYu5dXXYxgYSR");
meister.constant('SYSTEM_ADMIN',99);
meister.constant('SYSTEM_INTEGRATOR',89);
meister.constant('CLIENT_ADMIN',49);
meister.constant('CLIENT_USER',29);
meister.constant('COOKIE_LAST_REQUEST','meister-sdk-last-request');
meister.constant('COOKIE_MAX_TIMEOUT_REQUEST',60); //in minutes

(function(app) {
    app.config(['$stateProvider','$compileProvider', '$mdDateLocaleProvider', '$urlRouterProvider','OAuthProvider',
      'OAuthTokenProvider','SERVER_BASE_URL','CLIENT_SECRET_KEY', 'cfpLoadingBarProvider',
        function($stateProvider, $compileProvider, $mdDateLocaleProvider, $urlRouterProvider, OAuthProvider, OAuthTokenProvider,
          SERVER_BASE_URL, CLIENT_SECRET_KEY,cfpLoadingBarProvider) {

          $mdDateLocaleProvider.formatDate = function(date) {
         return moment(date).format('YYYY-MM-DD');
        };

        cfpLoadingBarProvider.includeSpinner=false;
      
      $compileProvider.preAssignBindingsEnabled(true);
          
        OAuthTokenProvider.configure({
            name: 'meister-sdk-token',
              options: {
                secure: false
              }
        });
        
        OAuthProvider.configure({
              baseUrl: SERVER_BASE_URL,
              clientId: '2',
              clientSecret: CLIENT_SECRET_KEY, // optional
              grantPath: '/api/login',
              revokePath: '/api/logout'
        });

        $urlRouterProvider.otherwise('/home');

        $stateProvider.state('login', {
		url: '/login',
		templateUrl: 'partials/login-partial.html',
		controller: 'LoginController'
	})

	.state('home', {
            url: '/home',
            templateUrl: 'partials/home-partial.html',
            controller: 'HomeController'
        })
        .state('clients', {
            url: '/clients',
            templateUrl: 'partials/clients-partial.html',
            controller: 'ClientController'
        })
        .state('users', {
            url: '/users',
            templateUrl: 'partials/users-partial.html',
            controller: 'UserController'
        })
        .state('edit-profile', {
            url: '/edit-profile',
            templateUrl: 'partials/edit-profile-partial.html',
            controller: 'EditProfileController'
        })
        .state('gateways', {
            url: '/gateways',
            templateUrl: 'partials/client-gateways-partial.html',
            controller: 'ClientGatewayController'
        })
        .state('requests-logs', {
            url: '/requestlogs',
            templateUrl: 'partials/request-logs-partial.html',
            controller: 'RequestLogsController'
        });;

    }]).run(['$rootScope', '$location','$mdToast','OAuth', 
    function ($rootScope, $location,$mdToast,OAuth) {

      $rootScope.OAuth = OAuth;
        
        $rootScope.$on('$locationChangeStart', function (event, next, current) {
            // redirect to login page if not logged in
            console.log("locationChangeStart--->");
            //$cookies.put(COOKIE_LAST_REQUEST,Date.now());
            if ($location.path() !== '/login' && !OAuth.isAuthenticated()) {
                $location.path('/login');
            }
        });

        $rootScope.$on('oauth:error', function(event, rejection) {
          console.log("oauth:error--->");
          // Ignore `invalid_grant` error - should be catched on `LoginController`.
          if ('invalid_grant' === rejection.data.error) {
            return;
          }

          // Refresh token when a `invalid_token` error occurs.
          if ('invalid_token' === rejection.data.error) {
            return OAuth.getRefreshToken();
          }

          $mdToast.show(
                          $mdToast.simple()
                            .textContent(rejection.data.error)
                            .position('top' )
                            .highlightClass('md-warn')
                            .hideDelay(3000)
                        );

          // Redirect to `/login` with the `error_reason`.
          return $location.path('/login');
        });
    }]);

    app.factory('httpResponseInterceptor', ['$q', '$timeout','$rootScope', '$location','$cookies',
      'COOKIE_LAST_REQUEST','COOKIE_MAX_TIMEOUT_REQUEST',
      function($q, $timeout, $rootScope, $location, $cookies, COOKIE_LAST_REQUEST, COOKIE_MAX_TIMEOUT_REQUEST) {
        return {
            request: function(config) {
              var last_request = $cookies.get(COOKIE_LAST_REQUEST);
              if(last_request){
                var current_time = Date.now();
                var difference = current_time - last_request;
                var resultInMinutes = Math.round(difference / 60000);
                console.log("resultInMinutes",resultInMinutes);
                if(resultInMinutes>COOKIE_MAX_TIMEOUT_REQUEST){
                  console.log("Revoke token authentication...");
                   $rootScope.logout();
                } 
              } 
              
              $cookies.put(COOKIE_LAST_REQUEST,Date.now());  
              //console.log("request interceptor---->",config);
              var deferred = $q.defer();
              deferred.resolve(config);
              return deferred.promise;
            },
            responseError: function(rejection) {
              //console.log("rejection interceptor------------------>");
                if (rejection.status === 401) {
                    // Something like below:
                    console.log("401 ERROR");
                    $cookies.remove('meister-sdk-token');
                    $location.path('/login');
                }
                return $q.reject(rejection);
            }
        };
    }]);

    app.config(function($httpProvider) {
      $httpProvider.interceptors.push('httpResponseInterceptor');
  });

    app.config(function (blockUIConfig) {
        var template = '  <div class="block-ui-overlay"></div>' +
                       '    <div class="block-ui-message-container">' +
                       '     <div class="block-ui-message">' +
                       '       <img src="./public/images/loading.gif"  width="64px">' +
                       '     </div>' +
                       '  </div>';
      blockUIConfig.template = template;
    });
})(meister);
