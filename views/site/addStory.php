<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = '故事管理';
?>
<div>
    <h4>
        添加故事
    </h4>
    <hr>
    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
        'action' => ['/saveStory'],
        'method' => 'post'
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['autofocus' => true])->label("故事名") ?>

    <?= $form->field($model, 'description')->textInput()->label("描述") ?>

    <!-- Button trigger modal -->
    <div class="form-group field-story-name">
        <label class="col-lg-1 control-label" for="story-name">展示图片</label>
        <div class="col-lg-3">
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#imgUpModal">
                展示图片上传
            </button>
        </div>
        <div class="col-lg-8">
            <div class="help-block help-block-error "></div>
        </div>
    </div>

    <div class="form-group field-story-name">
        <label class="col-lg-1 control-label" for="story-name">音频</label>
        <div class="col-lg-3">
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#audioUpModal">
                音频上传
            </button>
        </div>
        <div class="col-lg-8">
            <div class="help-block help-block-error "></div>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('保存', ['class' => 'btn btn-success', 'name' => 'login-button']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<div class="modal fade" id="imgUpModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span
                        aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">上传图片</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-success">
                    <input name="UploadForm[file]" type="file" id="imgUp">
                    <label class="label label-success" id="imgUpText"></label>
                </div>
                <div class="progress progress-striped active" id="imgProgress" style="display: none">
                    <div id="imgBar" class="progress-bar progress-bar-success" role="progressbar" style="width: 0%;">
                        0%
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary" id="imgUploadBtn">上传</button>
            </div>
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
            <div class="modal-body">
                <div class="alert alert-info">
                    <input name="UploadForm[file]" type="file" id="audioUp">
                </div>
                <div class="progress progress-striped active" id="audioProgress" style="display: none">
                    <div class="progress-bar progress-bar-success" role="progressbar" style="width: 0%;">0%</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary" id="audioUploadBtn">上传</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
