<?php

class EventsAndVenuesLoader extends MvcPluginLoader {

	var $db_version = '1.0';
	var $tables = array();

	function activate() {
	
		// This call needs to be made to activate this app within WP MVC
		
		$this->activate_app(__FILE__);
		
		// Perform any databases modifications related to plugin activation here, if necessary

		require_once ABSPATH.'wp-admin/includes/upgrade.php';
	
		add_option('events_and_venues_db_version', $this->db_version);

		// Use dbDelta() to create the tables for the app here

		global $wpdb;

        $this->tables = array(
            'events' => $wpdb->prefix.'events',
            'venues' => $wpdb->prefix.'venues');

        $sql = '
            CREATE TABLE '.$this->tables['events'].' (
                id int(11) NOT NULL auto_increment,
                name varchar(200) NOT NULL,
                venue_id int(11) default NULL,
                date date default NULL,
                time time default NULL,
                description text,
                url varchar(200) default NULL,
                PRIMARY KEY  (id),
                KEY venue_id (venue_id)
            )';
        dbDelta($sql);

        $sql = '
            CREATE TABLE '.$this->tables['venues'].' (
                id int(11) NOT NULL auto_increment,
                name varchar(200) NOT NULL,
                url varchar(255) default NULL,
                description text,
                address1 varchar(255) default NULL,
                address2 varchar(255) default NULL,
                city varchar(100) default NULL,
                state varchar(100) default NULL,
                zip varchar(20) default NULL,
                PRIMARY KEY  (id)
            )';
        dbDelta($sql);
	}

	function deactivate() {
	
		// This call needs to be made to deactivate this app within WP MVC
		
		$this->deactivate_app(__FILE__);
		
		// Perform any databases modifications related to plugin deactivation here, if necessary
	
	}

}

?>
