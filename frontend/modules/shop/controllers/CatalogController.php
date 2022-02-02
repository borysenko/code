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

class CatalogController extends \yii\web\Controller
{
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            Url::remember();
            return true;
        } else {
            return false;
        }
    }

    public function actionList($slug, $id)
    {
        /** @var Category $category */

        if (!$model = Category::findOne(['slug' => $slug, 'id' => $id])) {
            throw new NotFoundHttpException('Категории нет!');
        }

        $sort = null;
        if(Yii::$app->request->get('sort')) {
            $sort = Yii::$app->request->get('sort');
        }

        $id = $model->id;

        $category = null;

        $categories = Category::find()->indexBy('id')->orderBy('id')->all();

        $productsQuery = Product::find();
        if ($id !== null && isset($categories[$id])) {
            $category = $categories[$id];
            $productsQuery->where([Product::tableName() . '.category_id' => $this->getCategoryIds($categories, $id)]);
        }

        $productsQueryClone = clone $productsQuery;
        $filters_array = FilterCheckboxWidget::getListFilters($productsQueryClone);

        $filters = ProductOption::getFilters($productsQuery, $filters_array);

        foreach ($filters_array as $group => $filter) {
            $productsQuery->innerJoin(ProductOption::tableName() . " `po_{$group}`", Product::tableName() . ".id = `po_{$group}`.product_id");
            $productsQuery->andOnCondition(["`po_{$group}`.slug_option" => $group]);
            $productsQuery->andOnCondition(['IN', "`po_{$group}`.slug", $filter]);
            $productsQuery->andOnCondition(["`po_{$group}`.is_filter" => 1]);
        }

        $productsDataProvider = new ActiveDataProvider([
            'query' => $productsQuery,
            'pagination' => [
                'pageSize' => 18,
                'pageSizeParam' => false,
                'forcePageParam' => false,
                'class' => \common\components\Pagination::className(),
            ],
            'sort' => [
                'defaultOrder' => [
                    'price' => SORT_ASC,
                ]
            ],
        ]);

        return $this->render('list', [
            'category' => $category,
            'filters' => $filters,
            'sort' => $sort,
            'productsQueryClone' => $productsQueryClone,
            'productsDataProvider' => $productsDataProvider,
        ]);
    }

    public function actionSearch($search_str)
    {
        $sort = null;
        if(Yii::$app->request->get('sort')) {
            $sort = Yii::$app->request->get('sort');
        }

        $productsQuery = Product::find();
        if(!empty($search_str)) {
            $productsQuery->joinWith('translate');
            $productsQuery->where(['like', 'title', $search_str]);
        }
        $productsDataProvider = new ActiveDataProvider([
            'query' => $productsQuery,
            'pagination' => [
                'pageSize' => 12,
                'pageSizeParam' => false,
                'forcePageParam' => false
            ],
        ]);

        return $this->render('search', [
            'sort' => $sort,
            'productsDataProvider' => $productsDataProvider,
        ]);
    }

    public function actionView()
    {
        return $this->render('view');
    }

    public function actionSearchAjax($search_str)
    {
        $categories = Category::find()->joinWith('translate')->where(['like', 'title', $search_str])->all();
        $products = Product::find()->joinWith('translate')->where(['like', 'title', $search_str])->limit(10)->all();

        return $this->renderAjax('search-ajax', [
            'categories' => $categories,
            'products' => $products
        ]);
    }

    /**
     * @param Category[] $categories
     * @param int $activeId
     * @param int $parent
     * @return array
     */
    private function getMenuItems($categories, $activeId = null, $parent = null)
    {
        $menuItems = [];
        foreach ($categories as $category) {
            if ($category->parent_id === $parent) {
                $menuItems[$category->id] = [
                    'active' => $activeId === $category->id,
                    //'options' => ['data' => ['parent_id' =>$category->parent_id, 'id' =>$category->id]],
                    'label' => $category->title,
                    'url' => ['catalog/list', 'slug' => $category->slug],
                    'items' => $this->getMenuItems($categories, $activeId, $category->id),
                ];
            }
        }
        return $menuItems;
    }


    /**
     * Returns IDs of category and all its sub-categories
     *
     * @param Category[] $categories all categories
     * @param int $categoryId id of category to start search with
     * @param array $categoryIds
     * @return array $categoryIds
     */
    private function getCategoryIds($categories, $categoryId, &$categoryIds = [])
    {
        foreach ($categories as $category) {
            if ($category->id == $categoryId) {
                $categoryIds[] = $category->id;
            } elseif ($category->parent_id == $categoryId) {
                $this->getCategoryIds($categories, $category->id, $categoryIds);
            }
        }
        return $categoryIds;
    }
}
