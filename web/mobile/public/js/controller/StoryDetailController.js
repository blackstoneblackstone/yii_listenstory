/**
 * Created by lihb on 12/15/16.
 */
motherApp.controller('StoryDetailController', function ($http, $scope, $routeParams, $timeout) {
    $http.get("/addPV?storyid="+$routeParams.id);
    var mp3Path = "mp3/";
    var voice = mp3Path + $routeParams.id + ".mp3";
    audioMain.src = voice;
    $http.get("/stories/" + $routeParams.id).success(function (data) {
        $scope.story = data;
    });
    $scope.pianState = 0;

    $("#btnPlay").show();
    var int;
    $scope.play = function () {
        audioMain.play();
        $("#btnPlay").hide();
        $("#btnPause").show();
        $("#playImg").addClass("an-cricle");
        $("#playImg").removeClass("pause");
        int = setInterval(function () {
            $("#sdTime").text(Math.floor(audioMain.currentTime) + "/" + Math.floor(audioMain.duration) + "\"");
        }, 1000);
    }
    $scope.pause = function () {
        clearInterval(int);
        audioMain.pause();
        $("#btnPlay").show();
        $("#btnPause").hide();
        $("#playImg").addClass("pause");
    }
    $scope.piantou = function () {
        $("#lu").show();
        $("#textPlace").text("故事开头");
        $scope.pianState = 1;
        $("#recordPlay").hide();
        $("#recordStop").hide();
        $("#recordUp").hide();
    }
    $scope.pianwei = function () {
        $("#lu").show();
        $("#textPlace").text("故事结尾");
        $scope.pianState = 2;
        $("#recordPlay").hide();
        $("#recordStop").hide();
        $("#recordUp").hide();
    }
    var time;
    $scope.ptlStart = function () {
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
            dialog("告诉你的孩子你的密码<br>就可以播放您的定制故事");
        });
    }


    var recordPTPlay = true;
    $scope.recordPT = function () {
        if (recordPTPlay) {
            wx.playVoice({
                localId: $scope.piantouId
            });
            recordPTPlay = false;
            $("#recordPT").text("停止播放");
        } else {
            wx.stopVoice({
                localId: $scope.piantouId
            });
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
            $("#recordPW").text("停止播放");
            recordPWPlay = false;
        } else {
            wx.stopVoice({
                localId: $scope.pianweiId
            });
            $("#recordPW").text("播放片尾");
            recordPWPlay = true;
        }
    }
});