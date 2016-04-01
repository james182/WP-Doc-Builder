<?php

/**
* CREATE DATABASES
*/
global $jal_db_version;
$jal_db_version = '1.0';

function jal_install() {
	global $wpdb;
	global $jal_db_version;

	$doc_item_table_name = $wpdb->prefix . 'doc_builder_items';
	$doc_element_table_name = $wpdb->prefix . 'doc_builder_elements';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql_elements = "CREATE TABLE $doc_item_table_name (
		id int(11) unsigned NOT NULL AUTO_INCREMENT,
	  	post_id int(11) DEFAULT NULL,
	  	element_id int(11) DEFAULT NULL,
	  	title varchar(255) DEFAULT NULL,
	  	content text,
	  	created_on timestamp NULL DEFAULT NULL,
	  	created_by int(11) DEFAULT NULL,
	  	sequence int(11) DEFAULT NULL,
	  	PRIMARY KEY (id)
	) $charset_collate;";

	$sql = "CREATE TABLE $doc_element_table_name (
	  	id int(11) unsigned NOT NULL AUTO_INCREMENT,
	  	title varchar(255) DEFAULT NULL,
	  	content text,
	  	created_on timestamp NULL DEFAULT NULL,
	  	created_by int(11) DEFAULT NULL,
	  	tagged varchar(115) DEFAULT NULL,
	  	PRIMARY KEY (`id`)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql_elements );
	dbDelta( $sql );

	add_option( 'jal_db_version', $jal_db_version );
}
register_activation_hook( __FILE__, 'jal_install' );
