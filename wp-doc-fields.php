<?php

function doc_add_custom_metabox() {

	add_meta_box(
		'doc_meta',
		__( 'Document Builder' ),
		'doc_meta_callback',
		'doc_builder',
		'normal',
		'core'
	);

}
add_action( 'add_meta_boxes', 'doc_add_custom_metabox' );


function doc_meta_callback() {

	global $wpdb;

	$doc_stored_meta_id = get_the_ID();

	$db_table_dbe = 'wp_doc_builder_elements';
	$db_table_dbi = 'wp_doc_builder_items';

	$doc_available_elements = $wpdb->get_results( "SELECT * FROM $db_table_dbe" );
	$doc_items = $wpdb->get_results( "SELECT * FROM $db_table_dbi WHERE post_id = ' $doc_stored_meta_id '" );
	
	?>
	
	<div class="doc_wrapper">

		<!-- Available Document Elements -->
		<div id="doc_elements" class="doc_elements">
						
			<?php if ( count($doc_available_elements) > 0 ) : ?>
				
				<ul id="available-elements">
					<?php foreach ( $doc_available_elements as $doc_available_element ): ?>
						<li id="<?php echo esc_attr( $doc_available_element->id ); ?>">
							<div class="document_element">
								<span class="element_list_heading"><?php echo $doc_available_element->title; ?></span>
								<p><?php echo $doc_available_element->content; ?></p>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
			
			<?php else: ?>				
				<p><?php _e( 'You have no available elements to use.', 'wp-doc-builder' ); ?></p>			
			<?php endif; ?>

		</div>


		<!-- Document Zone -->
		<div id="doc_sort" class="wrap">
			<div id="icon-job-admin" class="icon32"><br /></div>
			
			<h2><?php _e( 'Arrange Your Document Element Positions', 'wp-doc-builder' ); ?><img src="<?php echo esc_url( admin_url() . '/images/loading.gif' ); ?>" id="loading-animation"></h2> 
			
			<?php if ( count($doc_items) > 0 ) : ?>
				
				<ul id="document_drop_zone" class="document_elements">
					<?php foreach ( $doc_items as $doc_item ): ?>
						<li id="<?php echo esc_attr( $doc_item->element_id ); ?>">
							<div class="document_element">
								<span class="element_list_heading"><?php echo $doc_item->title; ?></span>
								<p><?php echo $doc_item->content; ?></p>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
			
			<?php else: ?>
				
				<ul id="document_drop_zone" class="document_elements">
					<li class="placeholder">Drag &amp; Drop Elements Here!</li>
				</ul>
			
			<?php endif; ?>

			<button id="save_document" class="button button-primary button-large"><?php echo __( 'Save Document', 'doc_builder' ); ?></button>
			<p><strong>note:</strong> this should save the above elements to the wp_doc_builder_items db table.</p>
		</div>

	</div>


	<?php

}


function save_document_elements() {

	if ( ! check_ajax_referer( 'wp-job-order', 'security' ) ) {
		return wp_send_json_error( 'Invalid Nonce' );
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		return wp_send_json_error( 'You are not allow to do this.' );
	}

	$order = $_POST['order'];
	$counter = 0;
	
	$data = array('post_id' => $doc_stored_meta_id,	'title' => '1',	'content' => ''	);
	$format = array('%d','%s','%s');
	$wpdb->replace( $db_table_dbi, $data, $format );

	foreach( $order as $item_id ) {

		$post = array(
			'ID' => (int)$item_id,
			'menu_order' => $counter,
		);

		wp_update_post( $post );

		$counter++;
	}

	wp_send_json_success( 'Post Saved.' );

}
add_action( 'wp_ajax_save_document', 'save_document_elements' );



/*
function save_sort( $post_id ) {
	// Checks save status
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'doc_builder_nonce' ] ) && wp_verify_nonce( $_POST[ 'doc_builder_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';

    // Exits script depending on save status
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }

    //$wpdb->insert( $db_table_dbi, $data, $format );

    if ( isset( $_POST[ 'job_id' ] ) ) {
    	update_post_meta( $post_id, 'job_id', sanitize_text_field( $_POST[ 'job_id' ] ) );
    }
    if ( isset( $_POST[ 'date_listed' ] ) ) {
    	update_post_meta( $post_id, 'date_listed', sanitize_text_field( $_POST[ 'date_listed' ] ) );
    }
    if ( isset( $_POST[ 'application_deadline' ] ) ) {
    	update_post_meta( $post_id, 'application_deadline', sanitize_text_field( $_POST[ 'application_deadline' ] ) );
    }
    if ( isset( $_POST[ 'principle_duties' ] ) ) {
    	update_post_meta( $post_id, 'principle_duties', sanitize_text_field( $_POST[ 'principle_duties' ] ) );
    }
	if ( isset( $_POST[ 'preferred_requirements' ] ) ) {
		update_post_meta( $post_id, 'preferred_requirements', wp_kses_post( $_POST[ 'preferred_requirements' ] ) );
	}
	if ( isset( $_POST[ 'minimum_requirements' ] ) ) {
		update_post_meta( $post_id, 'minimum_requirements', wp_kses_post( $_POST[ 'minimum_requirements' ] ) );
	}
	if ( isset( $_POST[ 'relocation_assistance' ] ) ) {
		update_post_meta( $post_id, 'relocation_assistance', sanitize_text_field( $_POST[ 'relocation_assistance' ] ) );
	}
}
add_action( 'save_post', 'save_sort' );

function xdoc_save_reorder() {

	if ( ! check_ajax_referer( 'wp-job-order', 'security' ) ) {
		return wp_send_json_error( 'Invalid Nonce' );
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		return wp_send_json_error( 'You are not allow to do this.' );
	}

	$order = $_POST['order'];
	$counter = 0;

	foreach( $order as $item_id ) {

		$post = array(
			'ID' => (int)$item_id,
			'menu_order' => $counter,
		);

		wp_update_post( $post );

		$counter++;
	}

	wp_send_json_success( 'Post Saved.' );

}
add_action( 'wp_ajax_save_sort', 'xdoc_save_reorder' );
*/


