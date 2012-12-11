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
    public function getXAttrList();
    public function isXAttrDef($key);
    public function getTableName();
    public function getXAttr($key,$date=null);
    public function getXAttrWDef($key,$defValue,$date=null);
    public function fillAvailableXAttrs();
    public function getAvailableXAttrs();
    public function setXAttr($key,$value);
    public function delXAttr($key);
}
