<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = '故事管理';
?>
<div>
    <h4>故事列表
        <a href="/addStory">
            <button class="btn btn-info btn-sm">
                <i class="glyphicon glyphicon-plus"></i>
            </button>
        </a>
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
            描述
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
                    <?= Html::encode("{$story->name}") ?>
                </td>
                <td>
                    <?= Html::encode("{$story->description}") ?>
                </td>
                <td>
                    <a href="/delStory">
                        <button class="btn btn-danger btn-sm">
                            <i class="glyphicon glyphicon-trash"></i>
                        </button>
                    </a>
                    <a href="/editStory">
                        <button class="btn btn-success btn-sm">
                            <i class="glyphicon glyphicon-edit"></i>
                        </button>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</div>