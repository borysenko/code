<?php

namespace common\models;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

Class Language extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'language';
    }

    public static function getLanguages()
    {
        return self::find()->orderBy('sort')->all();
    }

    public static function getLanguagesAsArray()
    {
        return ArrayHelper::map(self::getLanguages(), 'code', 'code');
    }
}