                    </div>
                </td>
            </tr>
        </table>
    </div>
    <iframe name="siframe" id="siframe" style="display:none;width:0px;"></iframe>
</body>
</html>
<script type="text/javascript">
function initMenu() {
  var nowmenu = "<?echo $menu_uplevel ?>";
  //全部關起來
  //$('#menu ul').hide();
  //加入處理事件
  $('#menu li a').click(function() {
    var checkElement = $(this).next();
    if((checkElement.is('ul'))) {
        if(!checkElement.is(':visible')){
        //把打開的關起來
        $('div.i-folder-open').removeClass("i-folder-open");
        $('#menu ul:visible').slideUp('normal');
        //把目前的打開
        $(this).find(".i-folder").addClass("i-folder-open");
        checkElement.slideDown('normal');
      }
      return false;
    }
  });
}
$(document).ready(function() {initMenu();});
$(document).ready(function(){
    $(".dateset").each(function(){
        datasel($(this).attr('name'));
    })
});
</script>