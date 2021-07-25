<?php
/*
Plugin Name:  Calendrier Rdv
Description:  Calendrier pour les prises de rendez-vous
Version:      0.1
Author:       Alice
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
*/

// Ajouter le menu d'administration de l'interface wp-admin
add_action('admin_menu','set_up_menu');

// On lui dit d'exécuter la fonction database_install lors de l'activation du plugin
register_activation_hook(__FILE__, 'database_install');

// On ajoute notre Objet
include('entity/Calendar.php');



function set_up_menu()
{
	// On définit les paramètre du menu.
	add_menu_page('Calendrier RDV', 'Calendrier', 'read', 'AliceCalendar', 'show_calendar');
}

function database_install()
{
	global $wpdb;
	// On définit la version de notre base de donnée. Ici la première version.
	global $jal_db_version;
	$jal_db_version = '1.0';

	// On définit le nom de la table (en utilisant le préfix de wordpress)
	$table = $wpdb->prefix . 'alice_calendar';

	// On définit le charset pour la base de donnée
	$charset_collate = $wpdb->get_charset_collate();

	// On prépare la requête de creation de la table.

	$sql = "CREATE TABLE $table (
		timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		number_person mediumint(9) NOT NULL,
		name varchar(255) NOT NULL,
		date DATE NOT NULL,
		hour_start varchar(15) NOT NULL,
		hour_end varchar(15) NOT NULL,
		activity varchar(255) NOT NULL,
		location varchar(255) NOT NULL,
		PRIMARY KEY (id)
		) $charset_collate;";

	// Security access
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	// Exécution de la requête SQL
	dbDelta( $sql );

}

function show_calendar()
{
	include('views/calendar.php');
	// On ajoute le fichier calendar.js
	wp_enqueue_script( 'calendar-js', plugins_url( 'assets/js/calendar.js', __FILE__ ));
	// Ajout du fichier de langue de FullCalendarJS
	wp_enqueue_script( 'locale-fr-js', plugins_url( 'assets/js/fr.js', __FILE__ ));
}


add_action('wp_ajax_add_calendar', 'add_calendar');

function add_calendar()
{
	global $wpdb;
	Calendar::insert($_POST['param']);
	wp_die();
}

add_action('wp_ajax_get_all_event', 'get_all_event');

function get_all_event()
{
	global $wpdb;
	print_r(json_encode(Calendar::findAll()));
	wp_die();
}
