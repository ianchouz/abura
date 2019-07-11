<?php
$page_title = $_SS->func_name.' - '.$de->title;
$d = (object) $drs;
?>

<?php ob_start();?>
<li><a href="./"><?=$_SS->func_name?></a></li>
<li class="active"><?=$de->title?></li>
<?php $layoutBreadcrumbs = ob_get_contents();ob_end_clean();?>

<?php ob_start();?>
<form action="" name="theForm" method="post" enctype="multipart/form-data" onsubmit="return validate();" class="needs-validation" novalidate>
	<div class="card">
		<div class="card-header text-center">
			<button type="submit" class="btn btn-primary">
				<i class="fa fa-check"></i> &nbsp;送出&nbsp;
			</button>
		</div>
		<div class="card-body card-block">
			<div class="row form-group"><?php $key='status';?>
				<div class="col col-md-2"><label for="<?=$key?>-input" class=" form-control-label">狀態 *</label></div>
				<div class="col-12 col-md-10">
					<label for="radio<?=$key?>1">
						<input type="radio" id="radio<?=$key?>1" name="<?=$key?>" value="1" <?=viz($d->$key)==1?'checked':''?>>
						<span class="btn btn-primary btn-sm" style="margin: 0 10px 0 5px;">啟用</span>
					</label>
					<label for="radio<?=$key?>0">
						<input type="radio" id="radio<?=$key?>0" name="<?=$key?>" value="0" <?=viz($d->$key)==0?'checked':''?>>
						<span class="btn btn-danger btn-sm" style="margin: 0 10px 0 5px;">停用</span>
					</label>
				</div>
			</div>
			<div class="row form-group"><?php $key='title';?>
				<div class="col col-md-2"><label for="<?=$key?>-input" class=" form-control-label">商品名稱 *</label></div>
				<div class="col-12 col-md-10"><input type="text" class="form-control <?=forms_is_invalid($key)?>" id="<?=$key?>-input" name="<?=$key?>" value="<?=vi($d->$key)?>" placeholder="" required>
					<small class="text-muted form-text">商品名稱 不得重複</small>
					<div class="invalid-feedback">請輸入資料</div>
				</div>
			</div>
			<div class="row form-group"><?php $key='price';?>
				<div class="col col-md-2"><label for="<?=$key?>-input" class=" form-control-label">售價 *</label></div>
				<div class="col-12 col-md-10"><input type="text" class="form-control <?=forms_is_invalid($key)?>" id="<?=$key?>-input" name="<?=$key?>" value="<?=vi($d->$key)?>" placeholder="" required>
					<small class="text-muted form-text">請填數字</small>
					<div class="invalid-feedback">請輸入資料</div>
				</div>
			</div>
			<div class="row form-group"><?php $key='price_original';?>
				<div class="col col-md-2"><label for="<?=$key?>-input" class=" form-control-label">原價</label></div>
				<div class="col-12 col-md-10"><input type="text" class="form-control <?=forms_is_invalid($key)?>" id="<?=$key?>-input" name="<?=$key?>" value="<?=vi($d->$key)?>" placeholder="">
					<small class="text-muted form-text">請填數字</small>
					<div class="invalid-feedback">請輸入資料</div>
				</div>
			</div>
			<div class="row form-group"><?php $key='text';?>
				<div class="col col-md-2"><label for="<?=$key?>-input" class=" form-control-label">商品介紹 *</label></div>
				<div class="col-12 col-md-10">
					<?php	create_html_editor($key,vi($d->$key))?>
					<small class="text-muted form-text">請上傳圖片, 圖片寬度為 750px</small>
				</div>
			</div>
			<div class="row form-group"><?php $key='spec1';?>
				<div class="col col-md-2"><label for="<?=$key?>-input" class=" form-control-label">規格1</label></div>
				<div class="col-12 col-md-10"><input type="text" class="form-control <?=forms_is_invalid($key)?>" id="<?=$key?>-input" name="<?=$key?>" value="<?=vi($d->$key)?>" placeholder="">
					<?php	if ( !empty($_SS->file_enable) && !empty($_SS->file_config) ){?>
						<?php	show_upload_one_html(0);?>
					<?php	}?>
				</div>
			</div>
			<div class="row form-group"><?php $key='spec2';?>
				<div class="col col-md-2"><label for="<?=$key?>-input" class=" form-control-label">規格2</label></div>
				<div class="col-12 col-md-10"><input type="text" class="form-control <?=forms_is_invalid($key)?>" id="<?=$key?>-input" name="<?=$key?>" value="<?=vi($d->$key)?>" placeholder="">
					<?php	if ( !empty($_SS->file_enable) && !empty($_SS->file_config) ){?>
						<?php	show_upload_one_html(1);?>
					<?php	}?>
				</div>
			</div>
			<div class="row form-group"><?php $key='spec3';?>
				<div class="col col-md-2"><label for="<?=$key?>-input" class=" form-control-label">規格3</label></div>
				<div class="col-12 col-md-10"><input type="text" class="form-control <?=forms_is_invalid($key)?>" id="<?=$key?>-input" name="<?=$key?>" value="<?=vi($d->$key)?>" placeholder="">
					<?php	if ( !empty($_SS->file_enable) && !empty($_SS->file_config) ){?>
						<?php	show_upload_one_html(2);?>
					<?php	}?>
				</div>
			</div>
			<div class="row form-group"><?php $key='spec4';?>
				<div class="col col-md-2"><label for="<?=$key?>-input" class=" form-control-label">規格4</label></div>
				<div class="col-12 col-md-10"><input type="text" class="form-control <?=forms_is_invalid($key)?>" id="<?=$key?>-input" name="<?=$key?>" value="<?=vi($d->$key)?>" placeholder="">
					<?php	if ( !empty($_SS->file_enable) && !empty($_SS->file_config) ){?>
						<?php	show_upload_one_html(3);?>
					<?php	}?>
				</div>
			</div>
		</div>
		<div class="card-footer text-center">
			<button type="submit" class="btn btn-primary">
				<i class="fa fa-check"></i> &nbsp;送出&nbsp;
			</button>
		</div>
	</div>

	<?php	if( URL_PARAM != 'add' ){?>
	<input type="hidden" name="id" value="<?=$id?>" />
	<?php	}?>
	<input type="hidden" name="<?=PARAM?>" value="<?=$next_act?>" />
	<?=vst_return_url()?>
</form>
<script>
// Example starter JavaScript for disabling form submissions if there are invalid fields
(function() {
  'use strict';
  window.addEventListener('load', function() {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();
</script>
<!-- CK Editor -->
<script src="../vendor/ckeditor/ckeditor.js"></script>
<script src="../vendor/ckfinder/ckfinder.js"></script>
<script>
    var editor = CKEDITOR.replace( 'text', {
        customConfig: '../ckeditor/config.js'
    });
    CKFinder.setupCKEditor( editor, '../ckfinder/' );
</script>

<?php $layoutContent = ob_get_contents();ob_end_clean();?>

<?php include_once('../layouts/master.php'); ?>
