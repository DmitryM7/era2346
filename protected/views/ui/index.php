<?php
Yii::app()->getClientScript()->registerCoreScript('jQuery');
Yii::app()->getClientScript()->registerPackage('ui');
Yii::app()->getClientScript()->registerPackage('jqGrid');
Yii::app()->getClientScript()->registerPackage('mfupload');
Yii::app()->getClientScript()->registerPackage('main.menu');
Yii::app()->getClientScript()->registerPackage('checkbox');
Yii::app()->getClientScript()->registerPackage('menu.right.click');
Yii::app()->getClientScript()->registerPackage('jqn');
Yii::app()->getClientScript()->registerPackage('uni.form');
Yii::app()->getClientScript()->registerPackage('main.page');
Yii::app()->getClientScript()->registerPackage('superfish');
Yii::app()->getClientScript()->registerPackage('onlineInformer');
Yii::app()->getClientScript()->registerPackage('wall');
Yii::app()->getClientScript()->registerPackage('jquery.ms');
Yii::app()->getClientScript()->registerPackage('adorn');

Yii::app()->getClientScript()->registerScriptFile(Yii::app()->assetManager->baseUrl.'/CryptoPro/cryptopro.js');

?>
<script>

// Получаем список документов для jqGrid
function addSign(urlProvider) {
    var selectRows = $('#rowed3').getGridParam('selarrrow');

    $.each(selectRows,function (index,id) {

        currPercent=Math.ceil((index+1)*100/selectRows.length);

        $.ajax({
            url:'<?php echo $this->createUrl('ajaxGetInfoBase64') ?>',
            success: function(result) {
                var sign = SignCreate('<?php echo $user->email; ?>',result);

                $.ajax({
                    url:urlSign,
                    success: function (result) {

                    },
                    async:false,
                    data:{
                        'id':id,
                        'details':sign
                    },
                    type:'post'
                });
            },
            async:false,
            data:{
                'id':id
            },
            type:'post'
        });
    });
};
function getDocList()
{
    var postData;

    var checkedItems1 = $('#typeDoc').multiselect("getChecked").map(function(){
        return this.value;
    }).get().join();

    var checkedItems2 = $('#typeStatus').multiselect("getChecked").map(function(){
        return this.value;
    }).get().join();
    postData={};
    $.extend(postData,{
        opdate:$("#opdate").val(),
        author:$("#author").val(),
        inspector:$("#inspector").val(),
        expn:$("#expn").val(),
        classcode:checkedItems1,
        status   :checkedItems2
    });

    $("#rowed3").jqGrid('setGridParam',{'postData':postData});
    $("#rowed3").trigger('reloadGrid');
    $(this).dialog('close');
};        

function delDoc(id) {
    $.ajax({        
        url:'<?php echo $this->createUrl('ajaxdelDoc') ?>',
        data:{
            'id':id
        },
        success:function (result) {
            alert(result);
            $("#rowed3").trigger('reloadGrid');
        }
    });

}

// Получаем информацию по конкретному документу
function getInfo(cid) {
        $("#info").html();
        $("#info").load('<?php echo $this->createUrl('ajaxGetInfo') ?>',{id:cid,preview:1});
               
    };    


