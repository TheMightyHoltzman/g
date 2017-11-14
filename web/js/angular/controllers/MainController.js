app.controller('MainController', ['$scope', 'api', function($scope, api) {
    cartoon_single.success(function(data) {
        $scope.cartoon_single = data;
    });
}]);
