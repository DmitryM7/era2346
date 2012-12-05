<?php
/**
 * Created by JetBrains PhpStorm.
 * User: user
 * Date: 11/27/12
 * Time: 10:23 PM
 * To change this template use File | Settings | File Templates.
 */
interface IXattrable
{
    public function getXAttr($key,$defaultValue);
    public function setXAttr($key,$value);
}
