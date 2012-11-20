<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dmaslov
 * Date: 20.11.12
 * Time: 13:45
 * To change this template use File | Settings | File Templates.
 */
return array(
    // this is used in contact page
    'adminEmail'=>'admin@era2346.ru',
    // Контролер отправки форм
    'finspector'=>'ADMNRE',
    //Тип идентификации
    // Возможные варианты {ad,simple}
    'auth'=>array(
        'type'=>'simple'
    ),
    'docValidateRules'=>array(
        'checkIfOpDateExists'=> true,
        'checkIfOpDateOpen'  => true,
        'checkIfOpDateClose' => true,
    )
);