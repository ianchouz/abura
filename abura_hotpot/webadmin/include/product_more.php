<? include 'applib.php';?>
<? include 'include/shoppingcar/shopping_lib.php';?>
<?
$pageid="product";

  $id = pgParam("id","");

  $sql = "SELECT * FROM ".$CFG->tbext."product WHERE inuse = '1' ";
   $sql .= " and id=".$id;
  $sql .= " order by seq desc LIMIT 0 , 1";
  $result = @sql_query($sql);
  $activeItem = @sql_fetch_array($result);
  if ($activeItem){
    $html_title = $activeItem["html_title"];
    if ($html_title==""){
      $html_title = $activeItem["title"];
    }
    $html_keywords = $activeItem["html_keywords"];
    $html_description = $activeItem["html_description"];
    if ($html_description==""){
     $html_description = $activeItem["summary"];
    }
  }

$headLIB='
<link href="css/default.css" rel="stylesheet" type="text/css" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/link.css" rel="stylesheet" type="text/css" />
<!--[if lt IE 9]>
	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
<![endif]-->
<script type="text/javascript">
        $(document).ready(function(){
            $("#wrapper").fadeIn(1000);
        }); 
</script>
<!--下拉式-->
<script type="text/javascript">
$(function(){
    $("#open").click(function() {
		$("#open_over").slideToggle()
    });
	
	//點選
	$(".product_more_hover_item").each(function(){
		$(this).click (function(){
			$("#open").html($(this).html())
			$("#open_over").slideToggle()
		})
	})
});
</script>
<script type="text/javascript" src="product.js"></script>  
<script type="text/javascript" src="js/fancybox/jquery.fancybox.pack.js?v=2.1.5"></script>
<link rel="stylesheet" type="text/css" href="js/fancybox/jquery.fancybox.css?v=2.1.5" media="screen" />
';

include 'header.php';
?>

<!--   slider    -->
<link rel="stylesheet" type="text/css" href="css/anythingslider.css" />

<script type="text/javascript" src="js/jquery.anythingslider.js"></script>
<script type="text/javascript">
</script>
<!--   slider    -->

<div id="wrapper" style="display:none;">



<? include 'left_menu.php';?>


<!--CENTER-->
<div id="center" style="width:100%;">
<!----------內容----------->
<!--文字的地方-->
<div class="product_more_left">
	<div class="product_more_tit"><span><?=$activeItem["title"]?></span></div>
    <div class="product_more_ml"><? if($activeItem["prono"]!=""){?>(<?=$activeItem["prono"]?>)<? }?></div>
    <div class="product_more_con">
    	
        
    	<?=$activeItem["content"]?>
        <?
			 //運費
					  $strSQLQ = "select * from ".$CFG->tbext."webconfig where id='shopping_memo_set'";
					  $query = @sql_query($strSQLQ);
					  $buysetrow = @sql_fetch_array($query);
					  if ($buysetrow["xmlcontent"]!=""){
					    $buyset_xmlvo = new parseXML($buysetrow["xmlcontent"]);
						$baseFreight = (int)$buyset_xmlvo->value('/content/baseFreight');
						$freeFreight = (int)$buyset_xmlvo->value('/content/freeFreight');
					  }
					if ($baseFreight>0){
		?>
        <div class="product_more_fare" style="padding-top:20px;">※ <? if($freeFreight>0){?>總金額未滿＄<?=$freeFreight?>將加收<? }?>運費＄<?=$baseFreight?>元</div>
        <? }?>
    </div>
</div>

