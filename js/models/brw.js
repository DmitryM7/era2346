(function ( $, undefined ) {
$.widget("o.brw",{
    options: {
             action:'getGrid',
             hidden     :true,
             toolbar    :null,
             find  : {
                        'gridId'  : null,
                        'pagerId' : null,
                        'frm'     : null,
                        'isInit'  : null
                    },
             mattr : {
                        'url':'#edit',
                        'loadUrl'    : null,
                        'saveUrl'    : null,
                        'frm'        : null
                       },
             xattr : {
                       'url':'#edit',
                       'loadUrl'    : null,
                       'saveUrl'    : null,
                       'frm'        : null
                    },
             toolbarId:null,
             gridParam  : {
                                        datatype:"json",
                                        rowNum:50,
                                        rowList:[10,20,30,50,100,500,1000],
                                        viewrecords: true,
                                        height:650,
                                        width:950,
                                        url:null,
                                        postData:{}
                          },
             menu       : {
                        'edit'   :{name:'Осн. реки',icon:'edit'},
                        'xattr'  :{name:'Доп. реки',icon:'xattr'}
                    },
             menuBuilder:  function(rowid,rowdata,rowelem,parent) {
                               return {};
                             },
             showButtons:['new','filter']
            },

/**
* Поиск по поисковому объекту
* @param params
*/
   find        : function (params) {
                     this.setGridParam({postData:params});
                     this.setEditParams(params);
          },

/** Установить доп. параметры окна редактирования **/
   setEditParams : function (params) {
   $(this.options.mattr.frm).frm('setParams',params);
   $(this.options.xattr.frm).frm('setParams',params);
 },
   getEdit: function () {
     return this.options.mattr.frm;
   },
   clear : function () {
     this.clearEdit();
     this.clearFind();
   },
   clearEdit     : function () {
       $(this.options.mattr.frm).frm('clear');
       $(this.options.xattr.frm).frm('clear');
   },
/** Поиск по окну фильтра **/
   sfltd   : function () {
            /**
             * Если на форме вывода есть элемент,
             * который передаем в качестве параметра,
             * то устанавливаем его значение.
             */
            var self=this;
            var params=self.options.gridParam.postData;
            $.each(self.options.find.frm.find('[model]'),function (index,element) {
                currElement=$(element);
                currElementModel=$(element).attr('model');

                if (params.hasOwnProperty(currElementModel)) {
                    currElement.val(params[currElementModel]);
                };
            });

            $(self.options.find.frm).dialog('open');
            },
   clearFind   : function() {
        var self=this;
           $.each(self.options.find.frm.find('[model]'),function (index,element) {
               $(element).val('');
           });
   },

/** Диалог основных реквизитов **/
   smad    : function (id) {
       $(this.options.mattr.frm).frm('setId',id);
       $(this.options.mattr.frm).frm('open');
   },

/** Диалог доп. реквизитов **/
   sxad    : function (id) {
       $(this.options.xattr.frm).frm('setId',id);
       $(this.options.xattr.frm).frm('open');
   },

/** Показать ГРИД **/
   sgrid   : function () {
     var options=this.options;
     var self=this;

     var menu=this.options.menu.items;

     var menuItems;
      /**
       * Создаю меню,
       * сделать это надо
       * здесь, чтобы получить
       * ссылку на сам jqGrid.
       */
      options.gridParam.afterInsertRow = function (rowid,rowdata,rowelem) {
          self._buildMenu(rowid,rowdata,rowelem);
      };
      $(options.find.gridId).jqGrid('setGridParam',options.gridParam);
      self._trigger('_beforeshowgrid',{},{parent:self});
         self._reloadGrid();
         self.element.show();
      self._trigger('_aftershowgrid',{},{parent:self});
   },

/** Скрыть ГРИД **/
   hgrid   : function () {
     this.element.hide();
   },
   destroy : function () {
                            alert('Убиваю виджет!');
                         },

/** Добавить к тулбару **/
appendToolbar:function (el) {
  el.appendTo('#t_'+this.options.find.gridId.substring(1));
},
setToolbar   :function (toolbar) {
  var self=this;
  toolbar.appendTo(self.options.toolbarId);
},
/** Установить параметры ГРИДА **/
setGridParam :function (conf) {
       $.extend(this.options.gridParam,conf);
       //$(this.options.find.gridId).jqGrid('setGridParam',conf);
      },
getGridParams: function () {
    return this.options.gridParam;
},
setGridAction: function (action) {
  var a=this.options.provider+'/'+action;
  this.setGridParam({url:a});
},
setCaption   : function (head) {
 $(this.options.find.gridId).jqGrid('setCaption',head);
},
/**
     * Выполняем произвольный JSON,
     * запрос к серверной части.
      * @param name
     * @param params
     * @param onEndAction
     */
doAction     : function (name,params,onEndAction) {
    var self=this;
    $.getJSON(this.createQuery(name),params,function (result) {
       onEndAction(result);
    });
},
createQuery      : function (name) {
    var self=this;
    return self.options.provider+'/'+name;
},
reload : function () {
    this._reloadGrid();
},
getGrid: function () {
  return $(this.options.find.gridId);
},

_reloadGrid  :function () {
         $(this.options.find.gridId).trigger('reloadGrid');
   },

_create      :function (conf) {
                             $.extend(this.options,conf);

                             if (this.options.provider==undefined) {
                               this.options.provider=this.element.attr('provider');
                             };

                             this.options.mattr.url      = this.options.provider;
                             this.options.xattr.url      = this.options.provider;
                             this.options.gridParam.url  = this.options.provider  + '/' + this.options.action;

                             this._createFindDialog();

                             this._createEditDialog();

                             this._createXAttrDialog();

                             this._createGrid();
                            },

/** Заполнить поисковый объект **/
_fillFindObject    : function () {
                var self = this;
                var options = this.options;
                var postData = {};
                // Собираем поисковый объект
                $.extend(postData,options.find.frm.adorn('get'));
                //Присваиваем параметры гриду
                 this.setGridParam({postData:postData});
            },

        /************************************************
         *
         *          МЕТОДЫ СОЗДАНИЯ ВИДЖЕТОВ
         *
         ************************************************/
_createFindDialog  : function () {
        var self = this;
        var options = self.options;
        options.find.frm=this.element.find('.brw-find');
        options.find.frm.dialog({
                                width:'450px',
                                autoOpen:false,
                                modal:true,
                            buttons:[
                                    {
                                        text:'Найти',
                                        click:function () {
                                             self._fillFindObject();
                                             self.sgrid();
                                             $(this).dialog('close');
                                       }
                                    },
                                    {
                                        text:'Отмена',
                                        click:function () {
                                             $(this).dialog('close');
                                        }
                                    }
                                    ]
                        });
        options.find.frm.adorn();
    },
_createEditDialog  : function () {
       var self=this;
       var options=self.options;
       options.mattr.frm=this.element.find('.brw-edit');

      $(options.mattr.frm).frm({
            url:options.provider
      });
      $(options.mattr.frm).bind('frm_aftersave',function (e,params) {
         self._trigger('_aftersavemattr',{},{ev:e,action:params});
         self._reloadGrid();
      });
    },
_createXAttrDialog : function () {
        var self=this;
        var options=self.options;
        options.xattr.frm=this.element.find('.brw-xattr');
        $(options.xattr.frm).frm({
            url:options.provider
        });
        $(options.xattr.frm).bind('frm_aftersave',function (e,params) {
        self._reloadGrid();
    });

    },
_createGrid        : function () {
        var self = this;
        var options=self.options;
        var gridOptions={};

        options.find.gridId='#'+this.element.attr('id')+'_grid';
        options.find.pagerId=options.find.gridId+'_pager';
        this.setGridParam({pager:options.find.pagerId});

    /**
     * Если грид при создании, должен
     * быть скрытм, то копируем параметры.
     */
        $.extend(gridOptions,options.gridParam);
        if (self.options.hidden) {
            gridOptions.datatype='local';
        };
         $('<table id="'+options.find.gridId.substring(1)+'"></table><div id="'+options.find.pagerId.substring(1)+'"></div>').appendTo(this.element);
         this.element.hide();
         $(options.find.gridId).jqGrid(gridOptions);

         self.options.toolbarId='#t_'+self.options.find.gridId.substring(1);

            //if (self.options.toolbar) {
                this._createToolbar(self.options.toolbar);
            //};

    },
_buildMenu         : function (rowid,rowdata,rowelem) {
     var self=this;
     var options=this.options;
     var jq=$(options.find.gridId);
     var menu={};
    // Проверяем наличие диалговых окн,
    // если каких-либо нет, то удаляем их
    // правокнопчного меню.
    if (self.options.xattr.frm.length<1) {
        delete self.options.menu.xattr;
    };
     $.extend(menu,self.options.menu);
     $.extend(menu,self.options.menuBuilder(rowid,rowdata,rowelem,self));    
     
     //
     //Сперва удаляем компонент, иначе при повторном
     //использовании contextMenu будет отображаться
     //старое
     //
     $.contextMenu('destroy','#'+rowid);
     
     //Теперь создаем меню по строке
     $.contextMenu({ 
                    selector:'#'+rowid,
                    items:menu,

                    callback: function (key,opt) {

                        id=jq.getGridParam('selrow');
                                                
                            switch (key) {
                                 case 'edit':
                                      self.smad(id);
                                      break;

                                 case 'newitem':
                                      self.smad();
                                      break;
                                                        
                                  case 'xattr':
                                      self.sxad(id);
                                      break;                             
                                  default:
                                      self._trigger('_menu',{},{name:key,id:id,parent:self,rowid:rowid,rowdata:rowdata,rowelem:rowelem});
                                      break;
                                      };
                        }
                 });
 },
_createToolbar     : function (toolbar) {

    var self=this;

    if ($.inArray('new',self.options.showButtons)!=-1) {
        var addButton=$('<button>+</button>').click(function () {
            self.smad();
        });
        self.appendToolbar(addButton);

    };

    if ($.inArray('filter',self.options.showButtons)!=-1) {
        var filterButton=$('<button>f</button>').click(function () {
            self.sfltd();
        });
        self.appendToolbar(filterButton);
    };
},
 /**
  * Возможно, что у строк будет ID 
  * с префиксом. Такое необходимо для
  * случая, когда на одной странице будет
  * несколько гридов.
  **/
 _extractId        : function (id) {
     if (id!=null) {
            var arr=id.split('-');

                if (arr.length>1) {
                          this.options.currId=arr[1];                            
                       } else {
                                this.options.currId=id;
                          };
           return true;                          
     } else {
         this.options.currId=null;
         return false;
     }

 }
 });
})( jQuery );
