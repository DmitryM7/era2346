<?php
Yii::app()->clientScript->registerScript(
                 'loadxml',
                 "$.ajax({
                             url: http://localwww2/employee/list, 
                             dataType:'xml', // тип данных
                             success: function(xmlData){
                                        // при позитивном результате запроса в переменной xmlData хранится наш XML документ
                                        }
                        });",
                  CClientScript::POS_READY);
            ?>