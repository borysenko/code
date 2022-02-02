<?php

namespace common\models;

class ProductPriceUpdate extends \yii\base\Model
{
    public $upload;
    public $filePath;

    public function init()
    {
        $this->filePath = \Yii::getAlias('@frontend/web/upload/price.csv');
        parent::init();
    }

    public function rules()
    {
        return [
            [['upload'], 'file'],
        ];
    }

    public function updatePrice()
    {
        $row = 1;
        $prg = 0;
        if (($handle = fopen($this->filePath, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);
                $row++;
                if ($row > 8) {
                    for ($c = 0; $c < $num; $c++) {

                        if ($model = Product::find()->where(['code' => $data[1]])->one()) {
                            $model->price = (double)preg_replace("/[^x\d|*\.]/", "", $data[5]);
                            $model->save(false);
                            $prg++;
                        }
                    }
                }
            }
            fclose($handle);
        }

        return $prg;

    }
}
