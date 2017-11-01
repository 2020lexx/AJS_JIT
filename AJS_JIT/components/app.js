/*       
*  PB_JIT -- app.js
*                                                       
* Author:  @pablo                           
*                                                       
* Purpose:  init angular app         
*                                                       
* Version:  1.0.0                                             
*            
*/ 

(function () {
    angular.module('PB_jit', [
        'ui.router',                    // Routing
        'oc.lazyLoad',                  // ocLazyLoad
        'ui.bootstrap',                 // Ui Bootstrap
        'pascalprecht.translate',       // Angular Translate
        'angularModalService',
        'ngMap',
        'angular-toasty',
        'ds.clock',
        'iso.directives',
       
        //  'leaflet-directive',
    ])
})();

