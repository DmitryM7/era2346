<?php

class MOpdate extends Opdate
{
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
   
   public static function isClose($od)
    {
        $opdate=MOpdate::model()->find(array('condition'=>'opdate=:opdate AND isclose=0',
                                             'params'=>array(':opdate'=>$od)
                                           )
                                     );
        
        if (!is_null($opdate))
        {            
            return 0;
        }
        else
        {
           return 1;    
        }
        
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
