/**
 * Created by lihb on 12/15/16.
 */

motherApp.controller('ChildrenStoryController', function ($scope, $http) {
    $("#footer").remove();
    if (pwd != '') {
        $loadingToast.show();
        $http.get("/childrenStory?pwd=" + pwd).success(function (res) {
            $loadingToast.hide();
            if (res.code == 0) {
                $scope.stories = res.data;
                $("#pwdDialog").hide();
            } else {
                $("#pwdDialog").show();
            }
        });
    }

    $("#pwdConfirm").on('tap',function () {
        var pwds = $("#pwd").val();
        if (pwds != '') {
            $loadingToast.show();
            $http.get("/childrenStory?pwd=" + pwds).success(function (res) {
                pwd = pwds;
                $loadingToast.hide();
                if (res.code == 0) {
                    $scope.stories = res.data;
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