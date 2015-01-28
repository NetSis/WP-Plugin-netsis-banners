<?php
include_once(sprintf("%s/../../classes/Banner.php", dirname(__FILE__)));

wp_enqueue_style('jquery-bxslider', plugins_url('js/jquery.bxslider/jquery.bxslider.css', dirname(__FILE__)));
wp_enqueue_script('jquery-bxslider', plugins_url('js/jquery.bxslider/jquery.bxslider.min.js', dirname(__FILE__)), array('jquery'));
?>
<ul class="bxslider">
<?php
global $wpdb;

$banner = new Banner();
$banners = $wpdb->get_results('SELECT file,link FROM '.$banner->get_table());

$upload_dir = wp_upload_dir();
foreach($banners as $b) {
	$banner = new Banner();
	$banner->LoadBy_array($b);

	echo '<li>';

	if ($banner->link != '')
		echo '<a href="'.$banner->get_link().'">';

	echo '<img src="'.$upload_dir['baseurl'].'/banners/'.$banner->file.'" alt="Banner" />';

	if ($banner->link != '')
		echo '</a>';

	echo '</li>';
}
?>
</ul>