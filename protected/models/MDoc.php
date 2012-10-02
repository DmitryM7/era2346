<?php
/**
 * @property string  $ext1
 * 
 */
abstract class MDoc extends Doc implements IStatusable
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
     * Method takes ownership.
     * @param $user
     * @return MDoc
     */
   public function takeAuthor($user) {
            $this->author=$user->un2;
            return $this;
    }

   /** Method takes document's control */
   public function takeInspector($user) {
            $this->inspector=$user->un2;
            return $this;
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
    * Is $user author or inspector of document?
    * @param MUser $user
    * @return bool
    **/
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

   protected function onBeforeStatusUpdate($oldStatus,$newStatus) {

    }
   protected function onAfterStatusUpdate($newStatus) {

    }

   public function setStatus($status) {
        $this->onBeforeStatusUpdate($this->status,$status);
        $this->status=$status;
        if ($this->save()) {
            $this->onAfterStatusUpdate($this->status);
        };
        return $this;
    }
   public function getStatus() {
        return $this->status;
    }

   abstract public function markDelete();
   abstract public static function getMainTaxon();
    public function byPid($pid) {
        $this->getDbCriteria()->mergeWith(array(
            'condition'=>'pid=:pid',
            'params'=>array(':pid'=>$pid)
        ));
        return $this;
    }
   public function rules() {
        return array(array("details","required"));
    }
}
?>
