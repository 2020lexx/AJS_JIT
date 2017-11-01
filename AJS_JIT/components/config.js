/*       
*  PB_JIT -- config.js
*                                                       
* Author:  @pablo                           
*                                                       
* Purpose:  angular config file        
*                                                       
* Version:  1.0.0                                             
*            
*/ 
function config($stateProvider, $urlRouterProvider, $ocLazyLoadProvider) {
    
  // Configure Idle settings
//    IdleProvider.idle(5); // in seconds
 //   IdleProvider.timeout(120); // in seconds

    $urlRouterProvider.otherwise("/index/TestPage");

    $ocLazyLoadProvider.config({
        // Set to true if you want to see what and when is dynamically loaded
        debug: false
    });

    $stateProvider

        .state('index', {
            abstract: true,
            url: "/index",
            templateUrl: "components/common/P_content.html",
        })
         .state('index.TestPage', {
            url: "/TestPage",
            templateUrl: "test/TestPage.html",
            controller: "TestPageCtrl",
         })
           .state('index.QueueDebugPage', {
            url: "/QueueDebugPage",
            templateUrl: "test/QueueDebugPage.html",
            controller: "QueueDebugCtrl",
         })
         .state('index.All_Q', {
            url: "/All_Q",
            templateUrl: "components/queues/All_Q.html",
            data: { pageTitle: 'Queues View' }, 
        })
        .state('index.Brief_Q', {
            url: "/Brief_Q",
            templateUrl: "components/queues_brief/Brief_Q.html",
            data: { pageTitle: 'Brief Queues View' },
        })
  
        .state('index.Q1_Details', {
            url: "/Q1_Details",
            templateUrl: "components/queues/Q1.html",
            data: { pageTitle: 'Incoming Order ' },
        })
        .state('index.Q2', {
            url: "/Q2",
            templateUrl: "components/queues/Q2.html",
            data: { pageTitle: 'In Production' },
           
        })
        .state('index.Q3', {
            url: "/Q3",
            templateUrl: "components/queues/Q3.html",
            data: { pageTitle: 'In Delivery' },
        })
       .state('404', {
            url: "/404",
            templateUrl: "components/errors/404.html",
            data: { pageTitle: '404', specialClass: 'gray-bg' }
        })
        .state('500', {
            url: "/500",
            templateUrl: "components/errors/500.html",
            data: { pageTitle: '500', specialClass: 'gray-bg' }
        })
         .state('login', {
            url: "/login",
            templateUrl: "components/login/login.html",
            data: { pageTitle: 'Login', specialClass: 'gray-bg' }
        })
          .state('register', {
            url: "/register",
            templateUrl: "components/login/register.html",
            data: { pageTitle: 'Register', specialClass: 'gray-bg' }
        })
             .state('lockscreen', {
            url: "/lockscreen",
            templateUrl: "components/common/P_lockscreen.html",
            data: { pageTitle: 'Lockscreen', specialClass: 'gray-bg' }
        })
             .state('forgot_password', {
            url: "/forgot_password",
            templateUrl: "components/login/forgot_password.html",
            data: { pageTitle: 'Forgot password', specialClass: 'gray-bg' }
        })
     
}
angular
    .module('PB_jit')
    .config(config)
    .config(['toastyConfigProvider', function(toastyConfigProvider) { // toasty config
        toastyConfigProvider.setConfig({
        position: 'top-right',
        theme: 'bootstrap'
        });
    }])
    .run(function($rootScope, $state) {
        $rootScope.$state = $state;
    });
