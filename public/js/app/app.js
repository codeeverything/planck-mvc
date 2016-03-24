angular.module('planckApp', ['ngRoute']).config(['$routeProvider', function($routeProvider) {
    $routeProvider.
      when('/', {
        templateUrl: 'partials/list.html',
        controller: 'TodoListController'
      }).
    //   when('/phones/:phoneId', {
    //     templateUrl: 'partials/phone-detail.html',
    //     controller: 'PhoneDetailCtrl'
    //   }).
      otherwise({
        redirectTo: '/'
      });
}]).controller('TodoListController', ['$scope', 'TodoAPI', function ($scope, todo) {
  todo.list().then(function successCallback(response) {
        // this callback will be called asynchronously
        // when the response is available
        $scope.todoList = response.data;
    }, function errorCallback(response) {
        // called asynchronously if an error occurs
        // or server returns response with an error status.
    });
  
  $scope.deleteTodo = function (item) {
      console.log('delete', item.id);
      todo.delete(item.id).then(function successCallback(response) {
        // this callback will be called asynchronously
        // when the response is available
        var index = $scope.todoList.indexOf(item);
        $scope.todoList.splice(index, 1); 
    }, function errorCallback(response) {
        // called asynchronously if an error occurs
        // or server returns response with an error status.
    });
    
    return false;
  }
      
}]).service('TodoAPI', ['$http', function ($http) {
    
    return {
        list: function () {
            return $http({
                method: 'GET',
                url: '/api/todo'
            });
        },
        delete: function (id) {
            return $http({
                method: 'DELETE',
                url: '/api/todo/' + id
            });
        }
    }
}]);