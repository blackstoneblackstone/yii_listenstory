/**
 * Created by lihb on 12/15/16.
 */

motherApp.controller('MyStoryController', function ($scope, $http) {
    $loadingToast.show();
    initUI();
    $http.get("/myStory?openid=" + openid).success(function (data) {
        $scope.pwd=data.pwd;
        $scope.stories = data.data;
        $loadingToast.hide();
    });
    $scope.del = function (id) {
        $loadingToast.show();
        $http.get("/delStar?openid=" + openid + "&storyid=" + id).success(function (data) {
            $loadingToast.hide();
            if (!data.code) {
                $scope.stories.remove(id);
                showToast(data.msg);
            } else {
                showToast(data.msg);
            }
        });
    }
    
    function initUI() {
        $("#barMyStory").removeClass("un-active");
        $("#barStory").addClass("un-active");
    }


});