<!--換圖的地方-->
<div class="product_more_right">
        

	<!--大圖-->
    
    <div id="slider">
        <?
        $xmlvo = new parseXML($activeItem["imagexml"]);
        for($i=1;$i<=3;$i++){
            $big1 = $xmlvo->value('/content/big'.$i);
            $lcoverstr = '';
            if ($big1 !=""){
                $lcoverstr = getImage($CFG->product_b_w,$CFG->product_b_h,$big1,$CFG->product["path"],true,true,' class="bigimg" ',false,false);
        ?> 
        <div  class="product_more_pic" style="text-align:center;"><?=$lcoverstr?></div>
        <?
            }
        }
        ?>
    </div>
	
    			
    <?
                 $f_fixprice = $activeItem["fixPrice"];
                 $f_sellprice = $activeItem["sellingPrice"];
                 $sid = pgParam("sid","");
                 //撈取規格
                 $standsql = "select * from ".$CFG->tbext."product_stand where proID = ".$activeItem["id"]." and inuse=true order by seq asc";
                 $standrs = @sql_query($standsql);
                 $standCount = @sql_num_rows($standrs);
                 if ($standCount > 0){
					 $standrs2 = @sql_query($standsql);
					 $tmpstand2 = @sql_fetch_array($standrs2);
                 ?>
    <div style="position:absolute;margin-top: -40px; margin-left: 120px; font-weight: bold;">定價&nbsp;:&nbsp; NT$ <span id="fixprice_show"><?=number_format($tmpstand2['fixPrice'])?></span></div>
    <div style="position:absolute;margin-top: -20px; margin-left: 120px; color:#A06B42; font-weight: bold;">售價&nbsp;:&nbsp; NT$ <span id="payprice_show"><?=number_format($tmpstand2['sellingPrice'])?></span></div>
   
    <div class="product_more_btn">
    	<input type="hidden" id="product_id" value="<?=$activeItem["id"]?>">
				 
        <div class="product_more_input"><input name="amount" id="amount" type="text" value="1" /><span>盒</span></div>
        <div class="product_more_cost" id="open"><div class="product_more_cost_word"><?=$tmpstand2['standName']?></div></div>
			<!--下拉-->
			<div style="margin-top:10px; position:absolute;">
			<div class="product_more_hover" id="open_over" style="bottom:0;">
			<?
					  $standcc = 0;
                      while($tmpstand = @sql_fetch_array($standrs)){
                        $pro = new sellProduct($activeItem["id"],$tmpstand['id']);
                        if (!$pro->loadDatas()){
                          continue;
                        }
                        $standName = $tmpstand['standName'];
                        $fixprice = $pro->fixPrice;
                        $sellprice = $pro->sellPrice;
                        $payprice = $pro->getPrice();
                        $discount_msg = $pro->discount_msg;
                        $sel = '';
                        $valstr = $tmpstand['id'];
                        if ($standcc==0 || $sid == $tmpstand['id']){
                          $f_fixprice = $fixprice;
                          $f_sellprice = $payprice;
                        }
						
                      ?>
            <div class="product_more_hover_item" onClick="chang_stand('<?=$activeItem['id']?>','<?=$tmpstand['id']?>');"><?=$standName?></div>
            <? }?>

			</div>
			</div>
            <input type="hidden" id="stand_id" name="stand_id" value="<?=$tmpstand2['id']?>" />
        <div class="product_more_link">
			<? if ($standCount > 0){?><div class="_menu_hover"><span id="btn_shopping" style="cursor:pointer;"><img src="image/btn10.png"/><img src="image/btn10_hover.png"  style="display:none;opacity:0;"/></span></div><? }?>
            
           <!-- 食譜<div class="_menu_hover" onClick="boxproduct('<?=$activeItem['id']?>');" style="cursor:pointer;"><img src="image/btn12.png" /><img src="image/btn12_hover.png" style="display:none;opacity:0;"/></div> -->
            <div class="_menu_hover"><a href="product_list.php?pid=<?=$activeItem['cateId']?>"　title="返回"><img src="image/btn11.png"  /><img src="image/btn11_hover.png"  style="display:none;opacity:0;"/></a></div>
        </div>
    </div>
                <? }?>

</div>

</div>


<!--FOOTER-->
<? include 'page_footer.php';?>

</div>
<? include 'footer.php';?>
<script type="text/javascript">
function boxproduct(dd){
var winhh=$(window).height();
var _url="recipe_list.php?proid="+dd+"&winhh="+winhh;
$.fancybox({
	'width' : 470,
      'scrolling'      : 'no',
	  'cyclic' : true,
      'showCloseButton'    : false,
      'type'        : 'iframe',
      'href' : _url,
      'autoDimensions':false,
      'hideOnOverlayClick':false,
	  'overlayColor':'#000000',
	  'overlayOpacity':0.8
	  });
}
</script><script type="text/javascript">
  function chang_stand(pdd,sdd){
	 $("#stand_id").val(sdd);
	 $("#colors").val("");
	 if(sdd!=""){
	 	fn_change_stands(pdd,sdd); 
	 }
  }
</script>
<script type="text/javascript">
  $(document).ready(function(){  
	//加入購物
	$("#btn_shopping").click(function(){
	  fn_callshopping(<?=$activeItem['id']?>,$('#stand_id').val());
	});
	//調整右圖位置
    var wh = $(window).height();
    var ww = $(window).width();
	$(".product_more_right").css("right",(ww-500-420)/2);
	var nowhh = wh-55-70-70;
	$(".bigimg").css({"height":nowhh,"width":<?=$CFG->product_b_w?>/(<?=$CFG->product_b_h?>/nowhh)});	
	
	
	
	 $("#slider").css({"height":nowhh}); 
	//alert('bigimg height' +product_b_w); 有誤
	//
	$(window).bind("resize", function() {
		resizewin();
    });

  });
  resizewin();
  function resizewin(){
        var wh = $(window).height(); 
        var ww = $(window).width();
        $(".product_more_right").css("right",(ww-500-420)/2);
        var nowhh = wh-55-70-70;
        $(".bigimg").css({"height":nowhh,"width":<?=$CFG->product_b_w?>/(<?=$CFG->product_b_h?>/nowhh)});   
        $("#slider").css({"height":nowhh});
        $(".bigimg").style("");

  }
</script>

<script type="text/javascript">
/*$(document).ready(function(){
	
	var boxW = $(".bigimg").width();
   var boxH = $(".bigimg").height();
 
 $("#slider").css({"height":boxH,"width": boxW});
 
 $(window).bind("resize", function() {
  
  
  var boxWnew = $(".bigimg").width();
    var boxHmew = $(".bigimg").height();
  
  $("#slider").css({"height":boxHmew,"width":boxWnew});
    });

});//end ready*/
 $(document).ready(function() {
	$('#slider').anythingSlider();
}); // end ready

 
</script>