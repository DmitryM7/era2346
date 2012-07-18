<?php

class MStatus extends Status {

  public static function model($className=__CLASS__)
  {
		return parent::model($className);
  }

    /**
     * @static
     *
     */
    public static function getBegin() {
     $begStatus = self::model()->find(array('condition'=>'isbegin=:0'));
     return $begStatus->primaryKey;
    }
};        
