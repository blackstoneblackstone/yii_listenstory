/**
 * Created by lihb on 12/15/16.
 */

motherApp.controller('StoryListController', function ($scope, $http) {
    $loadingToast.show();
    initUI();
    $http.get("/storyList?openid="+openid).success(function (data) {
        $scope.stories = data;
        $loadingToast.hide();
    });
    function initUI() {
        $("#barStory").removeClass("un-active");
        $("#barMyStory").addClass("un-active");
    }
});


