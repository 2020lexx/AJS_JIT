/*       
*  PB_JIT -- D_main-directives.js
*                                                       
* Author:  @pablo                           
*                                                       
* Purpose:  common main directives 
*                                                       
* Version:  1.0.0                                             
*            
*/ 

// customers on checkout
function counterCustomersCheckout(){
    return {
        restrict: 'E',
        replace: true,
        template:'<i class="fa fa-shopping-cart" tooltip-placement="bottom" uib-tooltip="customers on checkout"> <span class="label label-warning" >{{counter-CusCheckout}}</span></i>',
 
        }
}

 
//sideNavigation - Directive for run metsiMenu on sidebar navigation
function sideNavigation($timeout) {
    return {
        restrict: 'A',
        link: function(scope, element) {
            // Call the metsiMenu plugin and plug it to sidebar navigation
            $timeout(function(){
                element.metisMenu();
            });
        }
    };
}
 
// pageTitle - Directive for set Page title - mata title
function pageTitle($rootScope, $timeout) {
    return {
        link: function(scope, element) {
            var listener = function(event, toState, toParams, fromState, fromParams) {
                // Default title - load on Dashboard 1
                var title = 'ZX JIT_Delivery';
                // Create your own title pattern
                if (toState.data && toState.data.pageTitle) title = 'ZX JIT_Delivery | ' + toState.data.pageTitle;
                $timeout(function() {
                    element.text(title);
                });
            };
            $rootScope.$on('$stateChangeStart', listener);
        }
    }
}
// minimalizaSidebar - Directive for minimalize sidebar
function minimalizaSidebar($timeout) {
    return {
        restrict: 'A',
        template: '<a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="" ng-click="minimalize()"><i class="fa fa-bars"></i></a>',
        controller: function ($scope, $element) {
            $scope.minimalize = function () {
                $("body").toggleClass("mini-navbar");
                if (!$('body').hasClass('mini-navbar') || $('body').hasClass('body-small')) {
                    // Hide menu in order to smoothly turn on when maximize menu
                    $('#side-menu').hide();
                    // For smoothly turn on menu
                    setTimeout(
                        function () {
                            $('#side-menu').fadeIn(400);
                        }, 200);
                } else if ($('body').hasClass('fixed-sidebar')){
                    $('#side-menu').hide();
                    setTimeout(
                        function () {
                            $('#side-menu').fadeIn(400);
                        }, 100);
                } else {
                    // Remove all inline style from jquery fadeIn function to reset menu state
                    $('#side-menu').removeAttr('style');
                }
            }
        }
    };
}


angular
    .module('PB_jit')
    .directive('sideNavigation', sideNavigation) 
    .directive('counterCustomersCheckout',counterCustomersCheckout)
   .directive('pageTitle', pageTitle)
    .directive('minimalizaSidebar', minimalizaSidebar)
  