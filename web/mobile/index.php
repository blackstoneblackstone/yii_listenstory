<?php
$openid = $_GET['openid'];
if ($openid == '' || $openid == null) {
    if ($_COOKIE['plhopenid'] == null || $_COOKIE['plhopenid'] == '') {
        $sourceUrl = "http://ls.wexue.top/mobile/index.php";
        header("Location:https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx4069e1635ae1be38&redirect_uri=http%3a%2f%2fwww.wexue.top%2fwxAuth.php&response_type=code&scope=snsapi_base&state=" . $sourceUrl . "#wechat_redirect");
    } else {
        $openid = $_COOKIE['plhopenid'];
    }
} else {
    setcookie('plhopenid', $openid);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>熊猫兔听故事</title>
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link href="public/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="public/lib/weui/weui.css" rel="stylesheet">
    <link href="public/css/animate.css" rel="stylesheet">
    <link href="public/css/an.css" rel="stylesheet">
    <link href="public/lib/swiper/swiper-3.4.0.min.css" rel="stylesheet">
    <link href="public/css/main.css" rel="stylesheet">
    <script>
        var openid = "<?php echo $openid;?>";
        var pwd;
        if (/Android (\d+\.\d+)/.test(navigator.userAgent)) {
            var version = parseFloat(RegExp.$1);
            if (version > 2.3) {
                var phoneScale = parseInt(window.screen.width) / 480;
                document.write('<meta name="viewport" content="width=480, minimum-scale = ' + phoneScale + ', maximum-scale = ' + phoneScale + ', target-densitydpi=device-dpi">');
            } else {
                document.write('<meta name="viewport" content="width=480, target-densitydpi=device-dpi">');
            }
        } else {
            document.write('<meta name="viewport" content="width=480, user-scalable=no, target-densitydpi=device-dpi">');
        }

        Array.prototype.remove = function (id) {
            for (var a in this) {
                if (this[a].id == id) {
                    this.splice(a, 1);
                }
            }
        };
    </script>
</head>
<body ng-app="mother">
<div class="container">
    <div class="header row"></div>
    <div class="con row animated an-view" ng-view style="min-height: 650px">

    </div>
    <div class="weui-footer" style="margin-bottom: 80px;">
        <p class="weui-footer__links">
            <a href="javascript:void(0);" class="weui-footer__link">熊猫兔听故事</a>
        </p>
        <p class="weui-footer__text">Copyright © 2008-2016 pilehou.com</p>
    </div>
    <div id="footer" class="row footer navbar-fixed-bottom">
        <div class="weui-tabbar">
            <a href="#/" class="weui-tabbar__item">
                    <span style="display: inline-block;position: relative;">
                        <img id="barStory" src="public/img/book.png" alt="" class="weui-tabbar__icon">
                        <span class="weui-badge" style="position: absolute;top: -2px;right: -13px;" id="barNum1"></span>
                    </span>
                <p class="weui-tabbar__label">全部故事</p>
            </a>
            <a href="#/myStory" class="weui-tabbar__item">
                     <span style="display: inline-block;position: relative;">
                        <img id="barMyStory" src="public/img/star.png" alt="" class="weui-tabbar__icon">
                        <span class="weui-badge" style="position: absolute;top: -2px;right: -13px;" id="barNum2"></span>
                    </span>
                <p class="weui-tabbar__label">我的故事</p>
            </a>
        </div>
    </div>
</div>
<audio id="audioMain" class="animated fadeInRightBig" preload="metadata" controls="controls"
       style="background-color:#ffffff;height:50px;display: none;position: absolute;right: 70px;top: 15px;">

</audio>
<div class="js_dialog animated fadeIn" id="dialog" style="display: none">
    <div class="weui-mask"></div>
    <div class="weui-dialog">
        <div class="weui-dialog__bd text"></div>
        <div class="weui-dialog__ft">
            <a href="javascript:;" id="confirmBtn" class="weui-dialog__btn weui-dialog__btn_primary">知道了</a>
        </div>
    </div>
</div>

<!--BEGIN toast-->
<div id="toast" class="animated" style="display: none;">
    <div class="weui-mask_transparent"></div>
    <div class="weui-toast">
        <i class="weui-icon-success-no-circle weui-icon_toast"></i>
        <p class="weui-toast__content">成功</p>
    </div>
</div>
<div id="toastFail" class="animated" style="display: none">
    <div class="weui-mask_transparent"></div>
    <div class="weui-toast">
        <i class="weui-icon-warn weui-icon_toast" style="height: 50px;font-size: 50px"></i>
        <p class="weui-toast__content">失败</p>
    </div>
</div>

<!--end toast-->

<!-- loading toast -->
<div id="loadingToast" class="animated fadeIn" style="display:none;">
    <div class="weui-mask_transparent"></div>
    <div class="weui-toast">
        <i class="weui-loading weui-icon_toast"></i>
        <p class="weui-toast__content">数据加载中</p>
    </div>
</div>
<script src="public/lib/jplayer/jquery.min.js"></script>
<script src="public/lib/jplayer/touch.js"></script>
<script src="public/lib/jplayer/jquery.jplayer.min.js"></script>
<script src="public/lib/angular/angular.min.js"></script>
<script src="public/lib/angular/angular-animate.min.js"></script>
<script src="public/lib/angular/angular-route.js"></script>
<script src="public/lib/weui/jweixin-1.0.0.js"></script>
<script src="public/js/main.js"></script>
<script src="public/js/controller/StoryListController.js"></script>
<script src="public/js/controller/MyStoryController.js"></script>
<script src="public/js/controller/StoryDetailController.js"></script>
<script src="public/js/controller/MyStoryDetailController.js"></script>
<script src="public/js/controller/ChildrenStoryController.js"></script>
<script src="public/js/controller/NewUserController.js"></script>
<script>
    setJSAPI();
    function setJSAPI() {
        var option = {
            title: '皮乐猴-听妈妈讲故事',
            desc: '让孩子每晚都能听到妈妈专门为ta讲的的故事！',
            link: 'http://ls.wexue.top/mobile/',
            imgUrl: 'http://ls.wexue.top/mobile/public/img/share.jpg'
        };

        $.getJSON('http://www.wexue.top/weixinjs.php?url=' + encodeURIComponent(location.href), function (res) {
            wx.config({
                beta: true,
                debug: false,
                appId: res.appId,
                timestamp: res.timestamp,
                nonceStr: res.nonceStr,
                signature: res.signature,
                jsApiList: res.jsApiList
            });
            wx.ready(function () {
                wx.onMenuShareTimeline(option);
                wx.onMenuShareQQ(option);
                wx.onMenuShareAppMessage({
                    title: '熊猫兔听故事',
                    desc: '让孩子每晚都能听到妈妈专门为ta讲的的故事！',
                    link: 'http://ls.wexue.top/mobile/',
                    imgUrl: 'http://ls.wexue.top/mobile/public/img/share.jpg'
                });
            });
        });
    }

    var _mtac = {};
    (function () {
        var mta = document.createElement("script");
        mta.src = "http://pingjs.qq.com/h5/stats.js?v2.0.2";
        mta.setAttribute("name", "MTAH5");
        mta.setAttribute("sid", "500380141");

        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(mta, s);
    })();
</script>
</body>
</html>