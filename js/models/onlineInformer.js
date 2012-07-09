/**
 * Created with JetBrains PhpStorm.
 * User: dmaslov
 * Date: 04.05.12
 * Time: 15:20
 * To change this template use File | Settings | File Templates.
 */

(function( $ ) {
    $.widget( "o.onlineInformer", {
        options: {
            delay :60000,
            url          :null,
            timer        :null,
            whoAmI       :null,
            doNow        :true

        },
      _create: function (opt) {
            var self=this;
            $.extend(self.options,opt);
           if (self.options.doNow) {
             self._doMark();
           };
            self.options.timer = window.setInterval(function () {
                self._doMark();
            },self.options.delay);
        },
        /**
         * Отметиться и обновить
         * список присутствующих.
         * @private
         */
        _doMark : function () {
            var self=this;
            //console.log('sfdsf');
            jQuery.getJSON(self.options.url,{whoAmI:self.options.whoAmI},function (data) {
                    self._doRefresh(data);
            });
        },
        /**
         * Разместить данные пользователей
         * в родительском элементе.
         * @param users
         * @private
         */
        _doRefresh : function (users) {
            var self=this;
            self.element.empty();
            var list=self.element.append('<ul></ul>').find('ul');
            $.each(users,function (index,user) {
                list.append($('<li>'+user.surname+' '+user.name+'</li>'));
            });
        }
});
}( jQuery ) );
