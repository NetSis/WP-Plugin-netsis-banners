<?php
/*
Plugin Name: NetSis - Banners
Plugin URI: 
Description: Gerenciamento de banners
Version: 0.3.5
Author: NetSis - Sistemas Web
Author URI: http://www.netsis.com.br
License: Copyright
*/

include_once(sprintf("%s/../netsis/classes/NetSisUserUtil.php", dirname(__FILE__)));

if(!class_exists('NetSisBanners'))
{
    class NetSisBanners
    {
        /**
         * Construct the plugin object
         */
        public function __construct()
        {
        	add_action('init', array(&$this, 'init'));
            add_action('admin_init', array(&$this, 'admin_init'));
			add_action('admin_menu', array(&$this, 'admin_menu'));

			if (isset($_POST['netsis_action']))
				$this->process_POST();
        }

        /**
         * Activate the plugin
         */
        public static function activate()
        {
            // Do nothing
        }

        /**
         * Deactivate the plugin
         */     
        public static function deactivate()
        {
            // Do nothing
        }

		/**
         * Uninstall the plugin
         */     
        public static function uninstall()
        {
            // Do nothing
        }

        public function init()
        {
        	//shortcodes
			add_shortcode('netsis_banners', array(&$this, 'shortcode_banners'));
        }

		/**
		 * hook into WP's admin_init action hook
		 */
		public function admin_init()
		{
			// Add style
			wp_enqueue_style('netsis-site', plugins_url('/style.css', __FILE__));

			// Add URL Parser
			wp_enqueue_script('jQuery-URL-Parser', plugins_url('/netsis/js/jQuery-URL-Parser/purl.js'));
		}

		/**
		 * add a menu
		 */     
		public function admin_menu()
		{
			add_menu_page('Banners', 'Banners', NetSisUserUtil::GetKeyPermission(NetSisUserUtil::UserRole_Editor), 'netsis_banner_grid', array(&$this, 'netsis_banner_grid'));
			add_submenu_page(netsis_banner_grid, 'Novo', 'Novo', NetSisUserUtil::GetKeyPermission(NetSisUserUtil::UserRole_Editor), 'netsis_banner_form', array(&$this, 'netsis_banner_form'));
		}
		
		public function netsis_banner_grid()
		{
			if (!NetSisUserUtil::CurrentUserCanActLike(NetSisUserUtil::UserRole_Editor))
				wp_die(__('You do not have sufficient permissions to access this page.'));

			include(sprintf("%s/templates/netsis_banner_grid.php", dirname(__FILE__)));
		}

		public function netsis_banner_form()
		{
			if (!NetSisUserUtil::CurrentUserCanActLike(NetSisUserUtil::UserRole_Editor))
				wp_die(__('You do not have sufficient permissions to access this page.'));

			include(sprintf("%s/templates/netsis_banner_form.php", dirname(__FILE__)));
		}

		public function process_POST()
		{
			global $wpdb;

			$item = null;
			switch($_POST['netsis_object'])
			{
				case 'Banner':
					include_once(sprintf("%s/classes/".$_POST['netsis_object'].'.php', dirname(__FILE__)));
					$item = new $_POST['netsis_object']();
					break;
			}

			if ($item != null)
			{
				switch($_POST['netsis_action'])
				{
					case 'insert':
						$item->LoadBy_array($_POST);

						$item->Insert($_FILES);
						break;

					case 'update':
						$item->Load($_POST['id']);
						$item->LoadBy_array($_POST);

						$item->Update($_FILES);
						break;

					case 'delete':
						$item->Delete($_POST['items']);
						break;
				}
			}
		}

		public function shortcode_banners($atts)
		{
			ob_start();
			include(sprintf("%s/templates/shortcodes/banners.php", dirname(__FILE__)));
			return ob_get_clean();
		}
    }

	// Installation and uninstallation hooks
    register_activation_hook(__FILE__, array('NetSisBanners', 'activate'));
    register_deactivation_hook(__FILE__, array('NetSisBanners', 'deactivate'));
	register_uninstall_hook(__FILE__, array('NetSisBanners', 'uninstall'));

    // instantiate the plugin class
    $netsis_NetSisBanners = new NetSisBanners();
}
?>