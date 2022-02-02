<?php

namespace frontend\modules\shop\controllers;

use common\models\Category;
use common\models\CategoryTranslate;
use common\models\Product;
use common\models\ProductOption;
use common\models\ProductTranslate;
use frontend\modules\shop\widgets\FilterCheckboxWidget;
use yii\data\ActiveDataProvider;
use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class ProductController extends \yii\web\Controller
{
    public function actionView($slug, $id)
    {
        if(!$model = Product::find()->where(['id' => $id])->one()) {
            throw new NotFoundHttpException('Данного товара нет!');
        }

        return $this->render('view', [
            'model' => $model
        ]);
    }

}
