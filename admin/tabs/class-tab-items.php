<?php
/**
 * Roles & Permissions Tab
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

class EAM_Tab_Items {
    
    /**
     * Register tab
     */
    public static function register() {
        EAM_Tab_Controller::register_tab('items', [
            'label' => __('Roles & Permissions', 'ez-admin-menu'),
            'icon' => 'dashicons-admin-users',
            'callback' => [__CLASS__, 'render'],
            'priority' => 30,
        ]);
    }
    
    /**
     * Render tab content
     */
    public static function render() {
        // Get all WordPress roles
        global $wp_roles;
        $roles = $wp_roles->roles;
        $role_names = $wp_roles->get_names();
        
        ?>
        <h2 class="ezit-page-title"><?php _e('Roles & Permissions Management', 'ez-admin-menu'); ?></h2>
        <p class="ezit-description"><?php _e('Manage WordPress user roles and their capabilities. Create custom roles or modify existing ones to control what users can do in the admin area.', 'ez-admin-menu'); ?></p>
        
        <div class="ezit-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3>
                    <span class="dashicons dashicons-admin-users"></span>
                    <?php _e('WordPress Roles', 'ez-admin-menu'); ?>
                </h3>
                <button class="ezit-action-btn ezit-action-btn-primary" onclick="cmpShowMessage('Custom role creation coming soon!', 'info')">
                    <span class="dashicons dashicons-plus"></span>
                    <?php _e('Create Custom Role', 'ez-admin-menu'); ?>
                </button>
            </div>
            
            <p style="margin-bottom: 20px; color: #6b7280;">
                <?php _e('Manage user roles and their capabilities. Each role defines what users can and cannot do in WordPress.', 'ez-admin-menu'); ?>
            </p>
            
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th style="width: 25%;"><?php _e('Role Name', 'ez-admin-menu'); ?></th>
                        <th style="width: 15%;"><?php _e('User Count', 'ez-admin-menu'); ?></th>
                        <th><?php _e('Key Capabilities', 'ez-admin-menu'); ?></th>
                        <th style="width: 15%;"><?php _e('Actions', 'ez-admin-menu'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($role_names as $role_slug => $role_name): 
                        $role = $roles[$role_slug];
                        $capabilities = $role['capabilities'];
                        
                        // Count users with this role
                        $user_count = count(get_users(['role' => $role_slug, 'fields' => 'ID']));
                        
                        // Get key capabilities
                        $key_caps = [];
                        if (isset($capabilities['manage_options']) && $capabilities['manage_options']) {
                            $key_caps[] = 'Manage Options';
                        }
                        if (isset($capabilities['edit_posts']) && $capabilities['edit_posts']) {
                            $key_caps[] = 'Edit Posts';
                        }
                        if (isset($capabilities['publish_posts']) && $capabilities['publish_posts']) {
                            $key_caps[] = 'Publish Posts';
                        }
                        if (isset($capabilities['delete_posts']) && $capabilities['delete_posts']) {
                            $key_caps[] = 'Delete Posts';
                        }
                        
                        $cap_display = !empty($key_caps) ? implode(', ', array_slice($key_caps, 0, 3)) : __('Limited access', 'ez-admin-menu');
                        if (count($key_caps) > 3) {
                            $cap_display .= '...';
                        }
                    ?>
                        <tr>
                            <td>
                                <strong><?php echo esc_html($role_name); ?></strong>
                                <br>
                                <code style="font-size: 11px; color: #6b7280;"><?php echo esc_html($role_slug); ?></code>
                            </td>
                            <td>
                                <span style="display: inline-block; background: rgba(163, 230, 53, 0.1); color: #16a34a; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                    <?php echo $user_count; ?> <?php echo $user_count === 1 ? __('user', 'ez-admin-menu') : __('users', 'ez-admin-menu'); ?>
                                </span>
                            </td>
                            <td style="color: #6b7280; font-size: 13px;">
                                <?php echo esc_html($cap_display); ?>
                            </td>
                            <td>
                                <button class="button button-small" onclick="cmpShowMessage('Role editing coming soon!', 'info')">
                                    <?php _e('Edit', 'ez-admin-menu'); ?>
                                </button>
                                <?php if (!in_array($role_slug, ['administrator', 'editor', 'author', 'contributor', 'subscriber'])): ?>
                                    <button class="button button-small" onclick="cmpConfirm('Are you sure you want to delete this custom role?', function() { cmpShowMessage('Role deletion coming soon!', 'info'); })">
                                        <?php _e('Delete', 'ez-admin-menu'); ?>
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }
}

