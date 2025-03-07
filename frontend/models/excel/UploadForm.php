<?php

namespace frontend\models\excel;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    public $excelFile;

    public function rules()
    {
        return [
            [['excelFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xls, xlsx'],
        ];
    }
}
