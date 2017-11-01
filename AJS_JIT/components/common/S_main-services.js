/*       
*  PB_JIT -- S_main-services.js
*                                                       
* Author:  @pablo                           
*                                                       
* Purpose:  common main services   
*                                                       
* Version:  1.0.0                                             
*            
*/ 
// TopBar Q1 A (incoming) UI
function TopbarQ1a(){


}

function Q1L(){
 
 	return {  		
 		Q1L 						// reponse var call as: varQ1.varq1
 		 
 	}; 
}
 

// Base Services

// HTTP _ AJAX fetch_async
// input->data: {
//				token : data.token, 
//		        fnc_async: data.fnc_async,   
//		        data: data.data
//		        }
// output->[{"fetch_response_var":"test","fetch_response_data":[1471958700]}]

function fetchAsync($http,$q){

		var token = "THETOKEN!";
		var main_ajax_url = "http://192.168.0.33/PB_Develop/A_in_progress/PB_jit/yii2/yii-1/backend/web/index.php?r=bec/backendasync";
		// return var to process.. { fetched_data, error_mess }
		return({
			fetch: fetch
		})
		// fetch function
		function fetch(data){
			// make ajax query
			var request = $http({
							method: "post",
							url: main_ajax_url,
							data: {
								token : token, 
						        fnc_async: data.fnc_async, //datafnc_async, 
						        data: data.data
						    }

						});
			return( request.then( handleSuccess, handleError ) );

		}

		// ---
        // PRIVATE METHODS.
        // ---
         function handleError( response ) { 
             return( $q.reject( response.statusText ) );
        }
     
         function handleSuccess( response ) {
            return( response.data);
        }

};

// HTTP _ AJAX fetch_sync
// input->data: {
//				token : data.token, 
//		        fnc_sync: data.fnc_sync,   
//		        data: data.data
//		        }
// output->[{"fetch_response_var":"test","fetch_response_data":[1471958700]}]

function fetchSync($http,$q){

		var token = "THETOKEN!";
		var main_ajax_url = "http://192.168.0.33/PB_Develop/A_in_progress/PB_jit/yii2/yii-1/backend/web/index.php?r=bec/backendsync";
		// return var to process.. { fetched_data, error_mess }
		return({
			fetch: fetch
		})
		// fetch function
		function fetch(data){
			// make ajax query
			var request = $http({
							method: "post",
							url: main_ajax_url,
							data: {
								token : token, 
						        fnc_sync: data.fnc_sync, //datafnc_sync, 
						        data: data.data
						    }

						});
			return( request.then( handleSuccess, handleError ) );

		}

		// ---
        // PRIVATE METHODS.
        // ---
         function handleError( response ) { 
             return( $q.reject( response.statusText ) );
        }
     
         function handleSuccess( response ) {
            return( response.data);
        }

};


 angular
    .module('PB_jit')
    .service('fetchAsync',fetchAsync)  // http ajax query
    .service('fetchSync',fetchSync)  // http ajax query
 //   .factory('TopbarQ1A',TopbarQ1A)
     .service('Q1L',Q1L)  // http ajax query
 