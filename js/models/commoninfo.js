/**
 * Created with JetBrains PhpStorm.
 * User: dmaslov
 * Date: 18.05.12
 * Time: 12:54
 * To change this template use File | Settings | File Templates.
 */
(function ( $, undefined ) {
    $.widget("o.commoninfo",{
        options: {

        },
        _create: function (params) {
            var self=this;
            $.extend(self.options,params);
        },
        reload : function (params) {
            var self=this;
            self.element.empty();
            $.extend(self.options,params);
            var cont=$('<div></div>').addClass('commoninfo').appendTo(self.element);
            cont.append('<h2>'+self.options.whoAmI+' </h2>');
            cont.append('<h4>ПК "ЭРА" v3.0 </h4>');
            cont.append('<h4>Опер. день:'+self.options.opdate+'</h4>');
        }
    });
})(jQuery);