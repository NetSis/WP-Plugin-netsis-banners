<?php
include_once(sprintf("%s/../../../../wp-load.php", dirname(__FILE__)));
include_once(sprintf("%s/../../netsis/templates/form-ui.php", dirname(__FILE__)));
include_once(sprintf("%s/../classes/Banner.php", dirname(__FILE__)));

global $wpdb;

$item = new Banner();

if (NetSisUserUtil::CurrentUserCanActLike(NetSisUserUtil::UserRole_Editor) && isset($_GET['id']) && (intval($_GET['id']) > 0))
	$item->Load($_GET['id']);
?>
<script type="text/javascript">
jQuery(function ($) {
	$(document).ready(function() {
		$('#dialog-imagem-invalida').dialog({
			autoOpen: false,
			resizable: false,
			modal: true,
			buttons: {
				'Ok': function() {
					$(this).dialog('close');
				}
			}
		});

		$('input#imagem').change(function(){
			var exts = ['jpg','jpeg', 'png', 'gif'];
			if ($(this).val() != '') {
				var ext = $(this).val().split('.');
				if ($.inArray(ext[ext.length-1].toLowerCase(), exts) == -1 ) {
					$(this).val('');
					$(this).addClass('must-fill-in-empty');
					$('#dialog-imagem-invalida').dialog('open');
				}
			}
		});
	});
});
</script>
<div id="dialog-imagem-invalida" class="dialog-ui" title="Alerta" style="display:none;">
  <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>Formato de imagem inválido!<br /><br />Extensões permitidas: .jpg, .jpeg, .png, .gif</p>
</div>
<div class="wrap">
<?php 
if (NetSisUserUtil::CurrentUserCanActLike(NetSisUserUtil::UserRole_Editor)) {
	if ($item->id > 0) {
?>
	<h2>Editar Banner</h2>
<?php } else { ?>
	<h2>Novo Banner</h2>
<?php } ?>
<?php if (isset($_POST['id'])) { ?>
	<div id="message" class="updated below-h2"><p>Banner atualizado.</p></div>
<?php }
} else { ?>
	<header class="entry-header">
		<h1 class="entry-title">Inscrição</h1>
	</header>
<?php } ?>
	<form method="POST" id="frmBanner"  enctype="multipart/form-data" action="?page=netsis_banner_grid">
		<input type="hidden" name="netsis_object" value="Banner" />
<?php if ($item->id > 0) { ?>
		<input type="hidden" name="netsis_action" value="update" />
		<input type="hidden" name="id" value="<?php echo $item->id; ?>" />
<?php } else { ?>
		<input type="hidden" name="netsis_action" value="insert" />
<?php } ?>
		<div class="form-wrap">
			<div class="form-field"><span class="must-fill-in-empty">Campos obrigatórios.</span></div>
			<div class="form-field">
				<label for="link">Link (deixe o campo em branco para nenhum)</label>
				<p>Ex.: www.endereco.com.br</p>
				<input type="text" id="link" name="link" maxlength="500" style="width:600px;" value="<?php echo $item->link; ?>" />
			</div>
			<div class="form-field">
				<label for="imagem">Imagem</label>
				<p>Formatos de arquivo permitidos: .jpg, .jpeg, .png, .gif</p>
				<p>Envie apenas imagens com as dimensões de 940 x 280 px</p>
				<input type="file" name="imagem" id="imagem" size="40" class="must-fill-in" />
			</div>
			<div style="width:600px;">
				<input type="submit" class="button-primary<?php if ($item->id == 0) echo ' must-fill-in-check'; ?>" value="Salvar" style="float:right;" />
			</div>
		</div>
	</form>
</div>