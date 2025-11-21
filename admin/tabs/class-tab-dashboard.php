<?php
/**
 * Dashboard Tab
 * 
 * @package    Ez_Admin_Menu
 * @subpackage Admin
 * @author     Chris Hultberg <chris@ez-it-solutions.com>
 * @copyright  2025 Ez IT Solutions
 * @license    GPL-3.0-or-later
 * @version    0.1.0
 * @since      0.1.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class EAM_Tab_Dashboard {
    
    /**
     * Register tab
     */
    public static function register() {
        EAM_Tab_Controller::register_tab('dashboard', [
            'label' => __('Dashboard', 'ez-admin-menu'),
            'icon' => 'dashicons-dashboard',
            'callback' => [__CLASS__, 'render'],
            'priority' => 10,
        ]);
    }
    
    /**
     * Render tab content
     */
    public static function render() {
        ?>
        <h2 class="ezit-page-title"><?php _e('Getting Started', 'ez-admin-menu'); ?></h2>
        <p class="ezit-description"><?php _e('Customize WordPress admin menus with role-based permissions and drag-and-drop simplicity.', 'ez-admin-menu'); ?></p>
        
        <!-- Stats Grid -->
        <div class="ezit-stats-grid">
            <div class="ezit-stat-card">
                <div class="ezit-stat-icon">
                    <span class="dashicons dashicons-welcome-widgets-menus"></span>
                </div>
                <div class="ezit-stat-info">
                    <div class="ezit-stat-value"><?php echo wp_count_posts('eam_board')->publish; ?></div>
                    <div class="ezit-stat-label"><?php _e('Boards', 'ez-admin-menu'); ?></div>
                </div>
            </div>
            
            <div class="ezit-stat-card">
                <div class="ezit-stat-icon">
                    <span class="dashicons dashicons-list-view"></span>
                </div>
                <div class="ezit-stat-info">
                    <div class="ezit-stat-value"><?php echo wp_count_posts('eam_menu_item')->publish; ?></div>
                    <div class="ezit-stat-label"><?php _e('Menu Items', 'ez-admin-menu'); ?></div>
                </div>
            </div>
            
            <div class="ezit-stat-card">
                <div class="ezit-stat-icon">
                    <span class="dashicons dashicons-admin-appearance"></span>
                </div>
                <div class="ezit-stat-info">
                    <div class="ezit-stat-value">2</div>
                    <div class="ezit-stat-label"><?php _e('Frame Styles', 'ez-admin-menu'); ?></div>
                </div>
            </div>
        </div>
        
        <!-- Quick Start Card -->
        <div class="ezit-card">
            <h3>
                <span class="dashicons dashicons-info"></span>
                <?php _e('Quick Start Guide', 'ez-admin-menu'); ?>
            </h3>
            <ol class="ezit-sidebar-list">
                <li><?php _e('Create a new Board and add sections with menu items', 'ez-admin-menu'); ?></li>
                <li><?php _e('Use the shortcode [Ez_Admin_Menu board_id="123"] in any page or post', 'ez-admin-menu'); ?></li>
                <li><?php _e('Customize the frame style and appearance to match your brand', 'ez-admin-menu'); ?></li>
                <li><?php _e('Preview your customized admin menu', 'ez-admin-menu'); ?></li>
            </ol>
            
            <div class="ezit-quick-actions">
                <a href="<?php echo admin_url('post-new.php?post_type=cmp_board'); ?>" class="ezit-action-btn ezit-action-btn-primary">
                    <span class="dashicons dashicons-plus"></span>
                    <?php _e('Create New Board', 'ez-admin-menu'); ?>
                </a>
                <a href="<?php echo admin_url('post-new.php?post_type=cmp_menu_item'); ?>" class="ezit-action-btn">
                    <span class="dashicons dashicons-plus"></span>
                    <?php _e('Add Menu Item', 'ez-admin-menu'); ?>
                </a>
            </div>
        </div>
        
        <!-- Features Card -->
        <div class="ezit-card" style="margin-top: 24px;">
            <h3>
                <span class="dashicons dashicons-star-filled"></span>
                <?php _e('Features', 'ez-admin-menu'); ?>
            </h3>
            <ul class="ezit-sidebar-list">
                <li><?php _e('Role-based menu visibility and permissions', 'ez-admin-menu'); ?></li>
                <li><?php _e('Dynamic sections and menu items', 'ez-admin-menu'); ?></li>
                <li><?php _e('Link menu items to WordPress pages or custom URLs', 'ez-admin-menu'); ?></li>
                <li><?php _e('Shortcode support for easy embedding', 'ez-admin-menu'); ?></li>
                <li><?php _e('Light and dark admin modes', 'ez-admin-menu'); ?></li>
                <li><?php _e('Responsive design for all devices', 'ez-admin-menu'); ?></li>
            </ul>
        </div>
        <?php
    }
}

