<?php
Yii::app()->getClientScript()->registerCoreScript('jQuery');
Yii::app()->getClientScript()->registerPackage('ui');
Yii::app()->getClientScript()->registerPackage('jqGrid');
Yii::app()->getClientScript()->registerPackage('main.page');
Yii::app()->getClientScript()->registerPackage('superfish');
Yii::app()->getClientScript()->registerPackage('onlineInformer');
Yii::app()->getClientScript()->registerPackage('wall');
Yii::app()->getClientScript()->registerPackage('frm');
Yii::app()->getClientScript()->registerPackage('brw');
Yii::app()->getClientScript()->registerPackage('dinfo');
Yii::app()->getClientScript()->registerPackage('jDialog');
Yii::app()->getClientScript()->registerPackage('doc64');
Yii::app()->getClientScript()->registerPackage('noty');
Yii::app()->getClientScript()->registerPackage('commoninfo');
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->assetManager->baseUrl.'/CryptoPro/cryptopro.js');

?>
<script>
$().ready(function () {
  var currActiveDocs=null;
  $('#rwdg').accordion({autoHeight: false});
  $('#whoPresent').onlineInformer({
        delay :'10000',
        url   :'<?php echo $this->createUrl('wdg/markUser'); ?>',
        whoAmI:'<?php echo $user->email; ?>'
    });
  $('#wall').wall({
        'delay':5000,
        'url'  :'http://localwww2/ui/showWall'
    });

    $('#wdgCommonInfo').commoninfo({
        opdate:null,
        whoAmI:'<?php echo $user->surname." ".$user->name;?>'
    });
    $('#wdgNote').dinfo({
        url:'http://localwww2/docs',
        action:'getChildren'
    });

    $(document.body).doc64({
        author:'<?php echo $user->email; ?>'
    });
    $(document.body).bind('doc64_onsend',function (e,action) {
        $("#progressbar").progressbar({
            value:action.percent
        });
    });
    $(document.body).bind('doc64_onbegin',function (e,parent) {
        $('#signDialog').dialog('open');
    });

    $(document.body).bind('doc64_onfinish',function (e,parent) {
        $('#signDialog').dialog('close');
    });

    $('#wdgSign').dinfo({
        url   :'http://localwww2/docs',
        action:'getChildrenSign'
    });

    $(".author").autocomplete({source:'http://localwww2/ui/ajaxGetUser'});
    $(".inspector").autocomplete({source:'http://localwww2/ui/ajaxGetUser'});


    $('.sf-menu').find('a').click(function(e) {
        var action=$(this).attr('href');

        switch (action) {
            case "#aa1":
                // Произвольный фильтр
                $('#docs').brw("sfltd");
                e.preventDefault();
                break;
            case "#bb":
                $('#people').brw('sgrid');
                break;
            case "#logout":
                window.location.href='<?php echo $this->createUrl('logout');?>';
                break;
        };
    });

    $('#signDialog').dialog({
        autoOpen:false,
        dialogClass: 'no-close',
        hide: "explode",
        resizable:false,
        title:'Подписываю документы'
    });

    $('#docs').brw({
        action:'ajaxGetTable',
        gridParam: {
            caption: 'Документы',
            colNames:['Ун.ном','Рейс','Автор','Контроллер','Заголовок','Cтатус','Дата','Приоритет'],
            colModel:[
                {name:'id',index:'id',width:10},
                {name:'expn',index:'expn',width:15},
                {name:'author',index:'author',width:15},
                {name:'inspector',index:'inspector',width:15},
                {name:'title',index:'inspector',width:15},
                {name:'status',index:'status',width:15},
                {name:'dt',index:'dt',width:15},
                {name:'priority',index:'priority',width:25}
            ],
            sortname:'did',
            toolbar:[true,'top'],
            width:700,
            height:'700px',
            subGrid : true,
            multiselect: true,
            subGridRowExpanded: function(subgrid_id, row_id) {
                var subgrid_table_id;
                subgrid_table_id = subgrid_id+'_t';

                var info=$('<div id="'+subgrid_table_id+'"></div>').appendTo('#'+subgrid_id);
                    info.dinfo({
                        url:'http://localwww2/docs/getChildren',
                        postData:{
                                    pid:row_id
                                 }
                    });
            },
            onSelectRow:function (id) {
                $('#rwdg').accordion('activate',4);
                $('#wdgNote').dinfo('get',{pid:id});
                $('#wdgSign').dinfo('get',{pid:id});
            }

        },
        menu:{
            'sign'  :{name:'Подписать'},
            'del'   :{name:'Удалить'},
            'visa'  :{
                      name: 'Визы',
                      items:{
                          'permit':{
                              name:'Разрешить'
                          },
                          'deny':{
                              name:'Отказать'
                          }
                      }
            },
            'take'  :{
                name:'Захватить',
                items:{
                    'takeAuthor'   : {
                        name:'Авторство'
                    },
                    'takeInspector': {
                        "name":'Контроль'
                    }
                }
            },
            'stick':{
              name:'Прилепить',
                    items: {
                        'sticknotice':{name:'Информацию'},
                        'stickerror':{name:'Ошибку'}
                    }

            },
            'show'  :{name:'Показать'}

        },
        menuBuilder:function (rowid,rowdata,rowelem,parent) {
            var menu={};
            return menu;
        }
    });

    $('#people').brw({
        gridParam: {
            caption:'Коллеги',
            colNames:['Ун.ном','Фото','Фамилия','Имя','Отчетство','Должность','Подразделение','Тел','E-mail'],
            colModel:[
                {name:'id',index:'id',width:10},
                {name:'photo',index:'photo',width:15},
                {name:'surname',index:'surname',width:15},
                {name:'name',index:'name',width:15},
                {name:'patronymic',index:'patronymic',width:15},
                {name:'status',index:'status',width:15},
                {name:'decision',index:'decision',width:15},
                {name:'phone',index:'phone',width:25},
                {name:'email',index:'email',width:25}
            ],
            sortname:'did',
            toolbar:[true,'top'],
            width:700,
            height:'700px',
            subGrid : true,
            subGridRowExpanded: function(subgrid_id, row_id) {
                var subgrid_table_id;
                subgrid_table_id = subgrid_id+'_t';

                $('#'+subgrid_id).html('<div id="'+subgrid_table_id+'"></div>');
                $('#'+subgrid_table_id).load('http://localwww2/docs/getNotes','pid='+row_id);
            },
            onSelectRow:function (id) {
                $('#wdgNote').load('http://localwww2/docs/getNotes','pid='+id);
            }

        },
        menu:{
            'sign': {name:'Подписать'},
            'del' : {name:'Удалить'},
            'note' :{name:'Комментарий'}
        },
        menuBuilder:function (rowid,rowdata,rowelem,parent) {
            var menu={};
            return menu;
        }
    });

    $('#opdate').brw({});

    $('#frmNote').frm({
                        url    :'http://localwww2/docs',
                        action : 'AddPermitNote'
    });

    $('#frmStick').frm({
        url    :'http://localwww2/docs',
        action : 'stickNotice'
    });
    $('#frmStick').bind('frm_aftersave',function (e,params) {
        $('#wdgNote').dinfo('reload');
    });

  $('.brw').bind('brw_beforeshowgrid',function (e,params) {
      var postData=params.parent.getGrid().getGridParam('postData');
      var currDate=postData.opdate;
      $('#wdgCommonInfo').commoninfo('reload',{opdate:currDate});
      currActiveGrid.brw('hgrid');
      currActiveGrid=params.parent.element;
  });

  currActiveGrid=$('#docs');
  $('#docs').brw('find',{});
  $('#docs').brw('sgrid');






$('#docs').bind('brw_menu',function (e,action) {
    if (action.id!=null) {

    switch (action.name) {
        case "sign":
            var selectDocs = $('#docs').brw('getGrid').getGridParam('selarrrow');
            $(document.body).doc64('sign',selectDocs);
            break;
        case "deny":
            $('#frmNote').frm('setAction','addDenyNote');
            $('#frmNote').frm('setParams',{
               pid:action.id
            });
           $('#frmNote').frm('open');
            break;
        case "permit":
            $('#frmNote').frm('setAction','addPermitNote');
            $('#frmNote').frm('setParams',{
                pid:action.id
            });
            $('#frmNote').frm('open');
            break;
        case "sticknotice":
            $('#frmStick').frm('setAction',action.name).frm('setTitle','Приклеить оповещение...');
            $('#frmStick').frm('setParams',{
                pid:action.id
            });
            $('#frmStick').frm('open');
            break;
        case "stickalert":
            $('#frmStick').frm('setAction',action.name).frm('setTitle','Приклеить предупреждение...');
            $('#frmStick').frm('setParams',{
                pid:action.id
            });
            $('#frmStick').frm('open');
            break;
        case "stickerror":
            $('#frmStick').frm('setAction',action.name).frm('setTitle','Приклеить ошибку...');
            $('#frmStick').frm('setParams',{
                pid:action.id
            });
            $('#frmStick').frm('open');
            break;
        case "show":
            window.open('http://localwww2/docs/show?id='+action.id, '', '');
            break;
        case "takeInspector":
            $.getJSON('http://localwww2/docs/takeInspector',{id:action.id},function (result) {
                $.noty(result);
                $('#docs').brw('reload');
            });
            break;
        case "takeAuthor":
            $.getJSON('http://localwww2/docs/takeAuthor',{id:action.id},function (result) {
                $.noty(result);
                $('#docs').brw('reload');
            });
            break;
    };
    } else {
        alert('Не выбран документ!');
    }
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
            <a href="#dd">Подписать</a>
        </li>
        <li>
            <a href="#bb" class="sf-with-ul">Коллеги</a>
            <ul>
                <li><a href="#bb1">Список</a></li>
                <li><a href="#bb2">Управление</a></li>
            </ul>
        </li>
        <li>
            <a href="#cc">Дни</a>
        </li>
        <li>
            <a href="#c" class="sf-with-ul">Профиль</a>
            <ul>
                <li><a href="#c1" class="sf-with-ul">Пароль</a></li>
                <li><a href="#c2" class="sf-with-ul">Интерфейс &gt;</a>
                    <ul>
                        <li><a href="#c2" class="sf-with-ul">Классический</a></li>
                        <li><a href="#c2" class="sf-with-ul">Карта1</a></li>
                    </ul>
                </li>
            </ul>
        </li>

    </ul>
</div>

<div id="main_window" title="Электронный архив" class="span-24 zi1">

    <div id="docs" class="brw span-18 last zi1" provider='http://localwww2/ui'>
        <div class="brw-find" title="Произвольный фильтр" >
            <form>
                <dl>
                    <dt><label>Опер день:</label></dt>
                    <dd><input type="text" model="opdate" view-as="datepicker"/></dd>
                    <dt><label>Класс документа:</label></dt>
                    <dd>
                        <select model="classcode" view-as="multiselect">
                            <option value="doc">ДЭВ (опер.день)</option>
                            <option value="docother">ДЭВ (другие)</option>
                            <option value="docform">Формы отчетности</option>
                        </select>
                    </dd>
                    <dt><label>Автор:</label></dt>
                    <dd><input type="text" model="author" class="author" value="*"></dd>
                    <dt><label>Контролер:</label></dt>
                    <dd><input type="text" model="inspector" class="inspector"  value="*"/></dd>
                    <dt><label>Рейс:</label></dt>
                    <dd><input type="text" name="expn" model="expn" value="*"/></dd>
                    <dt><label>Статус документа:</label></dt>
                    <dd>
                        <select model="status" view-as="multiselect">
                            <option value="1">Введен</option>
                            <option value="2">Подписан(1)</option>
                            <option value="3">Подписан(2)</option>
                            <option value="4">Выгружен</option>
                        </select>
                    </dd>
                </dl>
            </form>
    </div>
        <div class="brw-edit">
            <form>
                <dl>
                    <dt><label>Опер день:</label></dt>
                    <dd><input type="text" model="opdate" view-as="datepicker"/></dd>
                    <dt><label>Класс документа:</label></dt>
                    <dd>
                        <select model="classcode" view-as="multiselect">
                            <option value="doc">ДЭВ (опер.день)</option>
                            <option value="docother">ДЭВ (другие)</option>
                            <option value="docform">Формы отчетности</option>
                            <option value="fin po rez">БК (рез)</option>
                            <option value="fin po nerez">БК (не рез)</option>
                        </select>
                    </dd>
                    <dt><label>Автор:</label></dt>
                    <dd><input type="text" model="author" class="author" value="*"></dd>
                    <dt><label>Контролер:</label></dt>
                    <dd><input type="text" model="inspector" class="inspector"  value="*"/></dd>
                    <dt><label>Заголовок:</label></dt>
                    <dd><input type="text" model="inspector" class="inspector"  value="*"/></dd>
                </dl>
            </form>
        </div>
    </div>

    <div id="people" title="Коллеги" class="brw span-18 last zi1" provider='http://localwww2/people'>
        <div class="brw-find" title="Произвольный фильтр" >
            <form>
                <dl>
                    <dt><label>Фамилия:</label></dt>
                    <dd><input type="text" model="surname"></dd>

                    <dt><label>Имя:</label></dt>
                    <dd><input type="text" model="name"></dd>

                    <dt><label>Отчество:</label></dt>
                    <dd><input type="text" model="patronymic"></dd>

                    <dt><label>Должность:</label></dt>
                    <dd><input type="text" model="status"></dd>

                    <dt><label>Отдел:</label></dt>
                    <dd><input type="text" model="decision"></dd>

                    <dt><label>Телефон:</label></dt>
                    <dd><input type="text" model="phone" view-as="phone"></dd>

                    <dt><label>Почт@</label></dt>
                    <dd><input type="text" model="email" view-as="email"></dd>

                </dl>
            </form>
        </div>
        <div class="brw-edit">
            <form>
                <dl>
                    <dt><label>Опер день:</label></dt>
                    <dd><input type="text" model="opdate" view-as="datepicker"/></dd>
                    <dt><label>Класс документа:</label></dt>
                    <dd>
                        <select model="classcode" view-as="multiselect">
                            <option value="doc">ДЭВ (опер.день)</option>
                            <option value="docother">ДЭВ (другие)</option>
                            <option value="docform">Формы отчетности</option>
                        </select>
                    </dd>
                    <dt><label>Автор:</label></dt>
                    <dd><input type="text" model="author" class="author" value="*"></dd>
                    <dt><label>Контролер:</label></dt>
                    <dd><input type="text" model="inspector" class="inspector"  value="*"/></dd>
                    <dt><label>Заголовок:</label></dt>
                    <dd><input type="text" model="inspector" class="inspector"  value="*"/></dd>
                </dl>
            </form>
        </div>
    </div>

    <div id="opdate" title="Дни" class="brw span-18 last zi1" provider='http://localwww2/opdate'>
        <form>
            <dl>
                <dt><label>Закрыт:</label></dt>
                <dd><input type="text" model="isClose"></dd>

                <dt><label>Блокирован СВК:</label></dt>
                <dd><input type="text" model="isBlock"></dd>
            </dl>
        </form>
    </div>

    <div id="rwdg" class="span-6 last">
        <h2><a href="#">Данные дня</a></h2>
        <div id="wdgCommonInfo">

        </div>
        <h2><a href="#">Коллеги в сети</a></h2>
        <div id="whoPresent"></div>
        <h2><a href="#">Погода</a></h2>
        <div>
            <a href="http://clck.yandex.ru/redir/dtype=stred/pid=7/cid=1228/*http://pogoda.yandex.ru/moscow"><img src="http://info.weather.yandex.net/moscow/1.ru.png" border="0" alt=""/><img width="1" height="1" src="http://clck.yandex.ru/click/dtype=stred/pid=7/cid=1227/*http://img.yandex.ru/i/pix.gif" alt="" border="0"/></a>
        </div>
        <h2><a href="#">Подписи</a></h2>
        <div id="wdgSign">
        </div>
        <h2><a href="#">Примечания к документу</a></h2>
        <div id="wdgNote">
        </div>
</div>

    <div id='signDialog' title="Пописываю документы ...">
    <div id="progressbar"></div>
</div>

<div id="frmNote" class="frmNote">
    <dt><label>Заголовок:</label></dt>
    <dd><input type="text" model="title" value="*"/></dd>
    <dt><label>Содержимое:</label></dt>
    <dd><input type="text" model="details"/></dd>
</div>

<div id="frmStick" class="frmNote">
    <dt><label>Заголовок:</label></dt>
    <dd><input type="text" model="title" value="*"/></dd>
    <dt><label>Содержимое:</label></dt>
    <dd><input type="text" model="details"/></dd>
</div>
<object id="cadesplugin" type="application/x-cades" class="hiddenObject"></object>