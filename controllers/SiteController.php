<?php

namespace app\controllers;

use app\models\MyStory;
use app\models\Password;
use app\models\Story;
use GetId3\GetId3Core;
use linslin\yii2\curl\Curl;
use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\UploadForm;
use yii\web\Cookie;
use yii\web\UploadedFile;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $id = Yii::$app->session['userid'];
        if ($id == 'plh') {
            $story = Story::find()->all();
            $mother = Password::find()->where(array('mother' => 1))->count('*');
            $children = Password::find()->where(array('children' => 1))->count('*');
            $info = array(
                'zongshu' => ($mother + $children),
                'mother' => $mother,
                'children' => $children
            );
            return $this->render('index', ['stories' => $story, 'info' => $info]);
        } else {
            return $this->redirect("login");
        }
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionUserLogin()
    {
        $model = new LoginForm;
        if ($model->load(Yii::$app->request->post())) {
            if ($model->username == 'plh' && $model->password == 'plh123456') {
                Yii::$app->session['userid'] = 'plh';
                $this->redirect('/');
            }
        } else {
            return $this->redirect('login');
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }


    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }


    public function actionUploadImg()
    {
        $model = new UploadForm();
        if (Yii::$app->request->isPost) {
            $model->storyid = Yii::$app->request->post("storyid");
            $model->file = UploadedFile::getInstance($model, 'file');
            var_dump($model);
            if ($model->file && $model->validate()) {
                $model->file->saveAs('/opt/userdata/basic/web/mobile/mp3Img/' . $model->storyid . '.' . $model->file->extension);
                $story = Story::findOne(array('id' => ($model->storyid)));
                $story->img = "mp3Img/" . $model->storyid . '.' . $model->file->extension;
                $story->save();
                return 0;
            }
        }
        return 1;
    }

    public function actionUploadAudio()
    {
        $model = new UploadForm();
        if (Yii::$app->request->isPost) {
            $model->storyid = Yii::$app->request->post("storyid");
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->file && $model->validate()) {
                $audio = '/opt/userdata/basic/web/mobile/mp3/' . $model->storyid . '.' . $model->file->extension;
                $model->file->saveAs($audio);
                $getID3 = new GetId3Core();//创建一个类的实例
                $ThisFileInfo = $getID3->analyze($audio);//分析文件
                $time = $ThisFileInfo['playtime_seconds'];
                $tt = floor($time);
//                $ss=round(($time-$tt)*10000);
//                $h=floor($tt/3600);$tt%=3600;
                $m = floor($tt / 60);
                $tt %= 60;
                $s = $tt;
                $duration = "$m:$s";
                $st = Story::findOne(array('id' => $model->storyid));
                $st->duration = $duration;
                $st->size = ceil(($model->file->size) / 1024 / 1024) . "M";
                $st->save();
                return 0;
            }
        }
        return 1;
    }

    public function actionAddStory()
    {
        try {
            $model = new Story();
            $model->name = Yii::$app->request->get("name");
            $model->description = Yii::$app->request->get("description");
            $model->time = date("Y-m-d h:i:s");
            $model->save();
            return 0;
        } catch (Exception $e) {
            return 1;
        }

    }

    public function actionDelStory($id)

    {
        try {
            $st = Story::findOne(array('id' => $id));
            $st->delete();
            $myStories = MyStory::findAll(array('storyid' => $id));
            foreach ($myStories as $ms) {
                $ms->delete();
            }
            return 0;
        } catch (Exception $e) {
            return 1;
        }
    }

    public function actionEditStory($id, $name, $desc)
    {
        try {
            $s = Story::findOne($id);
            $s->name = $name;
            $s->description = $desc;
            $s->save();
            return 0;
        } catch (Exception $e) {
            return 1;
        }
    }


    public function actionWxCode($code, $state)
    {
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx4069e1635ae1be38&secret=4578c042ea9361b6e16626f1aa3d7e52&code=' . $code . '&grant_type=authorization_code';
        $result = null;
        try {
            //Init curl
            $curl = new Curl();
            //get http://example.com/
            $result = $curl->get($url);
            $openid = (json_decode($result)->openid);
            $user = Password::findOne(array('openid' => $openid));
            if (empty($user)) {
                $pwd = new Password();
                $pwd->openid = $openid;
                $pwd->pwd = $this->getRandom();
                $pwd->save();
            }
            return $this->redirect('/mobile/index.php?openid=' . $openid);
        } catch (Exception $e) {
            echo $e->getTraceAsString();
        }
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


    public function actionInfo()
    {
        $mother = Password::find()->where(array('mother' => 1))->count('*');
        $children = Password::find()->where(array('children' => 1))->count('*');
        $info = array(
            'zongsu' => ($mother + $children),
            'mother' => $mother,
            'children' => $children
        );
        return $info;
    }
}
