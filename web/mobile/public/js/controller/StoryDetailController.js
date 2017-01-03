/**
 * Created by lihb on 12/15/16.
 */
motherApp.controller('StoryDetailController', function ($http, $scope, $routeParams, $timeout) {
    $timeout(function () {
        $("#btnTip").hide();
    }, 5000);
    $http.get("/addPV?storyid=" + $routeParams.id);
    var mp3Path = "mp3/";
    var voice = mp3Path + $routeParams.id + ".mp3";
    audioMain.jPlayer({
        ready: function (event) {
            $(this).jPlayer("setMedia", {
                mp3: voice
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
    $http.get("/stories/" + $routeParams.id).success(function (data) {
        $scope.story = data;
    });
    $scope.pianState = 0;

    $("#btnPlay").show();
    var int;
    $scope.play = function () {
        audioMain.jPlayer("play", 0);
        audioMain.bind($.jPlayer.event.ended, function () {
            audioMain.jPlayer("stop", 0);
            showToast("故事讲完了");
            $scope.pause();
        });
        $("#btnPlay").hide();
        $("#btnPause").show();
        $("#playImg").addClass("an-cricle");
        $("#playImg").removeClass("pause");
    }
    $scope.pause = function () {
        clearInterval(int);
        audioMain.jPlayer("pause");
        $("#btnPlay").show();
        $("#btnPause").hide();
        $("#playImg").addClass("pause");
    }
    $scope.piantou = function () {
        $scope.pause();
        $("#lu").show();
        $("#textPlace").text("故事开头");
        $scope.pianState = 1;
        $("#recordPlay").hide();
        $("#recordStop").hide();
        $("#recordUp").hide();
    }
    $scope.pianwei = function () {
        $scope.pause();
        $("#lu").show();
        $("#textPlace").text("故事结尾");
        $scope.pianState = 2;
        $("#recordPlay").hide();
        $("#recordStop").hide();
        $("#recordUp").hide();
    }
    var time;
    $scope.ptlStart = function () {
        $("#btnLuText").text("点击停止录音");
        $("#btnLu1").hide();
        $("#btnLu2").show();
        wx.startRecord();
        $("#djs").show();
        $("#recordPlay").hide();
        $("#recordStop").hide();
        $("#recordUp").hide();
        var recordTime = 60;
        $("#recordTime").text(recordTime);
        time = setInterval(function () {
            recordTime = recordTime - 1;
            $("#recordTime").text(recordTime);
            if (recordTime == 1) {
                clearInterval(time);
            }
        }, 1000);
        wx.onVoiceRecordEnd({
            // 录音时间超过一分钟没有停止的时候会执行 complete 回调
            complete: function (res) {
                showToast("超过60秒了");
                $scope.recordId = res.localId;
                $scope.ptlStop();
            }
        });
    }
    $scope.ptlStop = function () {
        $("#btnLuText").text("点击开始录音");
        $("#btnLu1").show();
        $("#btnLu2").hide();
        clearInterval(time);
        $("#djs").hide();
        wx.stopRecord({
            success: function (res) {
                $scope.recordId = res.localId;
                $("#recordPlay").show();
                $("#recordStop").hide();
                $("#recordUp").show();
            }
        });
    }

    $scope.recordPlay = function () {
        wx.playVoice({
            localId: $scope.recordId
        });
        $("#recordPlay").hide();
        $("#recordStop").show();
        wx.onVoicePlayEnd({
            success: function () {
                $("#recordPlay").show();
                $("#recordStop").hide();
            }
        });
    }
    $scope.recordStop = function () {
        wx.stopVoice({
            localId: $scope.recordId
        });
        $("#recordPlay").show();
        $("#recordStop").hide();
    }
    $scope.recordUp = function () {
        $scope.close();
        if ($scope.pianState == 1) {
            $scope.piantouId = $scope.recordId;
            $("#recordPT").show();
            $("#addPT").text("重新录片头");
            if ($scope.piantouId != null) {
                wx.uploadVoice({
                    localId: $scope.piantouId, // 需要上传的音频的本地ID，由stopRecord接口获得
                    isShowProgressTips: 1, // 默认为1，显示进度提示
                    success: function (res) {
                        $http.get("/addPianTou?openid=" + openid + "&storyid=" + $scope.story.id + "&piantouid=" + res.serverId)
                            .success(function (data) {
                                $loadingToast.hide();
                                if (!data.code) {
                                    showToast(data.msg);
                                } else {
                                    showFailToast(data.msg);
                                }
                            });
                    }
                });
            }
        }
        if ($scope.pianState == 2) {
            $scope.pianweiId = $scope.recordId;
            $("#recordPW").show();
            $("#addPW").text("重新录片尾");
            if ($scope.pianweiId != null) {
                wx.uploadVoice({
                    localId: $scope.pianweiId, // 需要上传的音频的本地ID，由stopRecord接口获得
                    isShowProgressTips: 1, // 默认为1，显示进度提示
                    success: function (res) {
                        $http.get("/addPianWei?openid=" + openid + "&storyid=" + $scope.story.id + "&pianweiid=" + res.serverId)
                            .success(function (data) {
                                $loadingToast.hide();
                                if (!data.code) {
                                    showToast(data.msg);
                                } else {
                                    showFailToast(data.msg);
                                }
                            });
                    }
                });
            }
        }

        if ($scope.pianweiId != null && $scope.pianweiId != "" && $scope.piantouId != null && $scope.piantouId != "") {
            $("#tryPlay").show();
        }

        $scope.recordStop();


        // wx.uploadVoice({
        //     localId: $scope.recordId, // 需要上传的音频的本地ID，由stopRecord接口获得
        //     isShowProgressTips: 1, // 默认为1，显示进度提示
        //     success: function (res) {
        //         $scope.serverId = res.serverId; // 返回音频的服务器端ID
        //         $scope.close();
        //         if ($scope.pianState == 1) {
        //             $http.get("/addPianTou?openid=" + openid + "&storyid=" + $scope.story.id + "&piantouid=" + $scope.serverId)
        //                 .success(function (data) {
        //                     showToast(data.msg);
        //                 });
        //         }
        //         if ($scope.pianState == 2) {
        //             $http.get("/addPianWei?openid=" + openid + "&storyid=" + $scope.story.id + "&pianweiid=" + $scope.serverId)
        //                 .success(function (data) {
        //                     showToast(data.msg);
        //                 });
        //         }
        //     }
        // });
    }

    $scope.close = function () {
        $("#btnLu2").hide();
        $("#lu").addClass("fadeOutDown");
        $timeout(function () {
            $("#lu").removeClass("fadeOutDown");
            $("#lu").hide();
        }, 500);
        $scope.recordStop();
    }

    $scope.starStory = function (id) {
        $loadingToast.show();
        $http.get("/starStory?openid=" + openid + "&id=" + id).success(function (data) {
            $loadingToast.hide();
            showToast(data.msg);
        });
    }

    $scope.sendStory = function (id) {
        if (($scope.pianweiId != null && $scope.pianweiId != "") || ($scope.piantouId != null && $scope.piantouId != "")) {
            $loadingToast.show();
            $http.get("/sendStory?openid=" + openid + "&id=" + id).success(function (data) {
                $loadingToast.hide();
                showToast(data.msg);
                dialog("输入你们的专属播放密码<br>孩子就可以收听妈妈为ta定制的专属故事啦");
            });
        } else {
            dialog("要录制片头或者片尾才能发送给孩子哦");
        }
    }

    var recordPTPlay = true;
    $scope.recordPT = function () {
        if (recordPTPlay) {
            wx.playVoice({
                localId: $scope.piantouId
            });
            wx.onVoicePlayEnd({
                success: function () {
                    $("#recordPT").removeClass('btn-success');
                    $("#recordPT").text("播放片头");
                    recordPTPlay = true;
                }
            });
            recordPTPlay = false;
            $("#recordPT").addClass('btn-success');
            $("#recordPT").text("停止播放");
        } else {
            wx.stopVoice({
                localId: $scope.piantouId
            });
            $("#recordPT").removeClass('btn-success');
            $("#recordPT").text("播放片头");
            recordPTPlay = true;
        }
    }
    var recordPWPlay = true;
    $scope.recordPW = function () {
        if (recordPWPlay) {
            wx.playVoice({
                localId: $scope.pianweiId
            });
            wx.onVoicePlayEnd({
                success: function () {
                    $("#recordPW").removeClass('btn-success');
                    $("#recordPW").text("播放片尾");
                    recordPWPlay = true;
                }
            });
            $("#recordPW").addClass('btn-success');
            $("#recordPW").text("停止播放");
            recordPWPlay = false;
        } else {
            wx.stopVoice({
                localId: $scope.pianweiId
            });
            $("#recordPW").removeClass('btn-success');
            $("#recordPW").text("播放片尾");
            recordPWPlay = true;
        }
    }

    var tryPlayState = true;
    $scope.tryPlay = function () {
        if (tryPlayState) {
            tryPlayState = false;
            $("#tryPlay").text("正在试听...");
            $("#tryPlay").addClass('btn-success');
            $("#btnPlay").hide();
            $("#btnPause").show();
            $("#playImg").addClass("an-cricle");
            $("#playImg").removeClass("pause");
            wx.playVoice({
                localId: $scope.piantouId
            });
            wx.onVoicePlayEnd({
                success: function () {
                    audioMain.jPlayer("play", 0);
                    audioMain.bind($.jPlayer.event.ended, function () {
                        audioMain.jPlayer("stop", 0);
                        wx.playVoice({
                            localId: $scope.pianweiId
                        });
                        wx.onVoicePlayEnd({
                            success: function () {
                                $("#tryPlay").removeClass('btn-success');
                                $("#tryPlay").text("试听整个故事");
                            }
                        });
                    });
                }
            });
        } else {
            $("#btnPlay").show();
            $("#btnPause").hide();
            $("#playImg").addClass("pause");
            tryPlayState = true;
            wx.stopVoice({
                localId: $scope.piantouId
            });
            wx.stopVoice({
                localId: $scope.pianweiId
            });
            audioMain.jPlayer("stop", 0);
            $("#tryPlay").removeClass('btn-success');
            $("#tryPlay").text("试听整个故事");
        }
    }
});