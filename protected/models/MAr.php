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
    private $_xattrs=array();

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function getTableName()
    {
        return $this->getTableSchema()->name;
    }


    public function getXAttrList()
    {
        return array(
            array('name'=>'test1','type'=>'string')
        );
    }

    public function isXAttrDef($key)
    {
        foreach ($this->getXAttrList() as $xAttrDef) {
            if ($xAttrDef['name']=$key) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function getXAttrWDef($key, $defValue, $date = NULL)
    {
        $xa=$this->getXAttr($key,$date);
        return is_null($xa) ? $defValue : $xa;
    }

    public function fillAvailableXAttrs()
    {
        //todo Переделать на реальный запрос к БД
        $xattrs=array();
        foreach ($xattrs as $xattr) {
               $this->_xattrs[$xattr->name]=$xattr->value;
        };
    }

    public function getAvailableXAttrs()
    {
      return $this->_xattrs;
    }
    public function getXAttr($key, $date = NULL)
    {

    }

    protected function afterFind() {
        $this->fillAvailableXAttrs();
        return parent::afterFind();
    }
    protected function __get($name) {
        if ($this->isXAttrDef($name)) {
            return $this->getXAttr($name);
        }
        else {
            return parent::__get($name);
        }
    }
}
