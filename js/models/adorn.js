/**
 * Created with JetBrains PhpStorm.
 * User: user
 * Date: 11.05.12
 * Time: 21:50
 * To change this template use File | Settings | File Templates.
 */
(function ($, undefined) {
$.widget("o.adorn",{
        options: {
                 },
   _create: function() {
       var self=this;
       var form=self.element.children('form');

       $.each(form.find('[view-as]'),function (index,element) {
           switch ($(element).attr('view-as')) {
               case "datepicker":
                   $(element).datepicker();
                   break;
               case "multiselect":
                   $(element).attr('multiple','multiple');
                   $(element).multiselect({
                       selectedList:30
                   }).multiselectfilter();

                   $(element).multiselect("widget").find(":checkbox:not(:checked)").each(function(){
                       this.click();
                   });
                   break;
           };
       });
     },
   get: function() {
       var self=this;
       var postData=[];
       $.each(self.element.find('[model]'),function (index, element) {
           switch ($(element).attr('view-as')) {
               case 'multiselect':
                   var checkedItems = $(element).multiselect("getChecked").map(function(){
                       return this.value;
                   }).get();
                   postData[$(element).attr('model')]=checkedItems.join();
                   checkedItems=[];
                   break;
               default:
                   postData[$(element).attr('model')] = $(element).val();
                   break;
           };
       });
    return postData;
   }
    });
})( jQuery );
