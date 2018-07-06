<?php

class m180627_075702_update_affiliate_id_from_person extends \yii\mongodb\Migration
{
    public function up()
    {
      $updated_persons = 0;

      foreach (\app\models\Person::find()->all() as $person) {
        $person->affiliate_id = "AF" . $person->short_id;
        $person->update(false);
        $updated_persons++;
      }

      echo "m180627_075702_update_affiliate_id_from_person: $updated_persons person updated.\n";
    }

    public function down()
    {
        echo "m180627_075702_update_affiliate_id_from_person cannot be reverted.\n";

        return false;
    }
}
