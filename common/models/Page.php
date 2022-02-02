<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "text".
 *
 * @property int $id
 * @property string $slug
 * @property string $title
 * @property string $body
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_description
 */
class Page extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'page';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['slug', 'title'], 'required'],
            [['body'], 'string'],
            [['slug', 'title', 'meta_title', 'meta_keywords', 'meta_description'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'slug' => 'Slug',
            'title' => 'Title',
            'body' => 'Body',
            'meta_title' => 'Meta Title',
            'meta_keywords' => 'Meta Keywords',
            'meta_description' => 'Meta Description',
        ];
    }
}
