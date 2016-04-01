<?php
/**
 * Plugin Name: WP Document Builder
 * Plugin URI: http://jameslawrie.net
 * Description: This plugin allows you to create content blocks then then drag & drop into a document.
 * Author: James Lawrie
 * Author URI: http://jameslawrie.net
 * Version: 0.0.1
 * License: GPLv2
 */

//Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once ( plugin_dir_path(__FILE__) . 'wp-doc-database.php' );
require_once ( plugin_dir_path(__FILE__) . 'wp-doc-post-type.php' );
require_once ( plugin_dir_path(__FILE__) . 'wp-doc-settings.php' );
require_once ( plugin_dir_path(__FILE__) . 'wp-doc-fields.php' );
require_once ( plugin_dir_path(__FILE__) . 'wp-doc-shortcode.php' );



function doc_admin_enqueue_scripts() {
	global $pagenow, $typenow;

	if ( $typenow == 'doc_builder') {

		wp_enqueue_style( 'doc-admin-css', plugins_url( 'css/admin-docs.css', __FILE__ ) );
		wp_enqueue_style( 'doc-jquery-ui-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' );

		wp_enqueue_script( 'doc-admin-js', plugins_url( 'js/admin-docs.js', __FILE__ ), array( 'jquery','jquery-ui-droppable','jquery-ui-draggable', 'jquery-ui-sortable' ), '20150626', true ); 

	}

	if ( $pagenow =='edit.php' && $typenow == 'doc_builder') {

		wp_enqueue_script( 'doc-build-js', plugins_url( 'js/doc-builder.js', __FILE__ ), array( 'jquery', 'jquery-ui-sortable' ), '20150626', true );
		wp_localize_script( 'doc-build-js', 'WP_DOC_BUILDER', array(
			'security' => wp_create_nonce( 'wp-docs-order' ),
			'success' => __( 'Docs sort order has been saved.' ),
			'failure' => __( 'There was an error saving the sort order, or you do not have proper permissions.' )
		) );

	}

}
add_action( 'admin_enqueue_scripts', 'doc_admin_enqueue_scripts' );





