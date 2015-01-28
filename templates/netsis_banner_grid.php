<?php
include_once(sprintf("%s/../../netsis/templates/grid-ui.php", dirname(__FILE__)));
include_once(sprintf("%s../classes/Banner.php", plugin_dir_path(__FILE__)));
?>
<script type="text/javascript">
jQuery(function ($) {
	$(document).ready(function() {
		$('#dialog-banner-form').dialog({
			autoOpen: false,
			resizable: false,
			modal: true,
			width: 'auto'
		});
	});
});
</script>
<div id="dialog-banner-form"></div>
<div class="wrap">
	<h2>Banners <a href="<?php echo get_admin_url(); ?>admin.php?page=netsis_banner_form" class="add-new-h2">Adicionar Novo</a></h2>
	<div style="width:983px;">
<?php
$msg = '';
$erro = false;
if ($_POST['netsis_action'] == 'delete')
{
	if (isset($_POST['items']))
		$msg = (sizeof($_POST['items']) > 1) ? 'Banners excluídos.' : 'Banner excluído.';
	else
	{
		$msg = 'Nenhum banner selecionado para exclusão.';
		$erro = true;
	}
}
else if ($_POST['netsis_action'] == 'insert')
	$msg = 'Novo banner cadastrado.';
else if ($_POST['netsis_action'] == 'update')
	$msg = 'Banner atualizado.';

if ($msg != '') {
?>
	<div id="message" class="<?php echo ($erro) ? 'error' : 'updated'; ?> below-h2"><p><?php echo $msg; ?></p></div>
<?php } ?>
		<form id="frmGrid" method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
			<input type="hidden" name="netsis_object" value="Banner">
			<input type="hidden" name="netsis_action" value="delete">
			<div class="tablenav top">
				<div class="alignright actions">
					<input type="submit" name="" id="doaction_top" class="button action delete-confirmation" value="Excluir">
				</div>
			</div>
			<table class="widefat">
				<thead>
					<tr>
						<th scope="col" id="cb" class="manage-column column-cb check-column" style=""><label class="screen-reader-text" for="cb-select-all-1">Selecionar Tudo</label><input id="cb-select-all-1" type="checkbox"></th>
						<th>Banner</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th scope="col" id="cb" class="manage-column column-cb check-column" style=""><label class="screen-reader-text" for="cb-select-all-1">Selecionar Tudo</label><input id="cb-select-all-1" type="checkbox"></th>
						<th>Banner</th>
					</tr>
				</tfoot>
				<tbody>
<?php
global $wpdb;

$alternate = 1;

$banner = new Banner();

$sql = 'SELECT * FROM '.$banner->get_table().' ORDER BY file';

$items = $wpdb->get_results($sql);
foreach($items as $item) {
	$tr_class = '';

	if (isset($_POST['id']) && (intval($item->id == $_POST['id'])))
		$tr_class .= ' just_updated_row';
	else if ($item->id == $wpdb->insert_id)
		$tr_class .= ' just_inserted_row';

	if ($alternate > 0)
		$tr_class .= ' alternate';

	$alternate = $alternate * -1;
?>
					<tr<?php if (strlen($tr_class) > 0) echo ' class="'.substr($tr_class, 1).'"'; ?>>
						<th scope="row" class="check-column"><input type="checkbox" name="items[]" value="<?php echo $item->id; ?>"></th>
						<td><a class="row-title" href="<?php echo get_admin_url(); ?>admin.php?page=netsis_banner_form&id=<?php echo $item->id; ?>" title="<?php echo $item->file; ?>"><img src="<?php echo Banner::get_baseurl().$item->file; ?>" alt="<?php echo $item->file; ?>" /></a></td>
					</tr>
<?php } ?>
				</tbody>
			</table>
			<div class="tablenav bottom">
				<div class="alignright actions">
					<input type="submit" name="" id="doaction_bottom" class="button action delete-confirmation" value="Excluir">
				</div>
			</div>
		</form>
	</div>
</div>