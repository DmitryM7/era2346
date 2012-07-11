<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dmaslov
 * Date: 11.07.12
 * Time: 17:52
 * To change this template use File | Settings | File Templates.
 */
class SimpleAuthForm extends CAction
{
    public function run() {
       $this->controller->render('simpleauthform');
    }

}
