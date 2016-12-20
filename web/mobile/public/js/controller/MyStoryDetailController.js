/**
 * Created by lihb on 12/15/16.
 */
motherApp.controller('MyStoryDetailController', function ($http, $scope, $routeParams, $timeout) {
    $http.get("/addPV?storyid=" + $routeParams.id);
    $loadingToast.show();
    $("#footer").remove();
    var mp3Path = "mp3/";
    var amrPath = "amr/";
    $scope.voices = [];
    $http.get("/getStory?storyid=" + $routeParams.id + "&openid=" + openid).success(function (data) {
        $scope.story = data;
        if ($scope.story.piantouid != '') {
            $scope.voices.push(amrPath + $scope.story.piantouid + ".mp3");
        }
        $scope.voices.push(mp3Path + $scope.story.id + ".mp3");
        if ($scope.story.pianweiid != '') {
            $scope.voices.push(amrPath + $scope.story.pianweiid + ".mp3");
        }
        $(document).ready(function () {

            /*
             根据浏览器兼容性测试结果，ie9+, firefox, chrome,opera,safari能支持m4a 和ogg 中的至少一种。
             setMedia 设置了两个资源，那么优先播放哪个呢？会考虑两个因素
             首先播放supplied设置的第一个格式，如果当前浏览器支持，那么会播放小城大事，如果不支持那么会播放第二个supplied 的格式对应的资源也就是test1.m4a
             */
            $("#jquery_jplayer_1").jPlayer({
                ready: function (event) {
                    $(this).jPlayer("setMedia", {
                        title: "test1或者小城大事",
                        m4a: "http://ls.wexue.top/mobile/mp3/0.mp3"
                    });
                },
                swfPath: "../lib/jplayer/jquery.jplayer.swf", // jquery.jplayer.swf 文件存放的位置
                supplied: "oga,m4a,mp3",
                wmode: "window", // 设置Flash 的wmode，具体设置参见文档说明，写window 就好了
                useStateClassSkin: true, // 默认情况下，播放和静音状态下的dom 元素会添加class jp-state-playing, jp-state-muted 这些状态会对应一些皮肤，是否使用这些状态对应的皮肤。
                autoBlur: false, // 点击之后自动失去焦点
                smoothPlayBar: true, // 平滑过渡
                keyEnabled: true, // 是否允许键盘控制播放
                remainingDuration: true, // 是否显示剩余播放时间,如果为false 那么duration 那个dom显示的是【3:07】,如果为true 显示的为【-3:07】
                toggleDuration: true //允许点击剩余时间的dom 时切换 剩余播放时间的方式，比如从【3:07】点击变成【-3：07】如果设置为false ,那么点击无效，只能显示remainingDuration 设置的方式。
            });
        });
        $loadingToast.hide();
    });

    $("#btnPlay").show();


    var int;
    $scope.firstPlay = true;

    $scope.play = function () {
        $("#btnPlay").hide();
        $("#btnPause").show();
        $("#playImg").addClass("an-cricle");
        $("#playImg").removeClass("pause");
        int = setInterval(function () {
            $("#sdTime").text(Math.floor(audioMain.currentTime) + "/" + Math.floor(audioMain.duration) + "\"");
        }, 1000);
        if ($scope.firstPlay) {
            $scope.firstPlay = false;
            if ($scope.voices.length == 1) {
                audioMain.src = $scope.voices[0];
                audioMain.jPlayer("play", 0);
                $("#sayText2").show();
            }
            if ($scope.voices.length == 2) {
                audioMain.bind($.jPlayer.event.ended, function () {
                    audioMain.jPlayer("setMedia", {
                        mp3: $scope.voices[1]
                    });
                    audioMain.jPlayer("play", 0);
                });
                audioMain.jPlayer("play", 0);
            }
            if ($scope.voices.length == 3) {
                $("#sayText1").show();
                audioMain.bind($.jPlayer.event.ended, function () {
                    $("#sayText2").show();
                    audioMain.jPlayer("clearMedia");
                    audioMain.jPlayer("setMedia", {
                        mp3: $scope.voices[1]
                    });
                    audioMain.jPlayer("play", 0);
                    audioMain.bind($.jPlayer.event.ended, function () {
                        $("#sayText3").show();
                        audioMain.jPlayer("clearMedia");
                        audioMain.jPlayer("setMedia", {
                            mp3: $scope.voices[2]
                        });
                        audioMain.jPlayer("play", 0);
                    })
                })
                audioMain.jPlayer("play", 0);
            }
        } else {
            audioMain.jPlayer("play", 0);
        }
    }
    $scope.pause = function () {
        audioMain.jPlayer("pause");
        clearInterval(int);
        $("#btnPlay").show();
        $("#btnPause").hide();
        $("#playImg").addClass("pause");
    }

    var firstShowPlayer = true;
    $scope.showPlayer = function () {
        if (firstShowPlayer) {
            firstShowPlayer = false;
            $("#audioMain").show();
        } else {
            $("#audioMain").hide();
            firstShowPlayer = true;
        }
    }

});