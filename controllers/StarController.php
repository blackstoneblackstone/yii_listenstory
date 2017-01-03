<?php
namespace app\controllers;

use app\models\MyStory;
use app\models\Password;
use app\models\Story;
use yii\base\Exception;
use yii\db\IntegrityException;
use yii\rest\ActiveController;

class StarController extends ActiveController
{
    public $modelClass = 'app\models\MyStory';


    function actionStar($openid, $id)
    {
        try {
            $myStory = MyStory::find()->where(array('openid' => $openid, 'storyid' => $id))->one();
            if (empty($myStory)) {
                $myStory = new MyStory();
                $myStory->openid = $openid;
                $myStory->storyid = $id;
            }
            $myStory->startime = date("Y/m/d");
            $myStory->save();

            //记录发送次数
            $s = Story::findOne(array('id' => $id));
            $s->starnum = ($s->starnum) + 1;
            $s->save();
            return array('code' => 0, 'msg' => '收藏成功');
        } catch (IntegrityException $ex) {
            return array('code' => 1, 'msg' => '收藏失败');
        }
    }

    function actionSend($openid, $id)
    {
        try {
            $myStory = MyStory::find()->where(array('openid' => $openid, 'storyid' => $id))->one();
            if (empty($myStory)) {
                $myStory = new MyStory();
                $myStory->openid = $openid;
                $myStory->storyid = $id;
            }
            $myStory->sendtime = date("Y/m/d");
            $myStory->save();

            //记录发送次数
            $s = Story::findOne(array('id' => $id));
            $s->sendnum = ($s->sendnum) + 1;
            $s->save();

            return array('code' => 0, 'msg' => '发送成功');
        } catch (IntegrityException $ex) {
            return array('code' => 1, 'msg' => '发送失败');
        }
    }


    function actionStarStories($openid)
    {
        $myStoried = MyStory::find()->where(array('openid' => $openid, 'sendtime' => null))->orderBy("sendtime DESC")->all();
        if (!empty($myStoried)) {
            $pwd = Password::findOne(array('openid' => $openid));
            $ss = array();
            foreach ($myStoried as $story) {
                $s = Story::findOne(array('id' => ($story->storyid)));
                $s->startime = $story->startime;
                array_push($ss, $s);
            }
            return array('pwd' => ($pwd->pwd), 'data' => $ss);
        } else {
            return array('pwd' => '', 'data' => array());
        }
    }

    function actionSendStories($openid)
    {
        $myStoried = MyStory::find()->where(array('openid' => $openid))->andWhere("sendtime!='[Null]'")->orderBy("sendtime DESC")->all();
        if (!empty($myStoried)) {
            $pwd = Password::findOne(array('openid' => $openid));
            $ss = array();
            foreach ($myStoried as $story) {
                $s = Story::findOne(array('id' => ($story->storyid)));
                $s->sendtime = $story->sendtime;
                array_push($ss, $s);
            }
            return array('pwd' => ($pwd->pwd), 'data' => $ss);
        } else {
            return array('pwd' => '', 'data' => array());
        }
    }

    //收藏的故事
    function actionStory($storyid, $openid)
    {
        $mystory = MyStory::findOne(array('storyid' => $storyid, 'openid' => $openid));
        $leftStory = MyStory::find()->where(array('openid' => $openid))->andWhere("storyid < " . $storyid)->andWhere("sendtime!='[Null]'")->orderBy("storyid DESC")->limit(1)->all();
        $rightStory = MyStory::find()->where(array('openid' => $openid))->andWhere("storyid > " . $storyid)->andWhere("sendtime!='[Null]'")->orderBy("storyid ASC")->limit(1)->all();
        $left = null;
        $right = null;
        if (!empty($leftStory)) {
            $left = $leftStory[0]->storyid;
        } else {
            $leftStory1 = MyStory::find()->where(array('openid' => $openid))->andWhere("sendtime!='[Null]'")->orderBy("storyid DESC")->limit(1)->all();
            $left = $leftStory1[0]->storyid;
        }
        if (!empty($rightStory)) {
            $right = $rightStory[0]->storyid;
        } else {
            $rightStory1 = MyStory::find()->where(array('openid' => $openid))->andWhere("sendtime!='[Null]'")->orderBy("storyid ASC")->limit(1)->all();
            $right = $rightStory1[0]->storyid;
        }

        $story = Story::findOne(array('id' => $storyid));
        $ss = array(
            'id' => $story->id,
            'name' => $story->name,
            'duration' => $story->duration,
            'img' => $story->img,
            'playnum' => $story->playnum,
            'description' => $story->description,
            'pianweiid' => $mystory->pianweiid,
            'piantouid' => $mystory->piantouid,
            'left' => $left,
            'right' => $right
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
            $myStoried = MyStory::find()->where(array('openid' => $user->openid))->andWhere("sendtime!='[Null]'")->orderBy("sendtime DESC")->all();
            $ss = array();
            foreach ($myStoried as $story) {
                $s = Story::findAll(array('id' => ($story->storyid)));
                array_push($ss, $s[0]);
            }
            return array("code" => 0, 'openid' => $user->openid, 'data' => $ss);
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


    function actionPwd($openid, $type)
    {
        $pwd = Password::findOne(array('openid' => $openid));
        $state=1;
        if (empty($pwd)) {
            $state=0;
            $pwd = new Password();
            $pwd->openid = $openid;
            $pwd->pwd = $this->getRandom();
        }
        if ($type == 0) {
            $pwd->mother = 1;
        }
        if ($type == 1) {
            $pwd->children = 1;
        }
        $pwd->save();
        return $state;
    }

    private function getRandom($length = 5)
    {
        $min = pow(10, ($length - 1));
        $max = pow(10, $length) - 1;
        $n = mt_rand($min, $max);
        $user = Password::findOne(array('pwd' => $n));
        if (empty($user)) {
            return $n;
        } else {
            $this->getRandom();
        }
    }

    function actionAccessToken()
    {
        $wechat = \Yii::$app->wechat;
//        return $wechat->getAccessToken(true);

    }
}