/**
 * Created with JetBrains PhpStorm.
 * User: user
 * Date: 13.05.12
 * Time: 21:29
 * To change this template use File | Settings | File Templates.
 */
(function ($, undefined) {
    $.widget("o.chess",{
        options: {
            url:null,
            postData: {},
            height:100,
            width:5,
            lastEvent:null
        },
        _create: function(params) {
            var self=this;
            $.extend(self.options,params);
            var table=$('<table></table>').addClass('chess-table').appendTo(self.element);
            var cellCount=1;
            var currRow=1;
            var row=$('<tr></tr>').addClass('chess-row');
            var currCell={};

            $.getJSON(self.options.url,self.options.postData,function (result) {
                $.each(result,function (index,value) {
                    currCell = value;
                    if (cellCount%self.options.width==0 && result.length!=currRow) {
                        row.appendTo(table);
                        row=$('<tr></tr>').addClass('chess-row');
                        currRow++;
                    };
                    $('<td></td>').
                        attr('id','chess_'+currCell.id).
                        click(function (event) {
                            self._click(event);
                        }).
                        addClass('chess-cell').
                        append('<a href="#">'+value.name+'</a>').
                        appendTo(row);
                    cellCount++;
                })
                row.appendTo(table);
            });
        },
      _click: function (e,cell) {
          var self=this;
          this.setSelected(e);
          var id=this.getSelected(e);
          this._trigger('_click',{},{parent:self,id:id});
          e.preventDefault();
      },
      getSelected: function () {
          var target=this.options.lastEvent.currentTarget.id.split('_');
          var id=target[1];
          return id;
      },
      setSelected : function (e) {
          this.options.lastEvent=e;
      }
    });
})( jQuery );