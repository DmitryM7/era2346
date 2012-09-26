<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dmaslov
 * Date: 26.09.12
 * Time: 14:48
 * To change this template use File | Settings | File Templates.
 */
class MFinDoc extends MDoc implements ISignable
{
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    public function isChild($pid=null) {
        $this->getDbCriteria()->mergeWith(array(
            'condition'=>'pid IS NOT NULL'
        ));
        return $this;
    }

    /**
     * Method returns children documents.
     * @return mixed
     */
    public function getChildren() {
        $docs=self::model()->findAll(array('condition'=>'pid=:pid',
                'params'=>array(':pid'=>$this->id))
        );
        return $docs;
    }

    /**
     * Method checks if document is child and then delete.
     * If document is parent do nothing.
     * @param $id
     * @return mixed
     */
    public function delOnlyIfChild($id) {
        $doc=MDoc::model()->isChild()->findByPk($id);
        return $doc->delete();
    }

    /**
     * If document has sign method returns Sign Document,
     * else method returns false.
     * @param STRING $author
     * @param STRING $inspector
     * @return mixed
     */
    public function hasSign($author,$inspector) {
        $sign=MDoc::model()->find(array(
                'condition'=>'pid=:doc AND author=:author AND inspector=:inspector AND isdelete=0',
                'params'=>array(':doc'=>$this->primaryKey,':author'=>$author,':inspector'=>$inspector)
            )
        );
        return is_null($sign) ? FALSE : $sign;
    }

    /**
     * Method excludes documents with signs.
     * @param $author
     */
    public function withoutSign($author) {
        $this->getDbCriteria()->mergeWith(
            array('condition'=>'NOT EXISTS(SELECT * FROM doc AS child WHERE child.pid=t.id AND child.author=t.author'));
        return $this;
    }

    /**
     * @param MUser $whoAmI
     * @param Mixed $author
     * @param Binary $sign
     * @return bool
     */
    public function checkAndSign($whoAmI,$author,$sign) {
        if ($this->hasSign($author,$author)!==FALSE) {
            return false;
        };
        if (!$this->isResponsible($whoAmI)) {
            return false;
        };

        return $this->addSign($whoAmI,$author,$sign);
    }

    /**
     * Method adds sign to document.
     * @param $author
     * @param $inspector
     * @param $details
     * @return bool|mixed
     */
    public function addSign($author,$inspector,$details) {
        /**
         * Если документ, уже подписан,
         * то ничего не делаем.
         */
        if ($this->hasSign($author,$inspector)!==FALSE) {
            return false;
        };

        $tr=$this->dbConnection->beginTransaction();
        $this->nextStatus(__METHOD__);

        try {
            $doc=new MFinDoc();
            $doc->taxon=MDoc::signTaxon;
            $doc->author=$author;
            $doc->inspector=$inspector;
            $doc->details=$details;
            $doc->opdate=$this->opdate;
            $doc->pid=$this->id;

            $res1 = $doc->save();
            $res2 = $this->save();
        }
        catch (CException $e) {
            $tr->rollback();
        }

        if ($res1 && $res2) {
            $tr->commit();
            return true;
        }
        else {
            $tr->rollback();
            return false;
        }
    }

    /** Method returns all document signs. */
    public function getAllSigns() {
        $signs=MDoc::model()->findAll(
            array(
                'condtion'=>'pid=:pid AND isdelete=0',
                array(':pid'=>$this->id)
            ));
        return $signs;
    }
    public function getData2Sign() {

    }
}
