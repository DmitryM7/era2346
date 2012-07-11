<?php
/**
 * Utils
 */
class SysClass {

    /**
     * @param string $point1 
     * @param string $point2
     * @param string $point3
     * @param mixed  $defaultValue
     * @return mixed
     * 
     * This method returns param from main config.
     */
    public static function getSetting() {
        $args=func_get_args();
        $defaultValue=array_pop($args);
        $tree=Yii::app()->params;
        return self::extractSetting($args,$defaultValue,$tree);
    }

/**
 * @static
 * @param array $args Путь к НП
 * @param string $defaultValue Значение по-умолчанию
 * @param array $tree Откуда берем НП
 * @return mixed
 *
 * Возвращает значение пути $args из
 * дерева $tree. Если такого пути нет, то
 * возвращает $defaultValue.
 */
    public static function extractSetting($args,$defaultValue,$tree) {
        $lth=count($args)-1;
        for($i=0;$i<=$lth;$i++) {
            $point=$args[$i];            
                if (isset($tree[$point])) {
                        if ($i<>$lth) {
                            $tree=$tree[$point];
                        } else {
                            return $tree[$point];
                        };
                } else {
                    return $defaultValue;
                };
        };
    }
    
    public static function getXAttr($tbl,$id,$code) {
       $xattr= MXattr::model()->find(array(
                                        "condition"=>"tlb=:tbl AND pid=:id AND code=:code",
                                        "params"=>array(":tbl"=>$tbl,":id"=>$id,":code"=>$code)
                                    ));
       return !is_null($xattr) ? $xattr->value : null;
    }
    
/**
 *
 * @param type $tbl
 * @param type $classcode
 * @param type $id
 * @param type $code 
 * Устанавливает значение расширенного реквизита,
 * если значение равно null, то запись в БД удаляется.
 * Если запись уже есть и новое значение <> null, то запись изменяется.
 */
    public static function setXAttr($tbl,$classcode,$id,$code,$value) {

        $xattr=self::etXAttr($tbl,$id,$code);

        if (!is_null($xattr)) {

            if (is_null($value)) {
                $res=$xattr->delete();
            } else {
                $xattr->value=$value;
                $res=$xattr->save();
            };
            
        } else {
            $xattr = new MXattr();
            $xattr->tbl=$tbl;
            $xattr->classcode=$classcode;
            $xattr->pid=$id;
            $xattr->code=$code;
            $xattr->value=$value;
            $res=$xattr->save();
        }

        return $res;
    }

    public static function mimeByExt($ext) {
        $ext_mime=array('jpe'=>'image/jpeg','jpeg'=>'image/jpeg','jpg'=>'image/jpeg');
        return !empty($ext_mime[$ext]) ? $ext_mime[$ext] : 'image/jpeg';
    }

    public static function extByMime($mime) {

    }
}