<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class TMEmployee extends MEmployee {
    
    /**
     * Метод возвращает таблицу в XML
     */
    
    static function  getInXml ($cond) {
        $res=TMEmployee::model()->findAll($cond);                
        
        if (count($res)>0) {
            
            $doc = new DOMDocument('1.0','utf-8');
            $doc->formatOutput = true;
            $xml = $doc->createElement("employes");
            $doc->appendChild($xml);
            
            
            foreach($res as $key=>$value) {
                if ($cond->select=="*") {
                   $f2d=$value->attributeNames();
                } else {
                    $f2d=$cond->select;
                };
                                
                $e = $xml->appendChild($doc->createElement("employee"));
                
                foreach ($f2d as $field) {
                                 
                    $surname=$e->appendChild($doc->createElement("$field"));
                    $surname->appendChild($doc->createTextNode($value->$field));    
                };            
            };
            
            echo $doc->saveXml();   
            
      };
        
        
    }
    
}
?>
