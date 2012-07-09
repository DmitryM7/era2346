/**
 * Created with JetBrains PhpStorm.
 * User: dmaslov
 * Date: 16.05.12
 * Time: 9:23
 * To change this template use File | Settings | File Templates.
 */

(function ($, undefined) {
    $.widget("o.jDialog",{
        options:{
            currElement:null
        },
        _create : function () {
        },
        prompt: function (onPermit) {
            var self=this;
            var options=this.options;
            var currElement=$('<div></div>').appendTo(this.element);
            currElement.dialog({
                modal:true,
                dialogClass: 'no-close',
                buttons:[
                    {
                        text:"Подтвердить",
                        click:function () {
                            $(this).dialog('close');
                            $(this).dialog('destroy');
                            currElement.remove();
                            onPermit();
                        }
                    },
                    {
                        text:"Отменить",
                        click: function () {
                            $(this).dialog('close');
                            $(this).dialog('destroy');
                            currElement.remove();
                        }
                    }
                ]
            })
        }
    })
})(jQuery);
