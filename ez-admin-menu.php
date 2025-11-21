<?php
/**
 * Plugin Name:       Ez Admin Menu
 * Plugin URI:        https://github.com/ez-it-solutions/ez-admin-menu
 * Description:       Powerful WordPress admin menu customization plugin with drag-and-drop interface, role-based permissions, and custom menu items.
 * Version:           0.1.0
 * Author:            Ez IT Solutions
 * Author URI:        https://ez-it-solutions.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       ez-admin-menu
 * Domain Path:       /languages
 *
 * @package Ez_Admin_Menu
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Ez_Admin_Menu' ) ) {
	/**
	 * Main plugin class for Ez Admin Menu.
	 *
	 * This class handles admin menu customization, role-based permissions,
	 * and provides a comprehensive interface for managing WordPress admin menus.
	 */
	class Ez_Admin_Menu {

		/**
		 * Singleton instance.
		 *
		 * @var Ez_Admin_Menu|null
		 */
		protected static $instance = null;

		/**
		 * Get singleton instance.
		 *
		 * @return Ez_Admin_Menu
		 */
		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Ez_Admin_Menu constructor.
		 *
		 * Hooks are registered here to keep the global namespace minimal.
		 */
		protected function __construct() {
			$this->define_constants();
			$this->includes();

			add_action( 'init', array( $this, 'register_post_types' ) );
			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'register_frontend_assets' ) );
			add_shortcode( 'ez_admin_menu', array( $this, 'render_admin_menu_shortcode' ) );
			
			// Initialize admin interface
			if ( is_admin() ) {
				EAM_Admin::init();
			}
		}

		/**
		 * Include required files.
		 */
		protected function includes() {
			require_once EAM_PLUGIN_DIR . 'includes/meta-boxes.php';
			
			// Load admin class
			if ( is_admin() ) {
				require_once EAM_PLUGIN_DIR . 'admin/class-eam-admin.php';
			}
			
			// Load company info if available
			if ( file_exists( EAM_PLUGIN_DIR . 'includes/class-company-info.php' ) ) {
				require_once EAM_PLUGIN_DIR . 'includes/class-company-info.php';
				EZIT_Company_Info::init();
			}
		}

		/**
		 * Register custom post types for menu configurations.
		 */
		public function register_post_types() {
			register_post_type(
				'eam_menu_config',
				array(
					'labels'      => array(
						'name'          => __( 'Menu Configurations', 'ez-admin-menu' ),
						'singular_name' => __( 'Menu Configuration', 'ez-admin-menu' ),
					),
					'public'      => false,
					'show_ui'     => true,
					'show_in_menu'=> false,
					'supports'    => array( 'title' ),
				)
			);

			register_post_type(
				'eam_menu_item',
				array(
					'labels'      => array(
						'name'          => __( 'Custom Menu Items', 'ez-admin-menu' ),
						'singular_name' => __( 'Custom Menu Item', 'ez-admin-menu' ),
					),
					'public'      => false,
					'show_ui'     => true,
					'show_in_menu'=> false,
					'supports'    => array( 'title' ),
				)
			);
		}

		/**
		 * Define plugin-wide constants.
		 */
		protected function define_constants() {
			if ( ! defined( 'EAM_PLUGIN_VERSION' ) ) {
				define( 'EAM_PLUGIN_VERSION', '0.1.0' );
			}

			if ( ! defined( 'EAM_PLUGIN_FILE' ) ) {
				define( 'EAM_PLUGIN_FILE', __FILE__ );
			}

			if ( ! defined( 'EAM_PLUGIN_DIR' ) ) {
				define( 'EAM_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			}

			if ( ! defined( 'EAM_PLUGIN_URL' ) ) {
				define( 'EAM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			}
		}

		/**
		 * Load plugin text domain for translations.
		 */
		public function load_textdomain() {
			load_plugin_textdomain( 'ez-admin-menu', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}

		/**
		 * Register and enqueue frontend assets for the admin menu.
		 */
		public function register_frontend_assets() {
			wp_register_style(
				'eam-frontend',
				EAM_PLUGIN_URL . 'assets/css/frontend.css',
				array(),
				EAM_PLUGIN_VERSION
			);

			// Styles are only enqueued when the shortcode is used via wp_enqueue_style in the renderer.
		}


		/**
		 * Render the [ez_admin_menu] shortcode output.
		 *
		 * Displays admin menu customization interface or menu preview.
		 *
		 * @param array  $atts    Shortcode attributes.
		 * @param string $content Enclosed content (unused for now).
		 *
		 * @return string
		 */
		public function render_admin_menu_shortcode( $atts, $content = '' ) {
			$atts = shortcode_atts(
				array(
					'config_id' => 0,
					'style'     => 'default',
				),
				$atts,
				'ez_admin_menu'
			);

			wp_enqueue_style( 'eam-frontend' );

			$config_id = absint( $atts['config_id'] );
			$sections = array();

			// Try to load configuration from database if config_id is provided.
			if ( $config_id > 0 ) {
				$config_post = get_post( $config_id );
				if ( $config_post && 'eam_menu_config' === $config_post->post_type ) {
					$sections = get_post_meta( $config_id, '_eam_menu_sections', true );
					if ( ! is_array( $sections ) ) {
						$sections = array();
					}
				}
			}

			// Fallback to demo content if no sections found.
			if ( empty( $sections ) ) {
				$sections = $this->get_demo_sections();
			}

			ob_start();
			?>
			<div class="eam-menu eam-menu-style-<?php echo esc_attr( $atts['style'] ); ?>">
				<div class="eam-menu-inner">
					<?php foreach ( $sections as $section ) : ?>
						<?php if ( ! empty( $section['title'] ) || ! empty( $section['items'] ) ) : ?>
							<div class="eam-menu-section">
								<?php if ( ! empty( $section['title'] ) ) : ?>
									<h2 class="eam-menu-heading"><?php echo esc_html( $section['title'] ); ?></h2>
								<?php endif; ?>
								<?php if ( ! empty( $section['items'] ) && is_array( $section['items'] ) ) : ?>
									<ul class="eam-menu-list">
										<?php foreach ( $section['items'] as $item ) : ?>
											<li><?php echo esc_html( $item ); ?></li>
										<?php endforeach; ?>
									</ul>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			</div>
			<?php

			return ob_get_clean();
		}

		/**
		 * Get demo sections for fallback display.
		 *
		 * @return array
		 */
		protected function get_demo_sections() {
			return array(
				array(
					'title' => 'Content',
					'items' => array( 'Posts', 'Pages', 'Media', 'Comments' ),
				),
				array(
					'title' => 'Appearance',
					'items' => array( 'Themes', 'Customize', 'Widgets', 'Menus', 'Header', 'Background' ),
				),
				array(
					'title' => 'Plugins',
					'items' => array( 'Installed Plugins', 'Add New', 'Plugin Editor' ),
				),
				array(
					'title' => 'Users',
					'items' => array( 'All Users', 'Add New', 'Profile', 'Roles & Permissions' ),
				),
				array(
					'title' => 'Tools',
					'items' => array( 'Available Tools', 'Import', 'Export', 'Site Health' ),
				),
				array(
					'title' => 'Settings',
					'items' => array( 'General', 'Writing', 'Reading', 'Discussion', 'Media', 'Permalinks' ),
				),
			);
		}
	}
}

/**
 * Initialize the plugin.
 */
function ez_admin_menu() {
	return Ez_Admin_Menu::instance();
}

ez_admin_menu();
