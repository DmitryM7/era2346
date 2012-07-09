/**
 * Created with JetBrains PhpStorm.
 * User: dmaslov
 * Date: 15.05.12
 * Time: 14:20
 * To change this template use File | Settings | File Templates.
 */

(function ($, undefined) {
$.widget("o.dinfo",{
    options: {
        url     :null,
        type    :'*',
        doOnCreate: false,
        action:'getChildren',
        postData:{},
        menu: {
            'del'   :{name:'Удалить',icon:'del'}
        }
    },
    _create: function (params) {
        $.extend(this,params);
        if (this.options.doOnCreate) {
            this._doAction();
        };
    },

     get: function (params) {
        $.extend(this.options.postData,params);
        this.element.addClass('dinfo');
        this._doAction();
    },
    _doAction: function () {
        var self=this;
        var url=self._makeUrl(self.options.action);

        $.ajaxSetup({ cache:false });

        self.element.empty();
        $.getJSON(url,self.options.postData,function (result) {
            $.each(result,function(index,doc){
                if (doc.taxon==self.options.type || self.options.type=="*")
                {
                    var currDocId=self.element.attr('id')+'_doc_'+doc.id;
                    var cont=$('<div></div>').attr('id',currDocId).addClass(doc.taxon).addClass(doc.taxon.replace(/ /g,'')).appendTo(self.element);

                    if (doc.isdelete!='0') {
                        cont.addClass('noteDelete');
                    };
                    self._createMenu(cont);
                    cont.append($('<h1>'+doc.author+'</h1>'));
                    cont.append($('<p>'+doc.dt+'</p>'));
                    cont.append($('<p>'+doc.title+'</p>'));
                    cont.append($('<p>-------</p>'));
                };
            });
        });
        $.ajaxSetup({ cache:true });
    },
    _createMenu : function (el) {
        var self=this;
        var currElementId=el.attr('id');

        el.contextMenu('destroy',el);
        $.contextMenu({
            selector:'#'+currElementId,
            items:self.options.menu,
            callback: function (key,opt) {

                id=opt.$trigger.attr("id").split('_')[2];

                switch (key) {
                    case 'del':
                        $.getJSON(self._makeUrl('del'),{id:id},function (result) {
                            $.noty(result);
                            self._doAction();
                        });
                        break;

                    default:
                        self._trigger('_menu',{},{name:key,id:id,parent:self});
                        break;
                };
            }
        });
    },
    reload : function () {
        this._doAction();
    },
    _makeUrl: function (action) {
        return this.options.url+'/'+action;
    }

})
})(jQuery);
