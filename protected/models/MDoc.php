<?php
/**
 * @property string  $ext1
 * 
 */
//todo Переделать в абстрактный класс
class MDoc extends Doc implements ISignable, IStatusable
{          
        CONST visaPermit ='note visa permit simple';
        CONST visaDeny   ='note visa deny simple';
        CONST stickAlert  = 'note message alert simple';
        CONST stickNotice  = 'note message notice simple';
        CONST stickError= 'note message error simple';
        CONST signTaxon = 'sign';


    public static function model($className=__CLASS__)
    {
        return parent::model($className);
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
     * Delete current document.
     * @return int
     */
    public function delCurrent() {
            return $this->markDelete($this->primaryKey);
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
     * Method marks document and his children as deleted.
     * @param $id PK Parent document
     * @return int
     */
   protected function markDelete() {
         $signs=MDoc::model()->findAll(array(
                                             'condition'=>'pid=:pid',
                                             'params'=>array(':pid'=>$this->id)
                                             ));

         $tr=$this->dbConnection->beginTransaction();
           
         try {
              $this->isdelete=1;
               if ($this->save()) {
                   foreach ($signs as $sign) {
                       $sign->isdelete=1;
                       //todo Сделать откат в случае ошибки удаления хотя бы одного документа
                       $sign->save();
                   };
               };
           } catch (CException $e) {
               $tr->rollback();
               return FALSE;
           }
         $tr->commit();
         return TRUE;
    }

    /**
     *
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
            $this->nextStatus();
            
            try {
            $doc=new MDoc();
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

   public function getAllSigns() {
        $signs=MDoc::model()->findAll(
            array(
                'condtion'=>'pid=:pid AND isdelete=0',
                array(':pid'=>$this->id)
            ));
        return $signs;
    }

   public function takeAuthor($user) {
            $this->author=$user->un2;
            return $this;
    }

   public function takeInspector($user) {
            $this->inspector=$user->un2;
            return $this;
    }

   //todo Обеспечить хождение по статусам.
   public function nextStatus($action=null) {

   }

   protected function beforeValidate() {
            if ($this->isNewRecord) 
            {
                $this->dt=new CDbExpression('NOW()');
            };
            
            if (is_null($this->status))
            {
                $begStatus=MStatus::getBegin();
                $this->status=$begStatus->primaryKey;
            };

            return parent::beforeValidate();
      }

   public function isChild($pid=null) {
            $this->getDbCriteria()->mergeWith(array(
                'condition'=>'pid IS NOT NULL'
            ));
            return $this;
     }

   /**
     * Method filters parent documents by opdate and author.
     * @param DATE $opdate
     * @param CHAR $author
     * @return MDoc
     */
   public function byDateUser($opdate,$author) {
       $this->getDbCriteria()->mergeWith(
          array('condition'=>'opdate=:opdate AND author=:author AND isdelete=0 AND pid IS NULL',
                'params'=>array(':opdate'=>$opdate,
                ':userid'=>$author)
               ));
            return $this;
     }

   /**
     * Method filters parent documents by opdate and author.
     * @param DATE $opdate
     * @param CHAR $inspector
     */
   public function byDateInspector($opdate,$inspector) {
     $this->getDbCriteria()->mergeWith(
         array('condition'=>'opdate=:opdate AND inpector=:author AND isdelete=0 AND pid IS NULL',
               'params'=>array(':opdate'=>$opdate,
                               ':userid'=>$inspector)
        ));
        return $this;
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
         *  Проверяет возможность подписи и в случае
         * выполнения всех условий подписывает документ.
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
         * Возвращает указатель на объект подписи,
         * если такой имеется или FALSE.
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

        /** Проверяем является ли пользователь $user
         * автором или контроллером документа.
         * */
   public function isResponsible($user) {
            //По заявке #1032
            //Временно ввожу возможность
            //подписи для любого пользователя.

            //По заявке #1011
            // разрешаю подпись служебным пользователям
            // сотрудникам соответствующих подразделений
            if ($this->author==$user->un2 || $this->inspector==$user->un2
                || ($this->author=="BNK-CL" && $this->startsWith($this->author,"04101")) ||($this->author=="PLASTIK" && $this->startsWith($this->author,"04110"))
               ) {
                return true;
            } else {
              return false;
            };
        }

   public function rules() {
        return array(array("details","required"));
    }
}
?>
