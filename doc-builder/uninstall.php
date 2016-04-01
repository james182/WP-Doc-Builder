<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

function doc_builder_delete_plugin() {
	global $wpdb;

	delete_option( 'docbuild' );

	$posts = get_posts( array(
		'numberposts' => -1,
		'post_type' => 'wpcf7_contact_form',
		'post_status' => 'any' ) );

	foreach ( $posts as $post )
		wp_delete_post( $post->ID, true );

	$table_name = $wpdb->prefix . "contact_form_7";

	$wpdb->query( "DROP TABLE IF EXISTS $table_name" );
}

doc_builder_delete_plugin();

?>