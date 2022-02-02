<?php

namespace common\models;

use common\components\MultilingualQuery;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsTrait;
use navatech\language\Translate;
use omgdef\multilingual\MultilingualBehavior;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yiidreamteam\upload\FileUploadBehavior;
use yz\shoppingcart\CartPositionInterface;
use yz\shoppingcart\CartPositionTrait;

/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property integer $category_id
 * @property string $price
 *
 * @property Image[] $images
 * @property OrderItem[] $orderItems
 * @property Category $category
 */
class Product extends ActiveRecord implements CartPositionInterface
{
    use CartPositionTrait;
    public $size_id;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_product';
    }

    public function behaviors()
    {
        return [
            'ml' => [
                'class' => MultilingualBehavior::className(),
                'languages' => Language::getLanguagesAsArray(),
                'defaultLanguage' => 'ru',
                'langForeignKey' => 'shop_product_id',
                'tableName' => ProductTranslate::tableName(),
                'attributes' => [
                    'title',
                    'description',
                    'meta_title',
                    'meta_description',
                ]
            ],
            [
                'class' => '\yiidreamteam\upload\ImageUploadBehavior',
                'attribute' => 'image',
                'thumbs' => [
                    'thumb' => ['width' => 300, 'height' => 300],
                    'preview' => ['width' => 400, 'height' => 400],
                    'ico' => ['width' => 270, 'height' => 270],
                ],
                'filePath' => '@frontend/web/upload/shop/products/[[attribute_slug]]_[[pk]].[[extension]]',
                'fileUrl' => '/upload/shop/products/[[attribute_slug]]_[[pk]].[[extension]]',
                'thumbPath' => '@frontend/web/upload/shop/products/thumb/[[attribute_slug]]_[[profile]]_[[pk]].[[extension]]',
                'thumbUrl' => '/upload/shop/products/thumb/[[attribute_slug]]_[[profile]]_[[pk]].[[extension]]',
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'action', 'novelty', 'not_available', 'deliveryFree'], 'integer'],
            [['price', 'price_old'], 'number'],
            [['code', 'collection_code', 'brand'], 'string', 'max' => 250],
            [['filter'], 'safe'],
            [['title_ru', 'category_id'], 'required'],
            ['image', 'image', 'extensions' => 'jpg, jpeg, gif, png'],
            [['title', 'slug'], 'required'],
            [['title', 'title_uk', 'title_en', 'slug', 'video'], 'string', 'max' => 255],
            [['description', 'description_uk', 'description_en'], 'safe'],
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
            'title' => Yii::t('shop', 'Title'),
            'description' => Yii::t('shop', 'Description'),
            'title_uk' => Yii::t('shop', 'Title_uk'),
            'description_uk' => Yii::t('shop', 'Description_uk'),
            'title_en' => Yii::t('shop', 'Title_en'),
            'description_en' => Yii::t('shop', 'Description_en'),
            'code' => Yii::t('shop', 'Code'),
            'category_id' => Yii::t('shop', 'Category'),
            'price' => Yii::t('shop', 'Price'),
            'image' => Yii::t('shop', 'Image'),
            'price_old' => Yii::t('shop', 'Price Old'),
            'novelty' => Yii::t('shop', 'Novelty'),
            'action' => Yii::t('shop', 'Action'),
            'not_available' => Yii::t('shop', 'Not available'),
        ];
        return $attributeLabels;
    }


    public static function find()
    {
        return new MultilingualQuery(get_called_class());
    }

    static function getActions()
    {
        $data = [];
        $groups = self::find()->with('category')->where(['action' => true])->groupBy('category_id')->all();
        foreach ($groups as $group) {
            $items = self::find()->where(['category_id' => $group->category_id, 'action' => true])->all();
            $data[] = ['category' => $group->category->title, 'items' => $items];
        }

        return $data;
    }

    /**
     * @return Image[]
     */
    public function getImages()
    {
        return $this->hasMany(Image::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOption()
    {
        return $this->hasMany(ProductOption::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOptionTranslate()
    {
        return $this->hasMany(ProductOption::className(), ['product_id' => 'id'])->multilingual();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslate()
    {
        return $this->hasMany(ProductTranslate::className(), ['shop_product_id' => 'id'])->where(['language' => Yii::$app->language]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSize()
    {
        return $this->hasMany(ProductSize::className(), ['product_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    public function getPic($attribute, $profile = 'thumb', $emptyUrl = null)
    {
        $file = $this->getThumbFileUrl($attribute, $profile, $emptyUrl);
        if(!is_file(Yii::getAlias('@frontend/web' . $file))) {
            $file = $emptyUrl;
        }
        return $file;
    }


}