$().ready(function () {
    $('ul.sf-menu').superfish({autoArrows : true});
    $('#rwdg').accordion({autoHeight: false});

    $('#whoPresent').onlineInformer({
        delay :'10000',
        url   :'http://localwww2/ui/markUser',
        whoAmI:'dmaslov@domain.ru'
    });

    $('#wall').wall({
        'delay':5000,
        'url'  :'http://localwww2/ui/showWall'
     });
/*** Правокнопочное меню **/
var eventsMenu = {
    menuStyle: {
        width:'150px'
    },
    bindings: {
        'addAnswer' : function(t) {
            $('#answForm').resetForm();
            $('#answForm').clearForm();
            $('input:file').MultiFile('reset')
            $('#form_pid').val(t.id);
            $('#answMenu').dialog('open');                        
        },
        'addSignMnu':function (t) {
            addSign('<?php echo $this->createUrl("addSign2"); ?>');
        },
        'showDoc':function (t) {
            window.open('<?php echo $this->createUrl('ajaxGetInfo')?>?id='+t.id+'&type=pdf','inf')
        },
        'delDoc':function (t) {
            delDoc(t.id);
        }
    }
};
/** МЕНЮ **/
$('#addDoc').bind('click',function () {       
  $('#docupload').dialog("open");
});
$('#openFltr').bind('click',function () {
        $('#fltfrm').dialog("open");
    })

$('#addForm').bind('click',function () {
    $('#add_form_dialog').dialog("open");
});


$("#splash1").animate({opacity:1.0},3000).fadeOut("slow");


$('#signSelected').bind('click',function () {
    var selectRows = $('#rowed3').getGridParam('selarrrow');

$("#progressbar").progressbar({
                              value:0                                        
                              });
                              
   $.each(selectRows,function (index,id) {

   currPercent=Math.ceil((index+1)*100/selectRows.length);
           
           $.ajax({
                url:'<?php echo $this->createUrl('ajaxGetInfoBase64') ?>',                   
                success: function(result) {
                        var sign = SignCreate('<?php echo $user->email; ?>',result);
                        
                        $.ajax({                            
                            url:'<?php echo $this->createUrl('ajaxaddSign') ?>',
                            success: function (result) {                                                                                                  
                                $("#progressbar").progressbar('option','value',currPercent);
                            },
                            async:false,
                            data:{
                                'id':id,
                                'details':sign
                            },
                            type:'post'
                        });
                      },
                    async:false,
                    data:{
                        'id':id
                        },
                    type:'post'
                });        
                
        });
    $('#progressbar').progressbar('destroy');
    $.notification('Все документы подписаны!');
    $("#rowed3").trigger('reloadGrid');
    });
    
    
  autoOpt={
          source:"<?php echo $this->createUrl("ajaxGetUser")?>"            
        };    
    
$("#rowed3").jqGrid({
        url:'<? echo $this->createUrl('ajaxgetTable') ?>',
        datatype:"json",
        colNames:['Ун. ном','Рейс','Автор','Контролер','Тема','Статус','Выгружен'],
        colModel:[
            {name:'id',index:'did',width:50},
            {name:'expn',index:'expn',width:70},
            {name:'author',index:'author',width:50},
            {name:'inspector',index:'inspector',width:50},
            {name:'title',index:'title',width:100},
            {name:'sign',index:'st',width:60},
            {name:'dt',index:'dt',width:120}
        ],
        rowNum:50,
        rowList:[10,20,30,50,100,500,1000],
        pager:'#pager3',
        sortname:'did',
        viewrecords:true,
        caption:'ДЭВ в ОД',
        height:700,
        width:700,
        //onSelectRow:getInfo,
        afterInsertRow: function (rowid,rowdata,rowelem) {
          $('#'+rowid).contextMenu('MenuJqGrid',eventsMenu);
        },
        ondblClickRow: function (rowid) {
          getInfo(rowid);
        },
        onHeaderClick: function (stat) {
            if (stat=="hidden") {
                $('#wall').show();
            } else {
                $('#wall').hide();
            };
        },
        subGrid : true,
        subGridRowExpanded: function(subgrid_id, row_id) {
            var subgrid_table_id;
            subgrid_table_id = subgrid_id+'_t';
            
            $('#'+subgrid_id).html('<div id="'+subgrid_table_id+'"></div>');
            $('#'+subgrid_table_id).load('<?php echo $this->createUrl('getAnsw') ?>',{pid:row_id});
        },        
        subGridUrl: '<?php echo $this->createUrl('ajaxgetSubTable') ?>',
        subGridModel: [
            {
                name:['Id','Автор','Время'],
                width: [55,120,120]
            }
        ],
        
        multiselect: true
    });
    
    $("#pager3").jqGrid('navGrid','#prowed3',{edit:false,add:false,del:false});
    $("#rowed3").jqGrid('bindKeys',{'onEnter':getInfo});
                                $('#fltfrm').find('input:checkbox:not([safari])').checkbox();
                                $('#fltfrm').find('input:radio').checkbox();
				
        $('#info').draggable();    
        $("#opdate").datepicker();
        $("#add_form_opdate").datepicker();
        $(".author").autocomplete(autoOpt);
        $(".inspector").autocomplete(autoOpt);        
        $('#upl_date').datepicker();
        $('#upl_inspector').autocomplete(autoOpt);


/** ОПРЕДЕЛЕНИЕ ДИАЛОГОВ **/
$('#answForm').ajaxForm();
$('#answMenu').dialog({
    autoOpen:false,
    closeOnEscape:true,
    buttons:{
        'Добавить':function () {$('#answForm').submit();$.notification('Форма добавлена!');}
    }
});
    $('#docupload').dialog({
                                   autoOpen:false,
                                   closeOnEscape:true,
                                   buttons:{
                                      'Добавить':function () {$('#add_doc').submit();}
                                    }
                               });
        
        $('#fltfrm').dialog({
                            autoOpen:false,
                            closeOnEscape:true,
                            modal: true,
                            width:450,
                            buttons:{
                                      'Найти':getDocList                                      
                                    }
                        });
                        
        $('#add_form_dialog').dialog({
                            autoOpen:false,
                            closeOnEscape:true,
                            buttons:{
                                      'Добавить':function () {$('#add_form').submit();}
                                    }
                       });                                                                     


$('#find_fltr').click(function (e) {
    $('#fltfrm').dialog("open");
    e.preventDefault();
});

$('form.uniForm').uniform();

    $('.sf-menu').find('a').click(function(e) {
        var action=$(this).attr('href');

       switch (action) {
           case "#aa1":
               // Произвольный фильтр
               $('#fltfrm').dialog("open");
               e.preventDefault();
               break;
           case "#bb1":
               alert('123');
               break;
       };
    });
    $('#fltfrm').adorn();
    $("#typeStatus").multiselect("widget").find(":checkbox:not(:checked)").each(function(){
        this.click();
    });
    })
