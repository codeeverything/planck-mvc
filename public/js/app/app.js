/**
 * A very simple (messy), implementation of a Todo List in AngularJS.
 * Items are pulled and pushed into the back-end via a RESTful API.
 * 
 * @author Mike Timms <mike@codeeverything.com>
 * 
 */
angular.module('planckApp', ['ngRoute']).config(['$routeProvider', function($routeProvider) {
    $routeProvider.
      when('/', {
        templateUrl: 'partials/list.html',
        controller: 'TodoController'
      }).
      otherwise({
        redirectTo: '/'
      });
}]).controller('TodoController', ['$scope', 'TodoAPI', function ($scope, todo) {
    // store the content of a new item as it is entered
    $scope.newItem = '';
    
    // populate the todo list upon instantiation
    todo.list().then(function successCallback(response) {
        // this callback will be called asynchronously
        // when the response is available
        $scope.todoList = response.data;
    }, errorHandler);
    
    // add a new item to the list of things to do
    $scope.addItem = function () {
        // check for blank entries
        if ($scope.newItem == '') {
            alert('Sorry. You can\'t enter a blank todo!');
            return;
        }
        
        // build the data to send
        var item = {
            "name": $scope.newItem
        };
        
        todo.add(item).then(function successCallback(response) {
            // push the item onto the list
            $scope.todoList.push(response.data);
            // reset the newItem text
            $scope.newItem = '';
            // focus the new item input
            document.getElementById('todoEntry').focus();
        }, errorHandler);
    };
    
    // remove an item
    $scope.deleteTodo = function (item) {
        todo.delete(item.id).then(function successCallback(response) {
            // remove from list
            var index = $scope.todoList.indexOf(item);
            $scope.todoList.splice(index, 1); 
        }, errorHandler);
    }
    
    /**
     * A generic function to handle errors from any of the actions above
     */
    function errorHandler(response) {
        alert('There was an error completing that action.');
        console.log(response);
    }
      
}]).service('TodoAPI', ['$http', function ($http) {
    // a service to manage the Todo API
    return {
        list: function () {
            return $http({
                method: 'GET',
                url: '/api/todo'
            });
        },
        add: function (item) {
            return $http({
                method: 'POST',
                url: '/api/todo',
                data: item
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