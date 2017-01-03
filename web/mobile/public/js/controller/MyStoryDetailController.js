/**
 * Created by lihb on 12/15/16.
 */
motherApp.controller('MyStoryDetailController', function ($http, $scope, $routeParams, $timeout) {
    $scope.source = $routeParams.source;
    if ($scope.source == 1) {
        $("#footer").remove();
    }
    var mp3Path = "mp3/";
    var amrPath = "amr/";
    //播放状态 默认单曲循环
    $scope.playTypeState = 0;
    $scope.voices = [];
    $scope.id = $routeParams.id;

    $scope.pause = function () {
        audioMain.jPlayer("pause");
        $("#btnPlay").show();
        $("#btnPause").hide();
        $("#playImg").addClass("pause");
    }

    $scope.play = function () {
        $("#btnPlay").hide();
        $("#btnPause").show();
        $("#playImg").addClass("an-cricle");
        $("#playImg").removeClass("pause");
        if ($scope.firstPlay) {
            audioMain.jPlayer("setMedia", {
                mp3: $scope.voices[0]
            });
            $("#sayText1").hide();
            $("#sayText2").hide();
            $("#sayText3").hide();
            $("#container").hide();
            $scope.firstPlay = false;
            if ($scope.voices.length == 1) {
                $("#sayText2").show();
                $("#container").show();
                //播放
                audioMain.jPlayer("play", 0);
                //停止
                audioMain.bind($.jPlayer.event.ended, function () {
                    audioMain.jPlayer("stop", 0);
                    $scope.pause();
                    $scope.firstPlay = true;
                    if ($scope.playTypeState == 0) {
                        $scope.play();
                    }
                    if ($scope.playTypeState == 1) {
                        $scope.id = $scope.rightId;
                        $scope.pageData(
                            function () {
                                $scope.play();
                            }
                        );
                    }
                });

            }
            if ($scope.voices.length == 2 && $scope.voices[0].split('.')[0] == "mp3/" + $scope.story.id) {
                $("#sayText2").show();
                $("#container").show();
                //停止1
                audioMain.bind($.jPlayer.event.ended, function () {
                    $("#sayText3").show();
                    $("#container").hide();
                    //装载2
                    audioMain.jPlayer("setMedia", {
                        mp3: $scope.voices[1]
                    });
                    //停止2
                    audioMain.bind($.jPlayer.event.ended, function () {
                        $("#sayText3").hide();
                        $("#container").hide();
                        audioMain.jPlayer("stop", 0);
                        $scope.pause();
                        $scope.firstPlay = true;
                        if ($scope.playTypeState == 0) {
                            $scope.play();
                        }
                        if ($scope.playTypeState == 1) {
                            $scope.id = $scope.rightId;
                            $scope.pageData(function () {
                                $scope.play();
                            });
                        }
                    });
                    //播放2
                    audioMain.jPlayer("play", 0);
                });
                //播放1
                audioMain.jPlayer("play", 0);
            }
            if ($scope.voices.length == 2 && $scope.voices[0].split('.')[0] != "mp3/" + $scope.story.id) {
                $("#sayText1").show();
                //播放1
                audioMain.jPlayer("play", 0);
                //停止1
                audioMain.bind($.jPlayer.event.ended, function () {
                    $("#sayText2").show();
                    $("#container").show();
                    //装载2
                    audioMain.jPlayer("clearMedia");
                    audioMain.jPlayer("setMedia", {
                        mp3: $scope.voices[1]
                    });
                    //播放2
                    audioMain.jPlayer("play", 0);
                    //停止2
                    audioMain.bind($.jPlayer.event.ended, function () {
                        $("#sayText3").hide();
                        $("#container").hide();
                        audioMain.jPlayer("stop", 0);
                        $scope.pause();
                        $scope.firstPlay = true;
                        if ($scope.playTypeState == 0) {
                            $scope.play();
                        }
                        if ($scope.playTypeState == 1) {
                            $scope.id = $scope.rightId;
                            $scope.pageData(
                                function () {
                                    $scope.play();
                                }
                            );
                        }
                    });
                });
            }
            if ($scope.voices.length == 3) {
                $("#sayText1").show();
                //播放1
                audioMain.jPlayer("play", 0);
                //停止1
                audioMain.bind($.jPlayer.event.ended, function () {
                    $("#container").show();
                    $("#sayText2").show();
                    //装载2
                    audioMain.jPlayer("clearMedia");
                    audioMain.jPlayer("setMedia", {
                        mp3: $scope.voices[1]
                    });
                    //播放2
                    audioMain.jPlayer("play", 0);
                    //停止2
                    audioMain.bind($.jPlayer.event.ended, function () {
                        $("#sayText3").show();
                        $("#container").hide();
                        //装载3
                        audioMain.jPlayer("clearMedia");
                        audioMain.jPlayer("setMedia", {
                            mp3: $scope.voices[2]
                        });
                        //播放3
                        audioMain.jPlayer("play", 0);
                        //停止3
                        audioMain.bind($.jPlayer.event.ended, function () {
                            $("#sayText3").hide();
                            $("#container").hide();
                            audioMain.jPlayer("stop", 0);
                            $scope.pause();
                            $scope.firstPlay = true;
                            if ($scope.playTypeState == 0) {
                                $scope.play();
                            }
                            if ($scope.playTypeState == 1) {
                                $scope.id = $scope.rightId;
                                $scope.pageData(
                                    function () {
                                        $scope.play();
                                    }
                                );
                            }
                        });
                    })
                })
            }
        } else {
            audioMain.jPlayer("play");
        }
    }

    $scope.pageData = function (callback) {
        $scope.firstPlay = true;
        $loadingToast.show();
        $http.get("/getStory?storyid=" + $scope.id + "&openid=" + openid).success(function (data) {
            $http.get("/addPV?storyid=" + $scope.id);
            $("#btnPlay").show();
            $scope.voices = [];
            $scope.story = data;
            $scope.leftId = data.left;
            $scope.rightId = data.right;
            $scope.leftUrl = "#/myStoryDetail/" + data.left + "/"+$scope.source;
            $scope.rightUrl = "#/myStoryDetail/" + data.right + "/"+$scope.source;
            if ($scope.story.piantouid != '') {
                $scope.voices.push(amrPath + $scope.story.piantouid + ".mp3");
            }
            $scope.voices.push(mp3Path + $scope.story.id + ".mp3");
            if ($scope.story.pianweiid != '') {
                $scope.voices.push(amrPath + $scope.story.pianweiid + ".mp3");
            }
            audioMain.jPlayer("destroy");
            audioMain.jPlayer({
                ready: function (event) {
                    $(this).jPlayer("setMedia", {
                        mp3: $scope.voices[0]
                    });

                    if (callback != undefined) {
                        setTimeout(function () {
                            callback();
                        }, 5000);
                    }
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
            $loadingToast.hide();

        });
    }

    $scope.pageData();

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
    $scope.playType = function () {
        if ($scope.playTypeState == 0) {
            $("#p1").attr("class", "glyphicon glyphicon-arrow-right");
            $("#p2").text("顺序播放");
            $scope.playTypeState = 1;
            return;
        }
        if ($scope.playTypeState == 1) {
            $("#p1").attr("class", "glyphicon glyphicon-retweet");
            $("#p2").text("单曲播放");
            $scope.playTypeState = 0;
            return;
        }
    }
});