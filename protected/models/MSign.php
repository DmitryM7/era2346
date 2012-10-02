<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dmaslov
 * Date: 27.09.12
 * Time: 13:21
 * To change this template use File | Settings | File Templates.
 */
class MSign extends MDoc implements ISign, ISingleFile
{
    private $_currSignFile=null;

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public static function getMainTaxon() {
        return 'sign';
    }
    /**
     * Method finds file with sign.
     * @return null
     */
    protected function findSignFile() {
        if (is_null($this->_currSignFile)) {
            $this->_currSignFile=MFdata::model()->byPid($this->id)->find();
        };
        return $this->_currSignFile;
    }

    public function setDetails($details) {
        if (is_null($this->getSignFile())) {
            $fdata=MFdata();
            $fdata->pid=$this->id;
            $fdata->name=MDoc::signTaxon.$this->id;
            $fdata->fext='txt';
            $fdata->mt='text/txt';
            $fdata->data=$details;
        } else {
            $this->getSignFile()->data=$details;
        }
        return $this;
    }
    public function getDetails() {
        $file=$this->getFile();
        return $file->data;
    }
    public function getFile() {
        $this->findSignFile();
        return $this->_currSignFile;
    }

    public function getSize() {
        return $this->_currSignFile->fsize;
    }
    public function save() {
        $tr=$this->dbConnection->beginTransaction();

        if (!$this->getSignFile()->save()) {
            $tr->rollback();
            return false;
        };

        if (!parent::save()) {
            $tr->rollback();
            return false;
        };

        $tr->commit();
        return false;
    }

    public function byAuthor($author) {
        $this->getDbCriteria()->mergeWith(array(
            'condition'=>'author=:author',
            'params'=>array(':author'=>$author)
        ));
        return $this;
    }

    public function markDelete() {
        $this->isdelete=1;
        return $this->save();
    }

    public function defaultScope() {
        return array(
            'condition'=>"taxon='".self::getMainTaxon()."'"
        );
    }
}
