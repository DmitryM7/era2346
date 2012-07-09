<?php
Yii::app()->getClientScript()->registerCoreScript('jQuery');
Yii::app()->getClientScript()->registerPackage('ui');
Yii::app()->getClientScript()->registerPackage('jqGrid');
Yii::app()->getClientScript()->registerPackage('main.menu');
?>
<link type="text/css" href="<?php echo Yii::app()->assetManager->baseUrl.'/Menu/css/style.css'?>" rel="stylesheet" />	        

<script type="text/javascript">
    function go2earch(rowid) {
        alert(rowid);
    }
    function log2YesNo(cellvalue, options, rowObject) {
        if (cellvalue==1) {
          return "ДА";  
        }
        else {
            return "НЕТ";
        }
    };
    
    $().ready(function() {
    function doOpenDay(dayp) {
    $.getJSON("<?php echo $this->createUrl('ajaxcrday') ?>",{day:dayp},function (data) {
                
                if (data.result) {
                    $("#openDay").dialog("close");
                }
                else
                    {
                        alert('Ошибка! Данные не сохранены');
                    }
            });            
        };                
        function doAdd(){
            doOpenDay($("#openDayDate").val());
        };
        function doCancel() {
            $("#openDay").dialog("close");
        }
        var dialogOpt={
            modal:true,
            autoOpen:false,
            buttons: { 
                 "Добавить": doAdd,
                 "Отменить": doCancel
                     }
        };
        $("#openDay").dialog(dialogOpt); 
        $("#add").bind('click',function () {         
         $("#openDay").dialog("open");
         $("#openDayDate").datepicker();
        });
        
                
      $('#tblOd').jqGrid({
          url:'<? echo $this->createUrl('ui/getOdList') ?>',
          datatype:'json',
          colNames:['УН(id)','Дата','Закрыт?','Заблокирован?'],
        colModel:[
            {name:'id',index:'id',width:50},
            {name:'opdate',index:'opdate',width:70},
            {name:'isclose',index:'isclose',width:70,formatter:log2YesNo},
            {name:'isblock',index:'isblock',width:90,formatter:log2YesNo}
        ],
        rowNum:50,
        rowList:[10,20,30,50,100],
        pager:'#tblOdNav',
        sortname:'id',
        viewrecords:true,
        caption:'ДЭВ в ОД',
        height:700,
        ondblClickRow:go2earch,
        subGrid:true,
        subGridUrl:'<?php echo $this->createUrl('getSummInfoOd')?>',
        subGridModel:[
            {
                name:['Автор','Кол-во док'],
                width:[30,30]
            }
        ]
      });
        
    });
    jQuery("#tblOd").jqGrid('navGrid','#tblOdNav',{edit:false,add:false,del:false});
</script>

<div>
    <table id="tblOd"></table>
    <div id="tblOdNav"></div>    
</div>

<div>
<input type="button" name="add" id="add" value="Открыть"/>
<input type="button" name="close" id="close" value="Закрыть"/>
<input type="button" name="block" id="block" value="Заблокировать"/>
</div>
<div id="openDay" title="Открыть ОД">
Выберите дату операционного дня: <input type="text" id="openDayDate" name="openDayDate"/>
</div>

<ul id="navigation">            
            <li class="earch"><a href="<?php echo $this->createUrl('index') ?>" id="Архив док-ов"><span>В архив</span></a></li>            
            
            <!--
            <li class="podcasts"><a href=""><span>Podcasts</span></a></li>
            <li class="contact"><a href=""><span>Contact</span></a></li>-->
        </ul>