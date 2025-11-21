<?php
/**
 * Menu Visibility Tab
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

class EAM_Tab_Boards {
    
    /**
     * Register tab
     */
    public static function register() {
        EAM_Tab_Controller::register_tab('boards', [
            'label' => __('Menu Visibility', 'ez-admin-menu'),
            'icon' => 'dashicons-visibility',
            'callback' => [__CLASS__, 'render'],
            'priority' => 20,
        ]);
    }
    
    /**
     * Render tab content
     */
    public static function render() {
        // Get all WordPress roles
        global $wp_roles;
        $roles = $wp_roles->get_names();
        
        ?>
        <h2 class="ezit-page-title"><?php _e('Menu Visibility Control', 'ez-admin-menu'); ?></h2>
        <p class="ezit-description"><?php _e('Control which admin menu items are visible to different user roles. Hide unnecessary menu items to simplify the admin interface for your clients.', 'ez-admin-menu'); ?></p>
        
        <div class="ezit-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3>
                    <span class="dashicons dashicons-visibility"></span>
                    <?php _e('WordPress Roles', 'ez-admin-menu'); ?>
                </h3>
            </div>
            
            <p style="margin-bottom: 20px; color: #6b7280;">
                <?php _e('Select a role below to configure which admin menu items are visible to users with that role.', 'ez-admin-menu'); ?>
            </p>
            
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th style="width: 30%;"><?php _e('Role', 'ez-admin-menu'); ?></th>
                        <th><?php _e('Description', 'ez-admin-menu'); ?></th>
                        <th style="width: 20%;"><?php _e('Hidden Menu Items', 'ez-admin-menu'); ?></th>
                        <th style="width: 15%;"><?php _e('Actions', 'ez-admin-menu'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($roles as $role_slug => $role_name): 
                        $hidden_count = get_option('eam_hidden_menus_' . $role_slug, []);
                        $hidden_count = is_array($hidden_count) ? count($hidden_count) : 0;
                        
                        // Get role description
                        $role_descriptions = [
                            'administrator' => __('Full access to all WordPress features', 'ez-admin-menu'),
                            'editor' => __('Can publish and manage posts including posts of other users', 'ez-admin-menu'),
                            'author' => __('Can publish and manage their own posts', 'ez-admin-menu'),
                            'contributor' => __('Can write and manage their own posts but cannot publish', 'ez-admin-menu'),
                            'subscriber' => __('Can only manage their profile', 'ez-admin-menu'),
                        ];
                        $description = isset($role_descriptions[$role_slug]) ? $role_descriptions[$role_slug] : __('Custom role', 'ez-admin-menu');
                    ?>
                        <tr>
                            <td>
                                <strong><?php echo esc_html($role_name); ?></strong>
                                <br>
                                <code style="font-size: 11px; color: #6b7280;"><?php echo esc_html($role_slug); ?></code>
                            </td>
                            <td style="color: #6b7280; font-size: 13px;">
                                <?php echo esc_html($description); ?>
                            </td>
                            <td>
                                <span style="display: inline-block; background: <?php echo $hidden_count > 0 ? 'rgba(239, 68, 68, 0.1)' : 'rgba(163, 230, 53, 0.1)'; ?>; color: <?php echo $hidden_count > 0 ? '#ef4444' : '#16a34a'; ?>; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                    <?php echo $hidden_count; ?> <?php _e('hidden', 'ez-admin-menu'); ?>
                                </span>
                            </td>
                            <td>
                                <button class="button button-primary button-small" onclick="cmpShowMessage('Menu visibility configuration coming soon!', 'info')">
                                    <?php _e('Configure', 'ez-admin-menu'); ?>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }
}

