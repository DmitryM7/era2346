<?
Yii::app()->getClientScript()->registerCoreScript('jQuery');
Yii::app()->getClientScript()->registerPackage('ui');
Yii::app()->getClientScript()->registerPackage('jqGrid');
?>
<script>
    $().ready(function () {
        
   $('#users_parent').jqGrid({
        url:'<? echo $this->createUrl('ajaxGetParentUsers') ?>',
        datatype:'json',
        colNames:['Ун. ном','Фамилия','Имя','Отчество'],
        colModel:[
            {name:'id',index:'id',width:50},
            {name:'surname',index:'surname',width:70},
            {name:'name',index:'name',width:65},
            {name:'patronymic',index:'patronymic',width:75}
        ],
        rowNum:50,
        rowList:[10,30,50,100],
        pager:'user_parent_pager',
        multiselect:false,
        caption:'Контроллеры',        
        onSelectRow:function (ids) {
            
            if(ids != null) 
                {                     
                    $("#users_child").jqGrid('setGridParam',{url:"<?php echo $this->createUrl('ajaxGetChildUsers') ?>?q=1&pid="+ids,page:1}); 
                    $("#users_child").jqGrid('setCaption',"Invoice Detail: "+ids) .trigger('reloadGrid'); 
                                              
        };}
    });
    
    jQuery("#users_child").jqGrid({
        height: 100, 
        url:"<? echo $this->createUrl('ajaxGetChildUsers') ?>", 
        datatype: "json", 
        colNames:['Ун. ном','Фамилия','Имя','Отчество'], 
        colModel:[
            {name:'id',index:'id',width:50},
            {name:'surname',index:'surname',width:70},
            {name:'name',index:'name',width:65},
            {name:'patronymic',index:'patronymic',width:75}
        ],
        rowNum:50,
        rowList:[10,30,50,100],
        pager:'user_child_pager',
        multiselect: true,
        caption:"Исполнители для:" }).navGrid('#users_child_pager',{add:false,edit:false,del:false});
    });            
</script>
<div style="position: relative;left:40px">
<table id="users_parent"></table>
<div id="user_parent_pager"></div>    
</div>

<div style="position: relative;left:40px">
<table id="users_child"></table>
<div id="user_child_pager"></div>    
</div>