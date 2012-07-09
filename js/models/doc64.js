/**
 * Created with JetBrains PhpStorm.
 * User: dmaslov
 * Date: 16.05.12
 * Time: 13:13
 * To change this template use File | Settings | File Templates.
 */

(function ($, undefined) {
    $.widget("o.doc64",{
        options: {
            url :'http://localwww2/ui2',
            getAction:'getDetailsInBase64',
            writeAction:'writeSign',
            type:'post',
            postData: {},
            currPercent:0,
            percentByOne:0,
            author: null
        },
        _create : function (params) {
            $.extend(this.options,params);
        },
        sign: function (selectRows) {
            var self=this;
            var currCount=0;
            self.options.selectRows=selectRows;
            self.options.percentByOne = Math.ceil(100 / selectRows.length);
            self._trigger('_onbegin',{parent:self});
            self._getFile(self.options.selectRows.shift());
        },
        _getFile: function (id) {
            var self=this;
            var actionUrl=self.options.url+'/'+self.options.getAction;
            if (id>0) {
            $.ajax({
                    url:actionUrl,
                    success: function(result) {
                        self._trigger('_onload',{parent:self,details:result});
                        self._doSign(id,result);
                    },
                    data:{
                        id:id
                    },
                    type:self.options.type
                });
            } else {
                self._trigger('_onfinish',{parent:self});
            };
        },
        _doSign:function (currId,data) {
            var self=this;
            var actionUrl=self.options.url+'/'+self.options.writeAction;
            var sign;
            sign=SignCreate(self.options.author,data);

            $.ajax({
                url:actionUrl,
                success: function (result) {
                    self.options.currPercent=self.options.currPercent + self.options.percentByOne;
                    var currPercent=self.options.currPercent;
                    self._trigger('_onsend',{},{parent:self,percent:currPercent});
                    self._getFile(self.options.selectRows.shift());
                },
                data:{
                    'id':currId,
                    'details':sign
                },
                type:'post'
            });
        }
    })
})(jQuery);


