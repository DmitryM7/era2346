<?php
/**
 * @property string  $ext1
 * 
 */
class MDoc extends Doc
{          
        CONST visaPermit ='note visa permit simple';
        CONST visaDeny   ='note visa deny simple';
        CONST stickAlert  = 'note message alert simple';
        CONST stickNotice  = 'note message notice simple';
        CONST stickError= 'note message error simple';


        public static function getClassCode() {
            return 'doc';
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
            $docs=self::model()->findAll(array('condition'=>'pid=:pid','params'=>array(':pid'=>$this->id)));
            return $docs;
        }
        
        public function rules()
        {
            return array(array("details","required"));
        }
        public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}        
        public static function del($fltjson)
        {
            $fltobj=json_decode($fltjson);
                        
            /**
             * Документы из электронного архива не удаляются,
             * а помечаются удаленными. При этом подписи так же
             * помечаются удаленными.
             */
            $con=Yii::app()->db;
            $command=$con->createCommand();
            $docs=MDoc::model()->findAll(array("condition"=>"expn=:expn","params"=>array(":expn"=>$fltobj->expn)));

            $lstp=array();
            
            foreach ($docs as $doc)
            {
                $lstp[]=$doc->id;
            }
            $tran=$con->beginTransaction();
            $res=0;
            try {
                    $res=$command->update("doc",array("isdelete"=>"1"),"expn=:expn",array(":expn"=>$fltobj->expn));
                    foreach ($lstp as $value)
                    {
                        $res=$res+$command->update("doc",array("isdelete"=>"1"),"pid=:pid",array(":pid"=>$value));
                    }
                    $tran->commit();
                }
                catch (Exception $e)
                {
                       $tran->rollBack();
                       $res=0;
                }
             
            return $res;
        }
        public function delCurrent() {
            return $this->markDelete($this->primaryKey);
        }

        public function delOnlyIfChild($id) {
            $doc=MDoc::model()->isChild()->findByPk($id);
            return $doc->delete();

        }
        protected function markDelete($id) {
            $doc=MDoc::model()->findByPk($id);
            $doc->isdelete=1;
            
            $signs=MDoc::model()->findAll(array('condition'=>'pid=:pid','params'=>array(':pid'=>$this->id)));

            
           $tr=$doc->dbConnection->beginTransaction();
           
           try {
               $doc->save();
                foreach ($signs as $sign) {
                    $sign->isdelete=1;
                    $sign->save();
                };              
           } catch (CException $e) {
               $tr->rollback();
               return FALSE;
           }
            $tr->commit();
            return TRUE;
        }
        public static function getChildByFlt($flt) {
            $fltobj = CJSON::decode($flt);
            $command = Yii::app()->db->createCommand();
            
            $command->select('doc.id as did,expn,author,inspector,dt')
                    ->from(doc)                    
                    ->where("pid=:pid
                             AND isdelete=0",
                                array(":pid"=>$fltobj['pid'])
                             );
                    //->order($fltobj['sidx']." ".$fltobj['sord']);
            
            $rows=$command->queryAll();
            
            $res=array();
            $i=0;
            foreach ($rows as $row)
            {        
                $res[$i]['id']=$row['did'];
                $res[$i]['cell']=array($row['did'],$row['author'],$row['dt']);
                $i++;
            }
            
            $resobj->rows=$res;
            return $resobj;
                    
            
        }
        /**
         *
         * @param string $opdate 
         * 
         */
        public static function getDocByOpDate($opdate) {
            $command=Yii::app()->db->createCommand();
            $command->select('id, opdate, expn, author, inspector, fext,pid')
                    ->from(doc)
                    ->where("class=:class AND opdate=:opdate AND isDelete=0 AND pid IS NULL",
                            array(":class"=>self::getClassCode(),":opdate"=>$opdate)
                            )
                    ->order('opdate, inspector');
            $res=$command->queryAll();
            
            return $res;                    
        }

        public static function getDocByFlt($flt) {
            $fltobj = CJSON::decode($flt);
            $command =  Yii::app()->db->createCommand();
            
            $classcode=isset($fltobj['classcode'])?$fltobj['classcode']:self::getClassCode();
            
        
                $command->select('COUNT(*) AS FC')
                    ->from(doc)
                    ->join("status","doc.status=status.id")
                    ->where("FIND_IN_SET(class,:classcode)
                                            AND opdate=:opdate
                                            AND author LIKE :author 
                                            AND inspector LIKE :inspector 
                                            AND expn LIKE :expn 
                                            AND FIND_IN_SET(status,:status)
                                            AND isdelete=0 AND pid IS NULL",
                                array(":classcode"=>$fltobj['classcode'],
                                      ":opdate"=>$fltobj['opdate'],
                                      ":author"=>"%".$fltobj['author']."%",
                                      ":inspector"=>"%".$fltobj['inspector']."%",
                                      ":expn"=>"%".$fltobj['expn']."%",
                                      ":status"=>$fltobj['status']
                                     )
                             );
                    
           $count=$command->queryScalar();
            
            $resobj = new StdClass();
            $resobj->total = ceil($count/$fltobj['limit']);
            
            if ($fltobj['page']>$resobj->total) $fltobj['page']=$resobj->total;

            $start=$fltobj['limit']*$fltobj['page']-$fltobj['limit'];
            
            $resobj->page=$fltobj['page'];
            $resobj->records=$count;
            
            $command1 =  Yii::app()->db->createCommand();
            $command1->select('doc.id as did,expn,author,inspector,title,status.name as st,dt')
                    ->from(doc)
                    ->join("status","doc.status=status.id")
                    ->where("FIND_IN_SET(class,:classcode)
                             AND opdate=:opdate
                             AND author LIKE :author
                             AND inspector LIKE :inspector
                             AND expn LIKE :expn
                             AND FIND_IN_SET(status,:status)
                             AND isdelete=0 AND pid IS NULL",
                                array(":classcode"=>$fltobj['classcode'],
                                      ":opdate"=>$fltobj['opdate'],
                                      ":author"=>"%".$fltobj['author']."%",
                                      ":inspector"=>"%".$fltobj['inspector']."%",
                                      ":expn"=>"%".$fltobj['expn']."%",
                                      ":status"=>$fltobj['status']
                                     )
                             )                    
                    ->offset($start)
                    ->limit($fltobj['limit'])
                    ->order($fltobj['sidx']." ".$fltobj['sord']);
                    
            
            $rows=$command1->queryAll();
            
            $res=array();
            $i=0;                     
            foreach ($rows as $row)
            {        
                $res[$i]['id']=$row['did'];
                $res[$i]['cell']=array($row['did'],$row['expn'],$row['author'],$row['inspector'],$row['title'],$row['st'],$row['dt']);
                $i++;
            }
            
            $resobj->rows=$res;
            return $resobj;
        }
        /**
         *
         * @param type $opdate
         * @param type $userid
         * @return type 
         */
        public static function getDocByOpDateUser($opdate,$userid)
        {   
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
            $docs=MDoc::model()->findAll(array("condition"=>"class='".self::getClassCode()
                    ."' AND opdate=:opdate AND inspector=:inspector AND isdelete=0 AND pid IS NULL AND NOT EXISTS(SELECT * FROM doc AS cdoc WHERE cdoc.pid=t.id AND cdoc.inspector=t.inspector)",
                                               "params"=>array(":opdate"=>$opdate,":inspector"=>$userid)
                                               )
                                        );
            return $docs;
        }        
        public static function addSign($pid,$author,$inspector,$details)
        {
            $pdoc=MDoc::model()->findByPk($pid);
            
            /**
             * Если документ, уже подписан,
             * то ничего не делаем.
             */
            if ($pdoc->hasSign($author,$inspector)!==FALSE) {
                return false;
            };

            $tr=$pdoc->dbConnection->beginTransaction();
            $pdoc->nextStatus();
            
            try {
            $doc=new MDoc();
            $doc->author=$author;
            $doc->inspector=$inspector;
            $doc->details=$details;
            $doc->opdate=$pdoc->opdate;
            $doc->pid=$pdoc->id;
            
            $res1 = $doc->save();
            $res2 = $pdoc->save();            
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
        /**
         * Сохраняем документ
         */
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
                $connection=Yii::app()->db;
                $command=$connection->createCommand("SELECT MIN(id) FROM status");
                $this->status=$command->queryScalar();
            };
            return parent::beforeValidate();
        }
        public function save()
        {                                                           
            //if (!MOpdate::isClose($this->opdate))
            //{
                return parent::save();
            //}
            //else
            //{
              //  throw new Exception('Day is Close or Not Open!');
            //}
        }

        public function isChild($pid=null) {
            $this->getDbCriteria()->mergeWith(array(
                'condition'=>'pid IS NOT NULL'
            ));
            return $this;
        }
        public function sign($whoAmI,$authorUn2,$sign) {
            $tr=$this->dbConnection->beginTransaction();
            $this->nextStatus();
            try {
                $doc=new MDoc();
                $doc->author=$authorUn2;
                $doc->author=$authorUn2;
                $doc->detaisl=$sign;
                $doc->opdate=$this->opdate;
                $doc->pid=$this->id;
                $res1 = $doc->save();
                $res2 = $this->save();

            } catch (CException $e) {
                $tr->rollback();
            };

            if ($res1 && $res2) {
                $tr->commit();
                return true;
            }
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

            return $this->sign($whoAmI,$author,$sign);
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

        public function taxon($num=null) {
            $a=explode(' ',$this->class);
            return $a[$num];
        }
}
?>
