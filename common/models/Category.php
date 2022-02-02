<?php

namespace common\models;

use common\components\MultilingualQuery;
use omgdef\multilingual\MultilingualBehavior;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $title
 * @property string $slug
 *
 * @property Category $parent
 * @property Category[] $categories
 * @property Product[] $products
 */
class Category extends ActiveRecord
{
    public function behaviors()
    {
        return [
            'ml' => [
                'class' => MultilingualBehavior::className(),
                'languages' => Language::getLanguagesAsArray(),
                'defaultLanguage' => 'ru',
                'langForeignKey' => 'shop_category_id',
                'tableName' => CategoryTranslate::tableName(),
                'attributes' => [
                    'title',
                    'body',
                    'meta_title',
                    'meta_description',
                ]
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id'], 'default', 'value' => null],
            [['parent_id'], 'integer'],
            [['title', 'slug'], 'required'],
            [['title', 'title_uk', 'title_en', 'slug'], 'string', 'max' => 255],
            [['body', 'body_uk', 'body_en'], 'safe'],
            [['meta_title', 'meta_title_uk', 'meta_title_en'], 'string', 'max' => 255],
            [['meta_description', 'meta_description_uk', 'meta_description_en'], 'string', 'max' => 255],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $attributeLabels = [
            'parent_id' => Yii::t('shop', 'Parent'),
            'title' => Yii::t('shop', 'Title'),
            'body' => Yii::t('shop', 'Body'),
            'title_uk' => Yii::t('shop', 'Title_uk'),
            'body_uk' => Yii::t('shop', 'Body_uk'),
            'title_en' => Yii::t('shop', 'Title_en'),
            'body_en' => Yii::t('shop', 'Body_en'),
        ];
        return $attributeLabels;
    }

    public static function find()
    {
        return new MultilingualQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Category::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslate()
    {
        return $this->hasMany(CategoryTranslate::className(), ['shop_category_id' => 'id']);
    }

    public static function asArray()
    {
        $categories = [];
        $models = Category::find()->multilingual()->all();
        foreach ($models as $model) {
            $categories[$model->id] = [
                'id' => $model->id,
                'parent_id' => $model->parent_id,
                'name' => $model->title,
            ];
        }
        return $categories;
    }
}
