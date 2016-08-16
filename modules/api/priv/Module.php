<?php
namespace app\modules\api\priv;

class Module extends \yii\base\Module
{
    public function init()
    {
        parent::init();

        $this->modules = [
            'v1' => [
                // you should consider using a shorter namespace here!
                'class' => 'app\modules\api\priv\v1\Module',
            ],
        ];
    }
}