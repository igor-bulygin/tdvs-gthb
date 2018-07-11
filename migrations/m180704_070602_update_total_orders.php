<?php

class m180704_070602_update_total_orders extends \yii\mongodb\Migration
{
    public function up()
    {
        $updated_orders = 0;

        foreach (\app\models\Order::find()->all() as $order) {
            $order->total = $order->subtotal;
            $order->update(false);
            $updated_orders++;
        }

        echo "m180704_070602_update_total_orders: $updated_orders orders updated.\n";
    }

    public function down()
    {
        echo "m180704_070602_update_total_orders cannot be reverted.\n";

        return false;
    }
}
