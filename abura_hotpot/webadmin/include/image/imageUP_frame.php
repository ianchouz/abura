<div class="-image-choice-area">
  &nbsp;<?
  if ($this->showimage && $this->showflash){
    echo '圖片或FLASH';
  }else if($this->showimage){
    echo '圖片';
  }else if($this->showflash){
    echo 'FLASH';
  }
  ?>需求尺寸：
  <?if ($this->showwidth!='' && $this->showwidth!='0' && $this->showwidth!='100%'){?>
  寬 <?=$this->showwidth?>px
  <?}?>
  <?if ($this->showheight!='' && $this->showheight!='0'){?>
   高 <?=$this->showheight?>px
  <?}?>
  <br><input type="radio" name="<?=$this->prefix?>useimage" id="<?=$this->prefix?>useimageN" value="none" <?=($fileexit==0 ?"checked":"")?> onclick="controlButton('<?=$this->prefix?>',true)"/> 無<br/>
  <div>
    <?if ($this->showimage){?>
    <input type="radio" name="<?=$this->prefix?>useimage" id="<?=$this->prefix?>useimageY" value="use" onclick="controlButton('<?=$this->prefix?>',false)" <?=($fileexit==1 && $filetyle=='img' ?"checked":"")?> /> 使用圖片 &nbsp;
    <input type="button" class="btn_now_choice" value="現有圖片選擇" onclick="changeIMG('<?=$this->prefix?>')">
    <input type="button" class="btn_new_choice" value="新圖片上傳" onclick="uploadIMG('<?=$this->prefix?>')">
    <br>
    <table>
      <tr>
        <td valign='top' width="10"></td>
        <td valign='top'>
           <div id="<?=$this->prefix?>imagePreView" class="imagePreView" style="padding:5px;">
           <div id="<?=$this->prefix?>imagearea" class="imagearea" style="width:<?=($this->prewidth+10)?>px;height:<?=($this->preheight+10)?>px;" data-w="<?=$this->prewidth?>"  data-h="<?=$this->preheight?>">
             <?
           if ($filetyle=='img' ){
             if ($fileexit==0){
              $fixleft  = (round(($this->prewidth - 48) / 2)) ;
              $fixtop  = (round(($this->preheight - 48) / 2));
              ?><img src="<?=$CFG->url_admin?>images/unknow.png" style="padding-top:<?=$fixtop?>px; left:<?=$fixleft?>px; width:48px; height:48px;"><?
             }else{
              $resizetool = new reSizeImage($this->prewidth,$this->preheight,$CFG->root_user.$this->filepath);
              ?><img src="<?=$CFG->web_user.$this->filepath?>" style="top:<?=$resizetool->thumbtop?>px; left:<?=$resizetool->thumbleft?>px; width:<?=($resizetool->thumbwidth?$resizetool->thumbwidth:"")?>px; height:<?=($resizetool->thumbheight?$resizetool->thumbheight:"")?>px;"><?
             }
           }?>
           </div>
        <td valign='top'>
        <div style="padding:5px 5px 0px 5px;font-size:10px;margin-left:1px;line-height:13px;margin-top:5px;">
          圖片資訊<br>
          寬度:<span id="<?=$this->prefix?>owidth"><? if ($filetyle=='img' ){ echo $resizetool->width;}?></span>px<br/>
          高度:<span id="<?=$this->prefix?>oheight"><? if ($filetyle=='img' ){ echo $resizetool->height;}?></span>px<br/>
          檔名:<span id="<?=$this->prefix?>opath"><? if ($filetyle=='img' ){ echo $this->filename;}?></span><br/>
        </div></td>
      </tr>
    </table>
    <?}?>
    <?if ($this->showflash){?>
    <input type="radio" name="<?=$this->prefix?>useimage" id="<?=$this->prefix?>useFLASHY" value="flash" onclick="controlButton('<?=$this->prefix?>',false)" <?=($fileexit==1&&$filetyle=='swf' ?"checked":"")?> /> 使用FLASH &nbsp;
    <input type="button" value="現有FLASH選擇" onclick="changeFLASH('<?=$this->prefix?>')">
    <input type="button" value="新FLASH上傳" onclick="uploadFLASH('<?=$this->prefix?>')">
    <br>
    <table>
      <tr>
        <td valign='top' width="10"></td>
        <td valign='top'>
          FLASH 資訊<br>
          檔名:<span id="<?=$this->prefix?>opath"><? if ($filetyle=='swf' ){ echo $this->filename;}?></span><br/>
           <div id="<?=$this->prefix?>flashPreView" class="flashPreView" style="padding:5px;">
           <div id="<?=$this->prefix?>flasharea" style="width:<?=($this->showwidth+10)?>px;height:<?=($this->showheight+10)?>px;" data-w="<?=$this->showwidth?>"  data-h="<?=$this->showheight?>">
             <?
           if ($filetyle=='swf' ){
             if ($fileexit==0){
              $fixleft  = (round(($this->prewidth - 48) / 2)) ;
              $fixtop  = (round(($this->preheight - 48) / 2));
              ?><img src="<?=$CFG->url_admin?>images/unknow.png" style="padding-top:<?=$fixtop?>px; left:<?=$fixleft?>px; width:48px; height:48px;"><?
             }else{
              ?><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="<?=$this->showwidth?>px" height="<?=$this->showheight?>px">
                <param name="movie" value="<?=$CFG->web_user.$this->filepath?>">
                <param name="quality" value="high">
                <embed src="<?=$CFG->web_user.$this->filepath?>" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"  width="<?=$this->showwidth?>px" height="<?=$this->showheight?>px"></embed></object><?
             }
           }?>
           </div>
      </tr>
    </table>
    <?}?>
  </div>
  <input type="hidden" name="<?=$this->prefix?>path" id="<?=$this->prefix?>path" value="<?=$this->filepath?>"/>
  <input type="hidden" name="<?=$this->prefix?>"     id="<?=$this->prefix?>"     value="<?=$this->filename?>"/>
  <input type="hidden" name="<?=$this->prefix?>old"  id="<?=$this->prefix?>old" value="<?=$this->filename?>"/>
  <input type="hidden" name="<?=$this->prefix?>width"  id="<?=$this->prefix?>width" value="<?=$this->showwidth?>" />
  <input type="hidden" name="<?=$this->prefix?>height" id="<?=$this->prefix?>height" value="<?=$this->showheight?>" />
  <input type="hidden" name="<?=$this->prefix?>noneHeight" id="<?=$this->prefix?>noneHeight" value="<?=$this->noneHeight?>" />
  <input type="hidden" name="<?=$this->prefix?>noneWidth" id="<?=$this->prefix?>noneWidth" value="<?=$this->noneWidth?>" />
  <input type="hidden" name="<?=$this->prefix?>fixdir" id="<?=$this->prefix?>fixdir" value="<?=$this->fixdir?>" />
  <script>var <?=$this->prefix?>Browser;</script>
</div>
