/**
 * Created with JetBrains PhpStorm.
 * User: dmaslov
 * Date: 05.05.12
 * Time: 8:48
 * To change this template use File | Settings | File Templates.
 */
/***
 * Виджет создает информационную стену.
 */
(function( $ ) {
    $.widget( "o.wall", {
        options: {
            url      : null,
            delay    : 3000,
            timer    : null,
            doNow    : true,
            maxCount : 30
        },
    _create: function (opt) {
        var self=this;
        $.extend(self.options,opt);

        if (self.options.doNow) {
            self._doCall();
        };
        self.options.timer = window.setInterval(function () {
            self._doCall();
        },self.options.delay);
    },

    _doCall: function () {
        var self=this;
        jQuery.getJSON(self.options.url,{},function (data) {
            self._doRefresh(data);
        });
    },
    _doRefresh : function (events) {
            var self=this;
            var i=1;

            self.element.empty();
            var list=self.element.append('<ul></ul>').find('ul');
            $.each(events,function (index,event) {
               if (i<=self.options.maxCount) {
                   list.append($('<li><a href="mailto:'+event.email+'">'+event.email+'</a> '+event.dt+'-->'+event.iss_summary+'</li>'));
               };
                i++;
            });
    }
    });
}( jQuery ) );
