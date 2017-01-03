/**
 * Created by lihb on 12/15/16.
 */

var audioMain = $("#audioMain");
var motherApp = angular.module("mother", ['ngRoute', 'ngAnimate']);

motherApp.config(['$routeProvider',
    function ($routeProvider) {
        $routeProvider.when('/', {
            templateUrl: 'view/storyList.html',
            controller: 'StoryListController'
        }).when('/myStory', {
            templateUrl: 'view/myStory.html',
            controller: 'MyStoryController'
        }).when('/storyDetail/:id', {
            templateUrl: 'view/storyDetail.html',
            controller: 'StoryDetailController'
        }).when('/myStoryDetail/:id/:source', {
            templateUrl: 'view/myStoryDetail.html',
            controller: 'MyStoryDetailController'
        }).when('/childrenStory', {
            templateUrl: 'view/childrenStory.html',
            controller: 'ChildrenStoryController'
        }).when('/newUser', {
            templateUrl: 'view/newUser.html',
            controller: 'NewUserController'
        }).otherwise({
            redirectTo: '/error'
        });
    }]);

function showToast(text) {
    var $toast = $("#toast");
    $toast.addClass("fadeIn");
    $toast.show();
    $("#toast .weui-toast__content").text(text);
    setTimeout(function () {
        $toast.removeClass("fadeIn");
        $toast.hide();
    }, 1000);
}

function showFailToast(text) {
    var $toast = $("#toastFail");
    $toast.addClass("fadeIn");
    $toast.show();
    $("#toastFail .weui-toast__content").text(text);
    setTimeout(function () {
        $toast.removeClass("fadeIn");
        $toast.hide();
    }, 1000);
}
var $loadingToast = $("#loadingToast");

function dialog(text) {
    $("#dialog .text").html(text);
    $("#dialog").show();
    $("#confirmBtn").on('click',function () {
        $("#dialog").hide();
    })
}
