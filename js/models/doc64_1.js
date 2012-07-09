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
            sendAction:'writeSign',
            type:'post',
            postData: {}
        },
    _create : function (params) {
        $.extend(this.options,params);
    },
    getElements: function (selectRows) {
        var self=this;
        var res1=[];
        var retArray=[];
        var actionUrl=self.options.url+'/'+self.options.getAction;

        var currCount=0;
        $.each(selectRows,function (index,currId) {
            // По всем выбранным столбцам
            $.extend(self.options.postData,{id:currId});

            $.ajax({
                url:actionUrl,
                success: function(result) {
                        res1.push({id:currId,details:result});
                        currCount=currCount+1;
                        self._trigger('_onload',{parent:self,count:currElement});
                        self._onLoadFile(currElement);
                        if (currCount==selectRows.length) {
                            self._trigger('_onloadcomplite',{},{parent:self,res:res1});
                            self._onLoadAllFiles(res1);
                        };
                },
                data:self.options.postData,
                type:self.options.type
            });
        });
    },
    _onLoadFile : function (currElement) {

    },
    _onLoadAllFiles: function (f) {
        var self=this;
        var signArray=[];

        $.each(f,function (index,value) {
            sign = /*SignCreate('<?php echo $user->email; ?>',value.details);*/'sdfsdfdsf';
            signArray.push({id:value.id,details:sign});
        });
        self._doSend(signArray);
    },
    _doSend     : function (signArray) {
        var self=this;
        var currElement=0;
        var currCount=0;
        var actionUrl=self.options.url+'/'+self.options.sendAction;
        var status={type:'success',text:'Подписи отправлены! Проверьте их наличие!'};

        $.each(signArray,function (index,docSign) {
            var currId=docSign.id;
            var currDetails=docSign.details;

            $.ajax({
                url:actionUrl,
                success: function (result) {
                    currCount++;
                    currElement++;
                    self._onSendFile(currElement);
                    self._trigger('_onsend',{},{parent:self,current:currElement});

                    if (currCount==signArray.length) {
                        self._onSendAllFiles(currCount,status);
                        self._trigger('_onsendcomplite',{},{parent:self,current:currCount,status:status});
                    }
                },
                data:{
                    'id':currId,
                    'details':currDetails
                },
                type:'post'
            });
        });

    },
    _onSendFile: function (currElement) {

    },
    _onSendAllFiles: function (currCount,status) {
            alert('Все отправил!');
    }
    })
})(jQuery);


