<?php
/**
 * Created by JetBrains PhpStorm.
 * User: user
 * Date: 11/27/12
 * Time: 10:24 PM
 * To change this template use File | Settings | File Templates.
 */
class MAr extends CActiveRecord  implements IXattrable
{
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


}
