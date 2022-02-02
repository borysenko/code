<?php

namespace frontend\modules\shop\controllers;

use common\models\Order;
use common\models\OrderItem;
use common\models\Product;
use common\models\ProductSize;
use common\models\ProductTranslate;
use yz\shoppingcart\ShoppingCart;

class CartController extends \yii\web\Controller
{
    public function actionItemsInCart()
    {
        return \Yii::$app->cart->getCount();
    }

    public function actionAdd($id, $size_id = null)
    {
        if ($size_id) {
            $query = ProductSize::find()->where([ProductSize::tableName() . '.id' => $size_id]);
        } else {
            $query = Product::find()->where([Product::tableName() . '.id' => $id]);
        }

        $product = $query->one();

        if ($product) {
            \Yii::$app->cart->put($product, \Yii::$app->request->get('quantity'));
            //\Yii::$app->session->setFlash('growl', 'Товар добавлен в корзину!');
            //return $this->goBack();
        }
    }

    public function actionList()
    {
        if (\Yii::$app->request->get('update') && \Yii::$app->request->get('id')) {
            if(\Yii::$app->request->get('size')) {
                $product = ProductSize::findOne(\Yii::$app->request->get('id'));
            } else {
                $product = Product::findOne(\Yii::$app->request->get('id'));
            }
            if ($product) {
                \Yii::$app->cart->update($product, \Yii::$app->request->get('quantity'));
            }
        }

        if (\Yii::$app->request->get('remove') && \Yii::$app->request->get('id')) {
            if(\Yii::$app->request->get('size')) {
                $product = ProductSize::findOne(\Yii::$app->request->get('id'));
            } else {
                $product = Product::findOne(\Yii::$app->request->get('id'));
            }
            if ($product) {
                \Yii::$app->cart->remove($product);
            }
        }
        /* @var $cart ShoppingCart */
        $cart = \Yii::$app->cart;

        $products = $cart->getPositions();
        $total = $cart->getCost();

        return $this->renderAjax('list', [
            'products' => $products,
            'total' => $total,
        ]);
    }

    public function actionRemove($id)
    {
        if(\Yii::$app->request->get('size')) {
            $product = ProductSize::findOne($id);
        } else {
            $product = Product::findOne($id);
        }
        if ($product) {
            \Yii::$app->cart->remove($product);
            $this->redirect(['cart/list']);
        }
    }

    public function actionUpdate($id, $quantity)
    {
        $product = Product::findOne($id);
        if ($product) {
            \Yii::$app->cart->update($product, $quantity);
            $this->redirect(['cart/list']);
        }
    }

    public function actionOrder()
    {
        $order = new Order();

        /* @var $cart ShoppingCart */
        $cart = \Yii::$app->cart;

        /* @var $products Product[] */
        $products = $cart->getPositions();
        $total = $cart->getCost();

        if ($order->load(\Yii::$app->request->post()) && $order->validate()) {
            $transaction = $order->getDb()->beginTransaction();
            $order->save(false);

            foreach ($products as $product) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->code = $product->code;
                if($product instanceof ProductSize) {
                    $orderItem->title = $product->product->title . ' ' . $product->size->name;
                    $orderItem->product_id = $product->product->id;
                } else {
                    $orderItem->title = $product->title;
                    $orderItem->product_id = $product->id;
                }
                $orderItem->price = $product->getPrice();
                $orderItem->quantity = $product->getQuantity();
                if (!$orderItem->save(false)) {
                    $transaction->rollBack();
                    \Yii::$app->session->addFlash('error', 'Cannot place your order. Please contact us.');
                    return $this->redirect('catalog/list');
                }
            }

            $transaction->commit();
            \Yii::$app->cart->removeAll();

            \Yii::$app->session->addFlash('success', \Yii::t('shop', 'Thanks for your order. We\'ll contact you soon.'));
            $order->sendEmail();

            return $this->redirect('success');
        }

        return $this->render('order', [
            'order' => $order,
            'products' => $products,
            'total' => $total,
        ]);
    }

    public function actionSuccess()
    {
        return $this->render('success');
    }
}
