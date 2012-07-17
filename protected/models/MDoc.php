<?php
/**
 * @property string  $ext1
 * 
 */
//todo Переделать в абстрактный класс
class MDoc extends Doc implements ISignable
{          
        CONST visaPermit ='note visa permit simple';
        CONST visaDeny   ='note visa deny simple';
        CONST stickAlert  = 'note message alert simple';
        CONST stickNotice  = 'note message notice simple';
        CONST stickError= 'note message error simple';


    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public static function getClassCode() {
        return $this->taxon;
    }

    function startsWith($haystack, $needle)
      {
          $length = strlen($needle);
          return (substr($haystack, 0, $length) === $needle);
      }
    function endsWith($haystack, $needle)
      {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        $start  = $length * -1; //negative
        return (substr($haystack, $start) === $needle);
    }

    public function getChildren() {
            $docs=self::model()->findAll(array('condition'=>'pid=:pid',
                                               'params'=>array(':pid'=>$this->id))
                                        );
            return $docs;
    }

    public function delCurrent() {
            return $this->markDelete($this->primaryKey);
    }

    /**
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
         * @param type $opdate
         * @param type $userid
         * @return type 
         */
    public static function getDocByOpDateUser($opdate,$userid)
      {
            //todo Переделать на использование сохранных критериев
            /**
             * Отсекаем подписи.
             * Или другими словами выводим только детей.
             * 
             */
            $docs=MDoc::model()->findAll(array("condition"=>"class='".self::getClassCode()."' AND opdate=:opdate AND author=:userid AND isdelete=0 AND pid IS NULL 
                                                AND NOT EXISTS(SELECT * FROM doc AS cdoc WHERE cdoc.pid=t.id AND cdoc.author=t.author)",
                                               "params"=>array(":opdate"=>$opdate,":userid"=>$userid)
                                               )
                                        );
            return $docs;
     }
     public static function getDocByOpDateInspector($opdate,$userid)
      {
            //todo Переделать на использование сохранных критериев
            $docs=MDoc::model()->findAll(array("condition"=>"class='".self::getClassCode()
                    ."' AND opdate=:opdate AND inspector=:inspector AND isdelete=0 AND pid IS NULL AND NOT EXISTS(SELECT * FROM doc AS cdoc WHERE cdoc.pid=t.id AND cdoc.inspector=t.inspector)",
                                               "params"=>array(":opdate"=>$opdate,":inspector"=>$userid)
                                               )
                                        );
            return $docs;
      }
      //todo Передалать функцию на подпись инстанцированного объекта
      public function addSign($author,$inspector,$details)
        {
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

        public function takeAuthor($user) {
            $this->author=$user->un2;
            return $this;
        }
        public function takeInspector($user) {
            $this->inspector=$user->un2;
            return $this;
        }

        public function nextStatus($action=null)
        {
            $connection=Yii::app()->db;
            $command=$connection->createCommand("SELECT MIN(id) FROM status WHERE id>".$this->status);
            $nextStatus=$command->queryScalar();
            $this->status=$nextStatus;            
        }
        protected function beforeValidate()
        {
            if ($this->isNewRecord) 
            {
                $this->dt=new CDbExpression('NOW()');
            };
            
            if (is_null($this->status))
            {
                //todo Ввести на статусах реквизит Начальный в зависимости от него устанавливать значение
                $connection=Yii::app()->db;
                $command=$connection->createCommand("SELECT MIN(id) FROM status");
                $this->status=$command->queryScalar();
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

    public function rules()
    {
        return array(array("details","required"));
    }
}
?>
