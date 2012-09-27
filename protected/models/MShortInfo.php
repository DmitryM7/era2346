<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dmaslov
 * Date: 27.09.12
 * Time: 16:20
 * To change this template use File | Settings | File Templates.
 */
class MShortInfo extends MDoc
{

    public static function getMainTaxon() {
        return "info";
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function markDelete() {
     $this->isdelete=1;
     return $this->save();
    }

    public function defaultScope() {
        return array(
           'condition'=>"taxon LIKE '".self::getMainTaxon()."%'"
        );
    }
}
