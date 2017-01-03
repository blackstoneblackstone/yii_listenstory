<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = '故事管理';
?>
<div>
    <div class="alert alert-warning">
        注册用户总数: <label class="label label-info"> <?= $info['zongshu']; ?></label>
        妈妈用户数: <label class="label label-success"> <?= $info['mother']; ?></label>
        孩子用户数: <label class="label label-danger"> <?= $info['children']; ?></label>
        <a style="float: right" href="http://mta.qq.com/h5/base/ctr_realtime_data?app_id=500380141" class="btn btn-warning">更多统计分析</a>
    </div>
    <hr>
    <h4>故事列表
        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#addStory">
            <i class="glyphicon glyphicon-plus"></i>
        </button>
    </h4>
    <hr>
    <table class="table table-bordered">
        <thead>
        <th>
            序号
        </th>
        <th>
            展示图
        </th>
        <th>
            故事名
        </th>
        <th>
            音频
        </th>
        <th>
            大小
        </th>
        <th>
            持续时间
        </th>
        <th>
            创建时间
        </th>
        <th>
            收藏次数
        </th>
        <th>
            分享次数
        </th>

        <th>
            操作
        </th>

        </thead>

        <tbody>
        <?php foreach ($stories as $story): ?>
            <tr>
                <td>
                    <?= Html::encode("{$story->id}") ?>
                </td>
                <td>
                    <img style="height: 50px" src="<?= "/mobile/{$story->img}" ?>">
                </td>
                <td>
                    <span class="tool" data-toggle="tooltip" data-placement="right"
                          title=<?= Html::encode("{$story->description}") ?>>
                    <?= Html::encode("{$story->name}") ?>
                    </span>
                </td>
                <td>
                    <audio src="/mobile/mp3/<?= Html::encode("{$story->id}") ?>.mp3" controls
                           preload="metadata"></audio>
                </td>
                <td>
                    <?= Html::encode("{$story->size}") ?>
                </td>
                <td>
                    <?= Html::encode("{$story->duration}") ?>
                </td>
                <td>
                    <?= Html::encode("{$story->time}") ?>
                </td>
                <td>
                    <?= Html::encode("{$story->starnum}") ?>
                </td>
                <td>
                    <?= Html::encode("{$story->sendnum}") ?>
                </td>
                <td>
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                            onclick="imgUpBtn(<?= Html::encode("{$story->id}") ?>)">
                        展示图片上传
                    </button>
                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                            onclick="audioUpBtn(<?= Html::encode("{$story->id}") ?>)">
                        音频上传
                    </button>
                    <button class="btn btn-success btn-sm"
                            onclick="editStory(<?= Html::encode("{$story->id}") ?>,'<?= Html::encode("{$story->name}") ?>','<?= Html::encode("{$story->description}") ?>')">
                        <i class="glyphicon glyphicon-edit"></i>
                    </button>
                    <button class="btn btn-danger btn-sm" onclick="delStory(<?= Html::encode("{$story->id}") ?>)">
                        <i class="glyphicon glyphicon-trash"></i>
                    </button>

                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</div>


<div class="modal fade" id="imgUpModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span
                        aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">上传图片</h4>
            </div>
            <form>
                <div class="modal-body">
                    <div class="alert alert-success">
                        <input name="storyid" type="text" class="hidden storyid">
                        <input name="UploadForm[file]" type="file" id="imgUp">
                        <label class="label label-success" id="imgUpText"></label>
                    </div>
                    <div class="progress progress-striped active" id="imgProgress" style="display: none">
                        <div id="imgBar" class="progress-bar progress-bar-success" role="progressbar"
                             style="width: 0%;">
                            0%
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary" id="imgUploadBtn">上传</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade" id="audioUpModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span
                        aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">上传音频</h4>
            </div>
            <form>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <input name="storyid" type="text" class="hidden storyid">
                        <input name="UploadForm[file]" type="file" id="audioUp">
                    </div>
                    <div class="progress progress-striped active" id="audioProgress" style="display: none">
                        <div id="audioBar" class="progress-bar progress-bar-success" role="progressbar"
                             style="width: 0%;">0%
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary" id="audioUploadBtn">上传</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="addStory">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span
                        aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">添加故事</h4>
            </div>
            <div class="modal-body">
                <div class="control-group">
                    <label class="control-label" for="input01">故事的名字</label>
                    <div class="controls">
                        <input type="text" id="addName" name="name" class="form-control" placeholder="">
                        <p class="help-block">请输入故事的名字，名字不宜太长，10个字符以内</p>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="input01">故事的描述</label>
                    <div class="controls">
                        <input type="text" id="addDesc" name="description" class="form-control" placeholder="">
                        <p class="help-block">请用一句话介绍故事的大概内容,50字以内.</p>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary" onclick="addSave()">保存</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" id="editStory">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span
                        aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">编辑故事</h4>
            </div>
            <div class="modal-body">
                <div class="control-group">
                    <label class="control-label" for="input01">故事的名字</label>
                    <div class="controls">
                        <input type="text" id="editName" name="name" class="form-control" placeholder="">
                        <p class="help-block">请输入故事的名字，名字不宜太长，10个字符以内</p>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="input01">故事的描述</label>
                    <div class="controls">
                        <input type="text" id="editDesc" name="description" class="form-control" placeholder="">
                        <p class="help-block">请用一句话介绍故事的大概内容,50字以内.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary" onclick="eidtSave()">保存</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
