<?php
/**
 * Meta box functionality for Ez Admin Menu.
 *
 * Handles meta boxes for menu items (link settings) and boards (sections/items).
 *
 * @package Ez_Admin_Menu
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register meta boxes for menu items and boards.
 */
function EAM_register_meta_boxes() {
	// Menu Item meta box for link settings.
	add_meta_box(
		'EAM_menu_item_link',
		__( 'Menu Item Link Settings', 'ez-admin-menu' ),
		'EAM_render_menu_item_link_meta_box',
		'EAM_menu_item',
		'normal',
		'high'
	);

	// Board meta box for sections and items.
	add_meta_box(
		'EAM_board_sections',
		__( 'Board Sections & Items', 'ez-admin-menu' ),
		'EAM_render_board_sections_meta_box',
		'EAM_board',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'EAM_register_meta_boxes' );

/**
 * Render menu item link meta box.
 *
 * @param WP_Post $post Current post object.
 */
function EAM_render_menu_item_link_meta_box( $post ) {
	wp_nonce_field( 'EAM_menu_item_meta', 'EAM_menu_item_nonce' );

	$link_type = get_post_meta( $post->ID, '_EAM_link_type', true );
	$link_url  = get_post_meta( $post->ID, '_EAM_link_url', true );
	$link_page = get_post_meta( $post->ID, '_EAM_link_page', true );
	?>
	<table class="form-table">
		<tr>
			<th><label for="EAM_link_type"><?php esc_html_e( 'Link Type', 'ez-admin-menu' ); ?></label></th>
			<td>
				<select name="EAM_link_type" id="EAM_link_type" class="regular-text">
					<option value="none" <?php selected( $link_type, 'none' ); ?>><?php esc_html_e( 'No Link', 'ez-admin-menu' ); ?></option>
					<option value="page" <?php selected( $link_type, 'page' ); ?>><?php esc_html_e( 'WordPress Page', 'ez-admin-menu' ); ?></option>
					<option value="url" <?php selected( $link_type, 'url' ); ?>><?php esc_html_e( 'Custom URL', 'ez-admin-menu' ); ?></option>
				</select>
			</td>
		</tr>
		<tr class="cmp-link-page-row" style="<?php echo ( 'page' !== $link_type ) ? 'display:none;' : ''; ?>">
			<th><label for="EAM_link_page"><?php esc_html_e( 'Select Page', 'ez-admin-menu' ); ?></label></th>
			<td>
				<?php
				wp_dropdown_pages(
					array(
						'name'             => 'EAM_link_page',
						'id'               => 'EAM_link_page',
						'selected'         => $link_page,
						'show_option_none' => __( '— Select —', 'ez-admin-menu' ),
					)
				);
				?>
			</td>
		</tr>
		<tr class="cmp-link-url-row" style="<?php echo ( 'url' !== $link_type ) ? 'display:none;' : ''; ?>">
			<th><label for="EAM_link_url"><?php esc_html_e( 'Custom URL', 'ez-admin-menu' ); ?></label></th>
			<td>
				<input type="url" name="EAM_link_url" id="EAM_link_url" value="<?php echo esc_attr( $link_url ); ?>" class="regular-text" placeholder="https://">
			</td>
		</tr>
	</table>
	<script>
	jQuery(function($) {
		$('#EAM_link_type').on('change', function() {
			var val = $(this).val();
			$('.cmp-link-page-row, .cmp-link-url-row').hide();
			if (val === 'page') {
				$('.cmp-link-page-row').show();
			} else if (val === 'url') {
				$('.cmp-link-url-row').show();
			}
		});
	});
	</script>
	<?php
}

/**
 * Render board sections meta box.
 *
 * @param WP_Post $post Current post object.
 */
function EAM_render_board_sections_meta_box( $post ) {
	wp_nonce_field( 'EAM_board_meta', 'EAM_board_nonce' );

	$sections = get_post_meta( $post->ID, '_EAM_board_sections', true );
	if ( ! is_array( $sections ) ) {
		$sections = array();
	}
	?>
	<div id="cmp-board-sections">
		<p><?php esc_html_e( 'Define sections and their menu items. Each section will be rendered as a group in the admin menu.', 'ez-admin-menu' ); ?></p>
		<div id="cmp-sections-list">
			<?php
			if ( empty( $sections ) ) {
				EAM_render_section_row( 0, array( 'title' => '', 'items' => array() ) );
			} else {
				foreach ( $sections as $index => $section ) {
					EAM_render_section_row( $index, $section );
				}
			}
			?>
		</div>
		<button type="button" class="button" id="cmp-add-section"><?php esc_html_e( 'Add Section', 'ez-admin-menu' ); ?></button>
	</div>
	<script>
	jQuery(function($) {
		var sectionIndex = <?php echo count( $sections ); ?>;

		$('#cmp-add-section').on('click', function() {
			var html = <?php echo wp_json_encode( EAM_get_section_row_html( 'INDEX_PLACEHOLDER', array( 'title' => '', 'items' => array() ) ) ); ?>;
			html = html.replace(/INDEX_PLACEHOLDER/g, sectionIndex);
			$('#cmp-sections-list').append(html);
			sectionIndex++;
		});

		$(document).on('click', '.cmp-remove-section', function() {
			$(this).closest('.cmp-section-row').remove();
		});

		$(document).on('click', '.cmp-add-item', function() {
			var $section = $(this).closest('.cmp-section-row');
			var sectionIdx = $section.data('index');
			var itemHtml = '<div class="cmp-item-row" style="margin-left:20px;margin-bottom:5px;">' +
				'<input type="text" name="EAM_sections[' + sectionIdx + '][items][]" placeholder="<?php esc_attr_e( 'Item name', 'ez-admin-menu' ); ?>" class="regular-text" style="width:70%;">' +
				' <button type="button" class="button cmp-remove-item"><?php esc_html_e( 'Remove', 'ez-admin-menu' ); ?></button>' +
				'</div>';
			$section.find('.cmp-items-list').append(itemHtml);
		});

		$(document).on('click', '.cmp-remove-item', function() {
			$(this).closest('.cmp-item-row').remove();
		});
	});
	</script>
	<?php
}

/**
 * Render a single section row for the board meta box.
 *
 * @param int   $index   Section index.
 * @param array $section Section data.
 */
function EAM_render_section_row( $index, $section ) {
	?>
	<div class="cmp-section-row" data-index="<?php echo esc_attr( $index ); ?>" style="border:1px solid #ddd;padding:10px;margin-bottom:10px;">
		<p>
			<label><strong><?php esc_html_e( 'Section Title:', 'ez-admin-menu' ); ?></strong></label><br>
			<input type="text" name="EAM_sections[<?php echo esc_attr( $index ); ?>][title]" value="<?php echo esc_attr( $section['title'] ); ?>" class="regular-text" placeholder="<?php esc_attr_e( 'e.g., Espresso', 'ez-admin-menu' ); ?>">
			<button type="button" class="button cmp-remove-section" style="float:right;"><?php esc_html_e( 'Remove Section', 'ez-admin-menu' ); ?></button>
		</p>
		<div class="cmp-items-list">
			<?php
			if ( ! empty( $section['items'] ) && is_array( $section['items'] ) ) {
				foreach ( $section['items'] as $item ) {
					?>
					<div class="cmp-item-row" style="margin-left:20px;margin-bottom:5px;">
						<input type="text" name="EAM_sections[<?php echo esc_attr( $index ); ?>][items][]" value="<?php echo esc_attr( $item ); ?>" placeholder="<?php esc_attr_e( 'Item name', 'ez-admin-menu' ); ?>" class="regular-text" style="width:70%;">
						<button type="button" class="button cmp-remove-item"><?php esc_html_e( 'Remove', 'ez-admin-menu' ); ?></button>
					</div>
					<?php
				}
			}
			?>
		</div>
		<button type="button" class="button cmp-add-item"><?php esc_html_e( 'Add Item', 'ez-admin-menu' ); ?></button>
	</div>
	<?php
}

/**
 * Get section row HTML for JS template.
 *
 * @param string $index   Section index placeholder.
 * @param array  $section Section data.
 *
 * @return string
 */
function EAM_get_section_row_html( $index, $section ) {
	ob_start();
	EAM_render_section_row( $index, $section );
	return ob_get_clean();
}

/**
 * Save meta box data for menu items and boards.
 *
 * @param int $post_id Post ID.
 */
function EAM_save_meta_boxes( $post_id ) {
	// Menu Item meta.
	if ( isset( $_POST['EAM_menu_item_nonce'] ) && wp_verify_nonce( $_POST['EAM_menu_item_nonce'], 'EAM_menu_item_meta' ) ) {
		if ( isset( $_POST['EAM_link_type'] ) ) {
			update_post_meta( $post_id, '_EAM_link_type', sanitize_text_field( $_POST['EAM_link_type'] ) );
		}
		if ( isset( $_POST['EAM_link_url'] ) ) {
			update_post_meta( $post_id, '_EAM_link_url', esc_url_raw( $_POST['EAM_link_url'] ) );
		}
		if ( isset( $_POST['EAM_link_page'] ) ) {
			update_post_meta( $post_id, '_EAM_link_page', absint( $_POST['EAM_link_page'] ) );
		}
	}

	// Board meta.
	if ( isset( $_POST['EAM_board_nonce'] ) && wp_verify_nonce( $_POST['EAM_board_nonce'], 'EAM_board_meta' ) ) {
		if ( isset( $_POST['EAM_sections'] ) && is_array( $_POST['EAM_sections'] ) ) {
			$sections = array();
			foreach ( $_POST['EAM_sections'] as $section ) {
				$title = isset( $section['title'] ) ? sanitize_text_field( $section['title'] ) : '';
				$items = array();
				if ( isset( $section['items'] ) && is_array( $section['items'] ) ) {
					foreach ( $section['items'] as $item ) {
						$items[] = sanitize_text_field( $item );
					}
				}
				$sections[] = array(
					'title' => $title,
					'items' => $items,
				);
			}
			update_post_meta( $post_id, '_EAM_board_sections', $sections );
		}
	}
}
add_action( 'save_post', 'EAM_save_meta_boxes' );

