<?php
namespace app\controllers;

use app\models\MyStory;
use app\models\Password;
use app\models\Story;
use callmez\wechat\sdk\Wechat;
use yii\base\Exception;
use yii\db\IntegrityException;
use yii\rest\ActiveController;

class StarController extends ActiveController
{
    public $modelClass = 'app\models\MyStory';


    function actionStar($openid, $id)
    {
        try {
            $myStory = new MyStory();
            $myStory->openid = $openid;
            $myStory->storyid = $id;
            $myStory->save();
            return array('code' => 0, 'msg' => '收藏成功');
        } catch (IntegrityException $ex) {
            return array('code' => 1, 'msg' => '收藏失败');
        }
    }


    function actionStories($openid)
    {
        $myStoried = MyStory::findAll(array('openid' => $openid));
        $pwd = Password::findOne(array('openid' => $openid));
        $ss = array();
        foreach ($myStoried as $story) {
            $s = Story::findOne(array('id' => ($story->storyid)));
            array_push($ss, $s);
        }
        return array('pwd' => ($pwd->pwd), 'data' => $ss);
    }

//    收藏的故事
    function actionStory($storyid, $openid)
    {
        $mystory = MyStory::findOne(array('storyid' => $storyid, 'openid' => $openid));
        $story = Story::findOne(array('id' => $storyid));
        $ss = array(
            'id' => $story->id,
            'name' => $story->name,
            'duration' => $story->duration,
            'img' => $story->img,
            'playnum' => $story->playnum,
            'description' => $story->description,
            'pianweiid' => $mystory->pianweiid,
            'piantouid' => $mystory->piantouid
        );
        return $ss;
    }


    function actionDel($openid, $storyid)
    {
        try {
            $myStoried = MyStory::findOne(array('openid' => $openid, 'storyid' => $storyid));
            $myStoried->delete();
            return array('code' => 0, 'msg' => '删除成功');
        } catch (Exception $e) {
            return array('code' => 1, 'msg' => '删除失败');
        }
    }

    function actionPt($openid, $storyid, $piantouid)
    {
        try {
            $wechat = \Yii::$app->wechat;
            if ($wechat->getMedia($piantouid)) {
                $amr = "/opt/userdata/basic/web/mobile/amr/" . $piantouid . ".amr";
                $mp3 = "/opt/userdata/basic/web/mobile/amr/" . $piantouid . ".mp3";
                $command = "ffmpeg -i $amr $mp3";
                system($command, $error);
                $myStoried = MyStory::findOne(array('openid' => $openid, 'storyid' => $storyid));
                if ($myStoried == null) {
                    $myStoried = new MyStory();
                    $myStoried->openid = $openid;
                    $myStoried->storyid = $storyid;
                    $myStoried->save();
                }
                $myStoried->piantouid = $piantouid;
                $myStoried->update();
                return array('code' => 0, 'msg' => '片头上传成功');
            } else {
                return array('code' => 1, 'msg' => '片头上传失败');
            }
        } catch (Exception $e) {
            return array('code' => 1, 'msg' => '片头上传失败');
        }
    }

    function actionPw($openid, $storyid, $pianweiid)
    {
        try {
            $wechat = \Yii::$app->wechat;
            if ($wechat->getMedia($pianweiid)) {
                $amr = "/opt/userdata/basic/web/mobile/amr/" . $pianweiid . ".amr";
                $mp3 = "/opt/userdata/basic/web/mobile/amr/" . $pianweiid . ".mp3";
                $command = "ffmpeg -i $amr $mp3";
                system($command, $error);
                $myStoried = MyStory::findOne(array('openid' => $openid, 'storyid' => $storyid));
                if ($myStoried == null) {
                    $myStoried = new MyStory();
                    $myStoried->openid = $openid;
                    $myStoried->storyid = $storyid;
                    $myStoried->save();
                }
                $myStoried->pianweiid = $pianweiid;
                $myStoried->update();
                return array('code' => 0, 'msg' => '片尾上传成功');
            } else {
                return array('code' => 1, 'msg' => '片尾上传失败');
            }
        } catch (Exception $e) {
            return array('code' => 1, 'msg' => '片尾上传失败');
        }
    }

    function actionChildren($pwd)
    {
        $user = Password::findOne(array('pwd' => $pwd));
        if ($user != null) {
            $myStoried = MyStory::findAll(array('openid' => $user->openid));
            $ss = array();
            foreach ($myStoried as $story) {
                $s = Story::findAll(array('id' => ($story->storyid)));
                array_push($ss, $s[0]);
            }
            return array("code" => 0, 'data' => $ss);
        } else {
            return array("code" => 1);
        }
    }


    function actionPv($storyid)
    {
        $story = Story::findOne(array('id' => $storyid));
        $story->playnum = ($story->playnum) + 1;
        $story->save();
    }

    function actionAccessToken()
    {
        $wechat = \Yii::$app->wechat;
//        return $wechat->getAccessToken(true);

    }
}