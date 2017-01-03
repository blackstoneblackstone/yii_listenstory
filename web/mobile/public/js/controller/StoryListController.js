/**
 * Created by lihb on 12/15/16.
 */

motherApp.controller('StoryListController', function ($scope, $http) {
    audioMain.jPlayer("stop", 0);
    $loadingToast.show();
    $.get("/pwd?openid=" + openid + "&type=0").success(function (data) {
        if(data==0){
            window.location.href="/mobile/#/newUser";
            return;
        }
    });
    initUI();
    $http.get("/storyList?openid=" + openid).success(function (data) {
        $scope.stories = data;
        $("#barNum1").text(data.length);
        $loadingToast.hide();
    });

    $scope.star = function (id, index) {
        $loadingToast.show();
        $http.get("/starStory?openid=" + openid + "&id=" + id).success(function (data) {
            $loadingToast.hide();
            $scope.stories[index].star = 1;
            showToast("收藏成功");
        });
    }

    function initUI() {
        $("#barStory").removeClass("un-active");
        $("#barMyStory").addClass("un-active");
    }
});


