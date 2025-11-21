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
                    <span class="dashicons dashicons-admin-users"></span>
                </div>
                <div class="ezit-stat-info">
                    <?php 
                    global $wp_roles;
                    $role_count = count($wp_roles->get_names());
                    ?>
                    <div class="ezit-stat-value"><?php echo $role_count; ?></div>
                    <div class="ezit-stat-label"><?php _e('User Roles', 'ez-admin-menu'); ?></div>
                </div>
            </div>
            
            <div class="ezit-stat-card">
                <div class="ezit-stat-icon">
                    <span class="dashicons dashicons-visibility"></span>
                </div>
                <div class="ezit-stat-info">
                    <?php
                    // Count configured roles (roles with hidden menus)
                    $configured = 0;
                    foreach ($wp_roles->get_names() as $role_slug => $role_name) {
                        $hidden = get_option('eam_hidden_menus_' . $role_slug, []);
                        if (is_array($hidden) && count($hidden) > 0) {
                            $configured++;
                        }
                    }
                    ?>
                    <div class="ezit-stat-value"><?php echo $configured; ?></div>
                    <div class="ezit-stat-label"><?php _e('Configured Roles', 'ez-admin-menu'); ?></div>
                </div>
            </div>
            
            <div class="ezit-stat-card">
                <div class="ezit-stat-icon">
                    <span class="dashicons dashicons-menu"></span>
                </div>
                <div class="ezit-stat-info">
                    <?php
                    global $menu;
                    $menu_count = is_array($menu) ? count($menu) : 0;
                    ?>
                    <div class="ezit-stat-value"><?php echo $menu_count; ?></div>
                    <div class="ezit-stat-label"><?php _e('Admin Menu Items', 'ez-admin-menu'); ?></div>
                </div>
            </div>
            
            <div class="ezit-stat-card">
                <div class="ezit-stat-icon">
                    <span class="dashicons dashicons-groups"></span>
                </div>
                <div class="ezit-stat-info">
                    <div class="ezit-stat-value"><?php echo count_users()['total_users']; ?></div>
                    <div class="ezit-stat-label"><?php _e('Total Users', 'ez-admin-menu'); ?></div>
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
                <li><?php _e('Go to Menu Visibility tab to configure which menu items are visible to each role', 'ez-admin-menu'); ?></li>
                <li><?php _e('Hide unnecessary menu items to simplify the admin interface for clients', 'ez-admin-menu'); ?></li>
                <li><?php _e('Use Roles & Permissions tab to manage user capabilities', 'ez-admin-menu'); ?></li>
                <li><?php _e('Test your configuration by logging in as different user roles', 'ez-admin-menu'); ?></li>
            </ol>
            
            <div class="ezit-quick-actions">
                <a href="<?php echo admin_url('admin.php?page=ez-admin-menu&tab=boards'); ?>" class="ezit-action-btn ezit-action-btn-primary">
                    <span class="dashicons dashicons-visibility"></span>
                    <?php _e('Configure Menu Visibility', 'ez-admin-menu'); ?>
                </a>
                <a href="<?php echo admin_url('admin.php?page=ez-admin-menu&tab=items'); ?>" class="ezit-action-btn">
                    <span class="dashicons dashicons-admin-users"></span>
                    <?php _e('Manage Roles', 'ez-admin-menu'); ?>
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
                <li><?php _e('Hide/show admin menu items by user role', 'ez-admin-menu'); ?></li>
                <li><?php _e('Control admin bar visibility and items', 'ez-admin-menu'); ?></li>
                <li><?php _e('Manage user roles and capabilities', 'ez-admin-menu'); ?></li>
                <li><?php _e('Create custom roles with specific permissions', 'ez-admin-menu'); ?></li>
                <li><?php _e('Simplify admin interface for clients', 'ez-admin-menu'); ?></li>
                <li><?php _e('Light and dark admin dashboard themes', 'ez-admin-menu'); ?></li>
            </ul>
        </div>
        <?php
    }
}

