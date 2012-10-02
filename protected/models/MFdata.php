<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dmaslov
 * Date: 02.10.12
 * Time: 14:16
 * To change this template use File | Settings | File Templates.
 */
class MFdata extends  Fdata
{

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function byPid($pid) {
        $this->getDbCriteria()->mergeWith(array(
            'condition'=>'pid=:pid',
            'params'=>array(':pid'=>$pid)
        ));
        return $this;
    }

}
