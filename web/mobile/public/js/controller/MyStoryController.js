/**
 * Created by lihb on 12/15/16.
 */

motherApp.controller('MyStoryController', function ($scope, $http) {
    audioMain.jPlayer("stop", 0);
    $loadingToast.show();
    $scope.stories = [];
    initUI();
    $http.get("/myStarStory?openid=" + openid).success(function (data) {
        $scope.starState = true;
        $scope.pwd = data.pwd;
        $scope.stories = data.data;
        $("#barNum2").text(data.data.length);
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

    $scope.tabStar = function (e) {
        $loadingToast.show();
        $("#tabSend").removeClass("weui-bar__item_on");
        $("#tabStar").addClass("weui-bar__item_on");
        $scope.starState = true;
        $scope.sendState = false;
        $http.get("/myStarStory?openid=" + openid).success(function (data) {
            $scope.pwd = data.pwd;
            $scope.stories = data.data;
            $("#barNum2").text(data.data.length);
            $loadingToast.hide();
        });
    }

    $scope.tabSend = function () {
        $loadingToast.show();
        $("#tabSend").addClass("weui-bar__item_on");
        $("#tabStar").removeClass("weui-bar__item_on");
        $scope.starState = false;
        $scope.sendState = true;
        $http.get("/mySendStory?openid=" + openid).success(function (data) {
            $scope.pwd = data.pwd;
            $scope.stories = data.data;
            $("#barNum2").text(data.data.length);
            $loadingToast.hide();
        });
    }
});