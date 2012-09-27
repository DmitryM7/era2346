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
        $docs=self::model()->findAll(array(
                'condition'=>'pid=:pid AND taxon<>:signTaxon',
                'params'=>array(':pid'=>$this->id,':signTaxon'=>MDoc::signTaxon))
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
        $doc=self::model()->isChild()->findByPk($id);
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
        $sign=MSign::model()->byPid($this->id)->byAuthor($author);
        if ($sign instanceof ISign) {
            return $sign;
        } else {
            return false;
        }
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
     * Method finds sign doc. If method can't find sign, then add sign.
     * @param MUser $whoAmI
     * @param Mixed $author
     * @param Binary $sign
     * @return bool
     */
    public function checkAndSign($whoAmI,$inspector,$sign) {

        if ($this->hasSign($whoAmI,$inspector)!==FALSE) {
            return false;
        };

        if (!$this->isResponsible($whoAmI)) {
            return false;
        };

        return $this->addSign($whoAmI,$inspector,$sign);
    }

    public function addSign($author,$inspector,$details) {
        /** If document has sign then exit. */
        if ($this->hasSign($author,$inspector)!==FALSE) {
            return false;
        };


        $this->nextStatus(__METHOD__);

        $sign=new MSign();
        return $sign->setAuthor($author)
                    ->setDetaisl($details)
                    ->save();

    }
    public function getSigns() {
        $signs=MSign::model()->byPid($this->id);
        return $signs;
    }
    public function getData2Sign() {

    }
    public function hasErrorSign() {

    }

}
