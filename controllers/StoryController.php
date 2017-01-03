<?php
namespace app\controllers;

use app\models\MyStory;
use app\models\Story;
use yii\rest\ActiveController;

class StoryController extends ActiveController
{
    public $modelClass = 'app\models\Story';


    public function actionStories($openid)
    {
        $stories = Story::find()->orderBy("time DESC")->all();
        $ss=array();
        foreach ($stories as $story) {
            $s = MyStory::findOne(array('storyid' => ($story->id),'openid'=>$openid));
            if (!empty($s)) {
                $story->star = 1;
            }
            array_push($ss, $story);
        }

        return $ss;
    }
}