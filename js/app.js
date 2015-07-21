// initialize the app
var myapp = angular.module('vt-app', ['ngRoute']);

myapp.config(function($routeProvider, $locationProvider) {
	$locationProvider.html5Mode(true);
  
	$routeProvider
	.when('/', {
		templateUrl: myLocalized.partials + 'main.html',
		controller: 'main'
	})
	.when('/advanced', {
		templateUrl: myLocalized.partials + 'advanced.html',
		controller: 'advanced'
	})
	.when('/advanced/id/:lastId', {
		templateUrl: myLocalized.partials + 'advanced.html',
		controller: 'advanced'
	})
	.when('/advanced/eng', {
		templateUrl: myLocalized.partials + 'advanced_eng.html',
		controller: 'advanced'
	})
	.when('/advanced/eng/id/:lastId', {
		templateUrl: myLocalized.partials + 'advanced_eng.html',
		controller: 'advanced'
	})
	.when('/intermediate', {
		templateUrl: myLocalized.partials + 'intermediate.html',
		controller: 'intermediate'
	})
	.when('/intermediate/id/:lastId', {
		templateUrl: myLocalized.partials + 'intermediate.html',
		controller: 'intermediate'
	})
	.when('/intermediate/eng', {
		templateUrl: myLocalized.partials + 'intermediate_eng.html',
		controller: 'intermediate'
	})
	.when('/intermediate/eng/id/:lastId', {
		templateUrl: myLocalized.partials + 'intermediate_eng.html',
		controller: 'intermediate'
	})
	.when('/beginner', {
		templateUrl: myLocalized.partials + 'beginner.html',
		controller: 'beginner'
	})
	.when('/beginner/id/:lastId', {
		templateUrl: myLocalized.partials + 'beginner.html',
		controller: 'beginner'
	})
	.when('/beginner/eng', {
		templateUrl: myLocalized.partials + 'beginner_eng.html',
		controller: 'beginner'
	})
	.when('/beginner/eng/id/:lastId', {
		templateUrl: myLocalized.partials + 'beginner_eng.html',
		controller: 'beginner'
	})
	.otherwise({redirectTo: '/'});;
});



// set the configuration
myapp.run(['$rootScope', function($rootScope){
  // the following data is fetched from the JavaScript variables created by wp_localize_script(), and stored in the Angular rootScope
  $rootScope.dir = BlogInfo.url; 
  $rootScope.site = BlogInfo.site; //page URL = BASE
  $rootScope.api = AppAPI.url;
}]);

// add a controller
myapp.controller('main', ['$scope', function($scope) {
  console.log('Main Controller initialized');

}]);



myapp.controller('advanced', ['$scope', '$http', '$routeParams', function($scope, $http, $routeParams) {
  // load posts from the WordPress API
  $scope.reloadRoute = function() {
   $route.reload();
};

  $http({
    method: 'GET',
    url: $scope.api + 'posts', // derived from the rootScope
    params: {
      'type[]': 'vt_words',
      'filter[meta_key]': 'proficiency',
      'filter[meta_value]': 'advanced',
      'filter[count]': '-1'
      
    }
  }).
  success(function(data, status, headers, config) {
    $scope.postdata = data;
    $scope.count = data.length; 
    $scope.lastID = $routeParams.lastId;
    console.log($scope.count);
    do {
    	$scope.RandomID = Math.floor(Math.random() * $scope.count);
    } while ($scope.RandomID == $scope.lastID);
    
    $scope.translation = "";
    
    $scope.choices = new Array();
	for (i = 0; i < 2; i++) {
    	y = Math.floor(Math.random() * $scope.count);
    	if (y != $scope.RandomID && $scope.choices.indexOf($scope.postdata[y]) < 0) {
    		$scope.choices[i] = $scope.postdata[y];
    	}
		else{
			i = i - 1;
		}
	};
	$scope.choices.splice(Math.floor(Math.random() * 2), 0, $scope.postdata[$scope.RandomID]);
    $scope.result = false;
    $scope.checktranslation = function(value) {
    if (value == $scope.postdata[$scope.RandomID].custom_fields.translation) {
       $scope.checktranslation_message = "Congrats. That is correct!";
       $scope.result = true;
    }
    else{
    	$scope.checktranslation_message = "Wrong. Try again!";
    	$scope.result = false;
    }
};
$scope.checktranslation_eng = function(value) {
    if (value == $scope.postdata[$scope.RandomID].custom_fields.transliteration) {
       $scope.checktranslation_message = "Congrats. That is correct!";
       $scope.result = true;
    }
    else{
    	$scope.checktranslation_message = "Wrong. Try again!";
    	$scope.result = false;
    }
};
    
    $scope.getNumber = function(num) {
    return new Array(num);   
	};
  }).
  error(function(data, status, headers, config) {
  });
}]);


