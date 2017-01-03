<?php
/**
 * Created by PhpStorm.
 * User: lihb
 * Date: 12/19/16
 * Time: 5:55 PM
 */

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

/**
 * UploadForm is the model behind the upload form.
 */
class UploadForm extends Model
{
    /**
     * @var UploadedFile file attribute
     */
    public $storyid;
    public $file;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['file'], 'file']
        ];
    }
}