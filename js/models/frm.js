/**
 * Created with JetBrains PhpStorm.
 * User: user
 * Date: 27.04.12
 * Time: 17:27
 * To change this template use File | Settings | File Templates.
 */

(function ($, undefined) {
  $.widget("o.frm",{
 options: {
            id           : null,
            url          : null,
            frmObject    : null,
            params       : {},
            action       : 'save',
            title        : null
 },
setParam: function(paramName,paramValue) {
  this.options.params[paramName]=paramValue;
},
/** Открыть **/
open : function () {
          this._clear();
          this._fillRemote();
          this._fillLocal();
          this.options.frmObject.dialog('open');
},
/** Установить искомый ID **/
setId   : function (id)  {
   this.options.id=id;
 },
/** Установить доп. параметры **/
setAction: function (action) {
 this.options.action=action;
},
setParams : function(params) {
    var self=this;
    $.extend(this.options.params,params);
    return self;
 },
setTitle : function (title) {
    var self=this;
    self.options.title=title;
    self.options.frmObject.dialog('option','title',self.options.title);
},
/** Получить параметры **/
getParams : function () {
          var self=this;
          return this.options.params;
      },
getParam  : function (param) {
  return this.options.params[param];
},
/** Полная очистка форма + пар-ры **/
clear: function () {
  this._clear();
  this.options.params={};
},
/** Отобразить **/
_create : function (conf) {
    $.extend(this.options,conf);
    this.options.frmObject = this.element;
    this._createDialog();
},
/**
       * Создать виджет
       * @private
       */
_createDialog : function () {
          var self=this;

          self._adorn();
          self.options.frmObject.dialog({
              width:'450px',
              autoOpen:false,
              modal:true,
              buttons:[
                  {
                      text:'Сохранить',
                      click:function () {
                          self._saveEdit();
                          $(this).dialog('close');
                      }
                  },
                  {
                      text:'Применить',
                      click:function () {
                          self._saveEdit();
                      }
                  },
                  {
                      text:'Отмена',
                      click:function () {
                          $(this).dialog('close');
                      }
                  }
              ],
              beforeClose : function (event,ui) {
                  self._trigger('_beforeclose',{},{parent:self,event:event,ui:ui});
              }
          });
        if (self.options.title!=null) {
            self.setTitle(self.options.title);
        };
      },

/**
     * Сохранить
     * @private
     */
 _saveEdit     : function () {
   var params={};
   var self=this;
   /** Учитываем дополнительные параметры **/
   $.extend(params,self.options.params);

     params['id']=self.options.id;

       //Собираем данные
        $.extend(params,self.element.adorn('get'));
        self._trigger('_beforesave',{},{parent:self});
        $.ajaxSetup({ cache:false });
        //Отправляем на сервер
        this._doAction(self.options.action,params,function (result) {
            self._trigger('_aftersave',{},{parent:self,result:result});
        });
        $.ajaxSetup({ cache:true });
    },

/** Очистить форму**/
 _clear: function () {
        $.each(this.options.frmObject.find('[model]'),function (index,element) {
            $(element).val('');
        });
 },
/** Загрузить с сервиса **/
_fillRemote : function () {
     var self= this;
     var m   = {};
     var currElement, currElementModel;

    if (self.options.id!=undefined) {

       this._doAction('load',{id:self.options.id},function (result) {
           $.each(result,function (name,value) {
               m[name]=value;
           });
           $.each(self.options.frmObject.find('[model]'),function (index,element) {
               currElement=$(element);
               currElementModel=$(element).attr('model');
               // Локальные данные имеют более
               // высокий приоритет. Поэтому,
               //если есть локальный параметр,
               // то пропускаем заливку с сервера.
               if (!self.options.params.hasOwnProperty(currElementModel)) {
                   currElement.val(m[currElementModel]);
               };
           });
       });
   };
 },
/** Загрузить с локальных параметров **/
_fillLocal  : function () {
    /**
     * Если на форме вывода есть элемент,
     * который передаем в качестве параметра,
     * то устанавливаем его значение.
     */
    var self=this;
    var params=self.options.params;
    $.each(self.options.frmObject.find('[model]'),function (index,element) {
        currElement=$(element);
        currElementModel=$(element).attr('model');
        if (params.hasOwnProperty(currElementModel)) {
            currElement.val(params[currElementModel]);
        };
    });
},
      /**
       * Закрашиваем элементы,
       * навешивая на них jQuery
       * виджеты.
       * @private
       */
_adorn   : function () {
          var self=this;
          self.element.adorn();
},

_doAction     : function (name,params,onEndAction) {
          var self=this;
          $.getJSON(self.options.url+'/'+name,params,function (result) {
              onEndAction(result);
          });
}
});
})( jQuery );