myapp.controller('intermediate', ['$scope', '$http', '$routeParams', function($scope, $http, $routeParams) {
  // load posts from the WordPress API
  $scope.reloadRoute = function() {
   $route.reload();
};
  $http({
    method: 'GET',
    url: $scope.api + 'posts', // derived from the rootScope
    params: {
      'type[]': 'vt_words',
      'filter[meta_key]': 'proficiency',
      'filter[meta_value]': 'intermediate',
      'filter[count]': '-1'
    }
  }).
  success(function(data, status, headers, config) {
    $scope.postdata = data.posts;
    $scope.count = data.count; 
    $scope.lastID = $routeParams.lastId;
    do {
    	$scope.RandomID = Math.floor(Math.random() * $scope.count);
    } while ($scope.RandomID == $scope.lastID);
    
    $scope.translation = "";
    
    $scope.choices = new Array();
	for (i = 0; i < 2; i++) {
    	y = Math.floor(Math.random() * $scope.count);
    	if (y != $scope.RandomID && $scope.choices.indexOf($scope.postdata[y]) < 0) {
    		$scope.choices[i] = $scope.postdata[y];
    	}
		else{
			i = i - 1;
		}
	};
	$scope.choices.splice(Math.floor(Math.random() * 2), 0, $scope.postdata[$scope.RandomID]);
    
   $scope.checktranslation = function(value) {
    if (value == $scope.postdata[$scope.RandomID].acf.translation) {
       $scope.checktranslation_message = "Congrats. That is correct!";
       $scope.result = true;
    }
    else{
    	$scope.checktranslation_message = "Wrong. Try again!";
    	$scope.result = false;
    }
};
  $scope.checktranslation_eng = function(value) {
    if (value == $scope.postdata[$scope.RandomID].acf.transliteration) {
       $scope.checktranslation_message = "Congrats. That is correct!";
       $scope.result = true;
    }
    else{
    	$scope.checktranslation_message = "Wrong. Try again!";
    	$scope.result = false;
    }
};
    
    $scope.getNumber = function(num) {
    return new Array(num);   
	};
  }).
  error(function(data, status, headers, config) {
  });
}]);

myapp.controller('beginner', ['$scope', '$http', '$routeParams', function($scope, $http, $routeParams) {
  // load posts from the WordPress API
 $scope.reloadRoute = function() {
   $route.reload();
};
  $http({
    method: 'GET',
    url: $scope.api + 'posts', // derived from the rootScope
    params: {
      'type[]': 'vt_words',
      'filter[meta_key]': 'proficiency',
      'filter[meta_value]': 'beginner',
      'filter[count]': '-1'
    }
  }).
  success(function(data, status, headers, config) {
    $scope.postdata = data.posts;
    $scope.count = data.count; 
    $scope.lastID = $routeParams.lastId;
    console.log($scope.lastID);
    do {
    	$scope.RandomID = Math.floor(Math.random() * $scope.count);
    } while ($scope.RandomID == $scope.lastID);
    
    $scope.translation = "";
    
    $scope.choices = new Array();
	for (i = 0; i < 2; i++) {
    	y = Math.floor(Math.random() * $scope.count);
    	if (y != $scope.RandomID && $scope.choices.indexOf($scope.postdata[y]) < 0) {
    		$scope.choices[i] = $scope.postdata[y];
    	}
		else{
			i = i - 1;
		}
	};
	$scope.choices.splice(Math.floor(Math.random() * 2), 0, $scope.postdata[$scope.RandomID]);
    
 $scope.checktranslation = function(value) {
    if (value == $scope.postdata[$scope.RandomID].acf.translation) {
       $scope.checktranslation_message = "Congrats. That is correct!";
       $scope.result = true;
    }
    else{
    	$scope.checktranslation_message = "Wrong. Try again!";
    	$scope.result = false;
    }
};
  
$scope.checktranslation_eng = function(value) {
    if (value == $scope.postdata[$scope.RandomID].acf.transliteration) {
       $scope.checktranslation_message = "Congrats. That is correct!";
       $scope.result = true;
    }
    else{
    	$scope.checktranslation_message = "Wrong. Try again!";
    	$scope.result = false;
    }
};

    $scope.getNumber = function(num) {
    return new Array(num);   
	};
  }).
  error(function(data, status, headers, config) {
  });
}]);