</script>

<div class="span-24 header">
    <ul class="sf-menu sf-js-enabled sf-shadow header">
        <li>
            <a href="#aa" class="sf-with-ul">Фильтры</a>
            <ul>
                <li><a href="#aa1">Произвольный фильтр</a></li>
                <li><a href="#aa2">Моя пачка</a></li>
                <li><a href="#aa3">Нужно подписать</a></li>
            </ul>
        </li>
        <li>
            <a href="#bb" class="sf-with-ul">Коллеги</a>
            <ul>
                <li><a href="#bb1">Список</a></li>
                <li><a href="#bb2">sfsfs</a></li>
            </ul>
        </li>
        <li>
            <a href="#cc">ДНИ</a>
        </li>
        <li>
            <a href="#c" class="sf-with-ul">Меню</a>
            <ul>
                <li><a href="#c1" class="sf-with-ul">Фильтр</a></li>
            </ul>
        </li>

    </ul>
</div>

<div id="main_window" title="Электронный архив" class="span-24 zi1">
<div class="span-18 last zi1">
    <table id="rowed3"></table>    
    <div id="pager3"></div>
    <div id="wall" class="hidden">

    </div>
</div>

<div id="rwdg" class="span-6 last">
    <h2><a href="#">Данные дня</a></h2>
    <div>
        Здесь будет отображаться инф. по дню
    </div>
    <h2><a href="#">Коллеги в сети</a></h2>
    <div id="whoPresent"></div>
    <h2><a href="#">Погода</a></h2>
    <div>
        <a href="http://clck.yandex.ru/redir/dtype=stred/pid=7/cid=1228/*http://pogoda.yandex.ru/moscow"><img src="http://info.weather.yandex.net/moscow/1.ru.png" border="0" alt=""/><img width="1" height="1" src="http://clck.yandex.ru/click/dtype=stred/pid=7/cid=1227/*http://img.yandex.ru/i/pix.gif" alt="" border="0"/></a>
    </div>
    <h2><a href="#">Примечания к документу</a></h2>
    <div>

    </div>
</div>


<script type="text/javascript">
                        $(function() {
                var d=300;
                $('#navigation a').each(function(){
                    $(this).stop().animate({
                        'marginTop':'-80px'
                    },d+=150);
                });

                $('#navigation > li').hover(
                function () {
                    $('a',$(this)).stop().animate({
                        'marginTop':'-2px'
                    },200);
                },
                function () {
                    $('a',$(this)).stop().animate({
                        'marginTop':'-80px'
                    },200);
                }
            );
            });

                
        </script>
        
<ul id="navigation">
            <!--<li class="home"><a href=""><span>Home</span></a></li>
            <li class="about"><a href=""><span>About</span></a></li>-->
            <li class="addForm"><a href="#" id="addForm"><span>Добавить форму</span></a></li>
            <li class="add"><a href="#" id="addDoc"><span>Добавить ДЭВ</span></a></li>
            <!--<li class="search"><a href="#" id="openFltr"><span>Фильтр</span></a></li>-->
            <li class="sign"><a href="#" id="signSelected"><span>Подписать выделенные</span></a></li>            
            <li class="od"><a href="<? echo $this->createUrl('shwd') ?>" id="od"><span>Перейти к ОД</span></a></li>
            <li class="users"><a href="<? echo $this->createUrl('users') ?>" id="od"><span>Пользователи</span></a></li>
            <li class="shutdown"><a href="<? echo $this->createUrl('logout') ?>" id="od"><span>Выйти</span></a></li>
            
            <!--
            <li class="podcasts"><a href=""><span>Podcasts</span></a></li>
            <li class="contact"><a href=""><span>Contact</span></a></li>-->
        </ul>

   
   <div id="progressbar"></div>

