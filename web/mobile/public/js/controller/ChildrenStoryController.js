/**
 * Created by lihb on 12/15/16.
 */

motherApp.controller('ChildrenStoryController', function ($scope, $http) {
    $.get("/pwd?openid=" + openid + "&type=1");
    $("#footer").remove();
    if (pwd != '') {
        $loadingToast.show();
        $http.get("/childrenStory?pwd=" + pwd).success(function (res) {
            $loadingToast.hide();
            if (res.code == 0) {
                $scope.stories = res.data;
                openid = res.openid;
                $("#topBg").hide();
                $("#pwdDialog").hide();
            } else {
                $("#pwdDialog").show();
            }
        });
    } else {
        $("#pwdDialog").show();
    }

    $("#pwdConfirm").on('click', function () {
        var pwds = $("#pwd").val();
        if (pwds != '') {
            $loadingToast.show();
            $http.get("/childrenStory?pwd=" + pwds).success(function (res) {
                pwd = pwds;
                openid = res.openid;
                $loadingToast.hide();
                if (res.code == 0) {
                    $scope.stories = res.data;
                    $("#topBg").hide();
                    $("#pwdDialog").hide();
                } else {
                    showFailToast("密码不对");
                }
            });
        } else {
            showFailToast("没输密码");
        }
    });


});