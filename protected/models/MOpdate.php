<?php

class MOpdate extends Opdate
{
    CONST dayIsClose=0;
    CONST dayIsOpen=1;
    CONST dayIsNotOpen=3;

    public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
   /**
    * Возвращает true если день opdate закрыт
    * @param date od
    * @return bool
    */
   protected function beforeValidate()
   {
       /*** Если данные в руском виде ***/
       if (strpos($this->opdate,'.')>0) {
           $day=substr($this->opdate,0,2);
           $month=substr($this->opdate,3,2);
           $year=substr($this->opdate,6,4);
           $this->opdate="$year-$month-$day";
       };
           return parent::beforeValidate();
   }

    /**
     * If operation day is close then method returns false,
     * else method returns true.
     * @param $od Operation day
     * @return int
     */
    public static function isClose($od)
    {
        return STATIC::getStatus($od)==STATIC::dayIsClose ? TRUE : FALSE;
    }

    /**
     * If operation day is open then method returns true, else
     * method returns false.
     * @param $od
     * @return int
     */
    public static function isOpen($od) {
        return STATIC::getStatus($od)==STATIC::dayIsOpen ? TRUE : FALSE;
    }

    /**
     * If operation day record doesn't exists in database returns true,
     * else returns false.
     * @param $od
     * @return bool
     */
    public static function isNotOpen($od) {
        return STATIC::getStatus($od)==STATIC::dayIsNotOpen ? TRUE : FALSE;
    }

    public static function dayPermitSaveDocuments($od) {
            $dayStatus=STATIC::getStatus($od);

        if (SysClass::getSetting('docValidateRules','checkIfOpDateExists',TRUE) && $dayStatus==STATIC::dayIsNotOpen) {
            return false;
        };

        if (SysClass::getSetting('docValidateRules','checkIfOpDateOpen',TRUE) && $dayStatus!=STATIC::dayIsOpen) {
            return false;
        };

        if (SysClass::getSetting('docValidateRules','checkIfOpDateClose',TRUE) && $dayStatus==STATIC::dayIsClose) {
            return false;
        };

        return true;

    }
    public static function getStatus($od) {

        $opdate=MOpdate::model()->findByAttributes(array(
                'opdate'=>$od
            )
        );

        if ($opdate instanceof IOpDate) {

            switch ($opdate->isclose) {
                case "0":
                    return STATIC::dayIsOpen;
                    break;
                case "1":
                    return STATIC::dayIsClose;
                    break;
            };

        };

        return STATIC::dayIsNotOpen;
    }


    /**
     * Закрывает операционный день
     * @param date $od
     * @return type 
     */
    public static function close($od)
    {
        $opdate=MOpdate::model()->find(array('condition'=>'opdate=:opdate','params'=>array(':opdate'=>$od)));
        $opdate->isclose=1;
        return $opdate->save();        
    }
    
    /**
     * Открывает операционный день
     * @param date $od
     * @return type 
     */
    public static function open($od)
    {
        $opdate=MOpdate::model()->find(array('condition'=>'opdate=:opdate','params'=>array(':opdate'=>$od)));
        if (!is_null($opdate)) {
            $opdate->isclose=0;
            return $opdate->save();
        }
        else
        {
            $opdate=new MOpdate();
            $opdate->opdate=$od;
            return $opdate->save();
            
        }
    }

    /**
     * Возвращает открытые операционные дни
     */
    public static function getOpenDays() {
        $days = MOpdate::model()->findAll(array(
                                                'condition'=>'isClose=0'
                                                )
                                          );
        
        return $days;
    }
    
    public static function getUnSaveDays() {
        $days = MOpdate::model()->findAll(array(
            'condition'=>'isclose=1 AND (issave=0 OR issave IS NULL)'
        ));
        return $days;
    }
}
?>
