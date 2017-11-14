app.factory('cartoon_single', ['$http', function($http) {
    return $http.get('http://glog.local/api/test')
        .success(function(data) {
            return data;
        })
        .error(function(err) {
            return err;
        });
}]);