<?php
$flashMessages = Yii::app()->user->getFlashes();
if ($flashMessages) {
  echo '<ul id="splash1">';
    foreach($flashMessages as $key => $message) {
        echo "<li><div>" . $message . "</div></li>\n";
    }
    echo '</ul>';  
};
?>

<div id="info" style="position: absolute;width:250px;left:750px;top:70px"></div>

<style type="text/css">
    object.hiddenObject
    {
        visibility: hidden;
        width: 0px;
        height: 0px;
        margin: 0px;
        padding: 0px;
        border-style: none;
        border-width: 0px;
        max-width: 0px;
        max-height: 0px;
    }
</style>
<!-- ФОРМА ЗАГРУЗКИ ДЭВ -->
<div id="docupload" title="Загрузка файла">
    <form id="add_doc" class="P10 MultiFile-intercepted" action="<? echo $this->createUrl('addDoc') ?>" method="POST" enctype="multipart/form-data">
        <label for="upl_date">Дата ОД</label>
        <input type="text" id="upl_date" name="opdate"/>
    
        <label for="upl_inspector">Контролер</label>
        <input type="text" id="upl_inspector" name="inspector" class="inspector"/>
    
        <input type="file" class="multi" name="docs[]"/>
    </form>
        
</div>

<!-- ФОРМА ЗАГРУЗКИ ФОРМЫ ОТЧЕТНОСТИ -->
<div id="add_form_dialog" title="Загрузка отчетности">
    <form id="add_form" class="P10 MultiFile-intercepted" action="<? echo $this->createUrl('addForm') ?>" method="POST" enctype="multipart/form-data">
        <label for="add_form_opdate">ОД</label>
        <input type="text" id="add_form_opdate" name="add_form_opdate"/>
        
        <label for="add_form_type">Тип формы</label>
        <input type="text" id="add_form_type" name="add_form_type"/>
        
        <input type="file" class="multi" size="1" name="forms[]"/>
    </form>
</div>

<div id="answMenu" title="Загрузка ответа">
    <form id="answForm" id="answForm" action="<? echo $this->createUrl('addAnswForm') ?>" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="form_pid" id="form_pid"/>
        <input type="file" class="multi" size="1" name="forms_answ[]"/>           
    </form>    
</div>

<div id="MenuJqGrid" class="contextMenu">
      <ul>        
        <li id="addSignMnu"><img src="/js/folder.png" />Подписать</li>
        <li id="showDoc"><img src="/css/zoom_in.png" />Просмотреть</li>
        <li id="addAnswer"><img src="folder.png" />Ответ</li>
        <li id="delDoc"><img src="email.png" /> Удалить</li>
        <li id="unloadDoc"><img src="disk.png" />Выгрузить</li>
      </ul>
    </div>
<div id="fltfrm" title="Произвольный фильтр">
    <form action="#" class="uniform">
        <dl>
            <dt><label>Опер день:</label></dt>
            <dd><input type="text" id="opdate" name="opdate"/></dd>
            <dt><label>Класс документа:</label></dt>
            <dd>
            <select id="typeDoc" model="status" view-as="multiselect">
                <option value="doc">ДЭВ (опер.день)</option>
                <option value="docother">ДЭВ (другие)</option>
                <option value="docform">Формы отчетности</option>
                <option value="fin po bk rez">БК (рез)</option>
                <option value="fin po bk nerez">БК (не рез)</option>
            </select>
            </dd>
            <dt><label>Автор:</label></dt>
            <dd><input type="text" id="author" name="author" class="author" value="*"></dd>
            <dt><label>Контролер:</label></dt>
            <dd><input type="text" id="inspector" name="inspector" class="inspector"  value="*"/></dd>
            <dt><label>Рейс:</label></dt>
            <dd><input type="text" id="expn" name="expn"  value="*"/></dd>
            <dt><label>Статус документа:</label></dt>
            <dd>
            <select id="typeStatus" model="status" view-as="multiselect">
                <option value="1">Введен</option>
                <option value="2">Подписан(1)</option>
                <option value="3">Подписан(2)</option>
                <option value="4">Выгружен</option>
            </select>
            </dd>
        </dl>
    </form>
</div>

</div>

<object id="cadesplugin" type="application/x-cades" class="hiddenObject"></object>        
