<?
$cs = Yii::app()->clientScript;
 
$cs->registerCssFile(Yii::app()->request->baseUrl.'/jqgrid/css/ui.jqgrid.css');
$cs->registerCssFile(Yii::app()->request->baseUrl.'/jqgrid/css/ui-lightness/jquery-ui-1.7.2.custom.css');
 
$cs->registerScriptFile(Yii::app()->request->baseUrl.'/jqgrid/js/jquery-1.5.2.min.js');
$cs->registerScriptFile(Yii::app()->request->baseUrl.'/jqgrid/js/i18n/grid.locale-ru.js');
$cs->registerScriptFile(Yii::app()->request->baseUrl.'/jqgrid/js/jquery.jqGrid.min.js');
?>
<table id="list1"></table> <div id="pager1"></div>
<script type="text/javascript"> 
    jQuery().ready(function (){ 
        jQuery("#list1").jqGrid({ 
            url:'/employee/xml', 
            datatype: "xml", 
            colNames:['Inv No','Date', 'Client', 'Amount','Tax','Total','Notes'], 
            colModel:[ {name:'id',index:'id', width:75}, 
                {name:'invdate',index:'invdate', width:90}, 
                {name:'name',index:'name', width:100}, 
                {name:'amount',index:'amount', width:80, align:"right"}, 
                {name:'tax',index:'tax', width:80, align:"right"}, 
                {name:'total',index:'total', width:80,align:"right"}, 
                {name:'note',index:'note', width:150, sortable:false} ], 
            rowNum:20, 
            autowidth: true, 
            rowList:[10,20,30], 
            pager: jQuery('#pager1'), 
            sortname: 'id', 
            viewrecords: true, 
            sortorder: "desc", 
            caption:"XML Example" }).navGrid('#pager1',{edit:false,add:false,del:false}); 
    });
</script>     



