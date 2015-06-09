<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This file keeps track of upgrades to the newmodule module
 *
 * Sometimes, changes between versions involve alterations to database
 * structures and other major things that may break installations. The upgrade
 * function in this file will attempt to perform all the necessary actions to
 * upgrade your older installation to the current version. If there's something
 * it cannot do itself, it will tell you what you need to do.  The commands in
 * here will all be database-neutral, using the functions defined in DLL libraries.
 *
 * @package    local
 * @subpackage bookingrooms
 * @copyright  2015 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Execute newmodule upgrade from the given old version
 *
 * @param int $oldversion
 * @return bool
*/
function xmldb_bookingrooms_upgrade($oldversion) {
	global $DB;

	$dbman = $DB->get_manager(); // Loads ddl manager and xmldb classes.
	
	if ($oldversion < 2015060800) {
		
		// Define table bookingrooms_campus to be created
		$table = new xmldb_table('bookingrooms_campus');
		
		// Adding fields to table reservasalas_sedes
		$table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
		$table->add_field('name', XMLDB_TYPE_CHAR, '45', null, null, null, null);
		
		// Adding keys to table reservasalas_sedes
		$table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
		
		// Conditionally launch create table for bookingrooms_campus
		if (!$dbman->table_exists($table)) {
			$dbman->create_table($table);
		}
		
		// Define table bookingrooms_buildings to be created
		$table = new xmldb_table('bookingrooms_buildings');
		
		// Adding fields to table reservasalas_edificios
		$table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
		$table->add_field('name', XMLDB_TYPE_CHAR, '45', null, null, null, null);
		$table->add_field('campusid', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
		
		// Adding keys to table bookingrooms_buildings
		$table->add_key('id', XMLDB_KEY_PRIMARY, array('id'));
		$table->add_key('campusid', XMLDB_KEY_FOREIGN, array('campusid'), 'bookingrooms_campus', array('id'));
		
		// Conditionally launch create table for bookingrooms_buildings
		if (!$dbman->table_exists($table)) {
			$dbman->create_table($table);
		}
		
		// Define table bookingrooms_rooms to be created
		$table = new xmldb_table('bookingrooms_rooms');

		// Adding fields to table reservasalas_salas
		$table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
		$table->add_field('name', XMLDB_TYPE_CHAR, '45', null, null, null, null);
		$table->add_field('pc_name', XMLDB_TYPE_CHAR, '45', null, null, null, null);
		$table->add_field('buildingsid', XMLDB_TYPE_INTEGER, '10', null, null, null, null);

		// Adding keys to table bookingrooms_rooms
		$table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
		$table->add_key('buildingsid', XMLDB_KEY_FOREIGN, array('buildingsid'), 'bookingrooms_buildings', array('id'));

		// Conditionally launch create table for bookingrooms_rooms
		if (!$dbman->table_exists($table)) {
			$dbman->create_table($table);
		}
		
		
		// Define table bookingrooms_periods to be created.
		$table = new xmldb_table('bookingrooms_periods');
		
		// Adding fields to table reservasalas_modulos.
		$table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
		$table->add_field('period_name', XMLDB_TYPE_CHAR, '50', null, XMLDB_NOTNULL, null, null);
		$table->add_field('start_time', XMLDB_TYPE_CHAR, '50', null, XMLDB_NOTNULL, null, null);
		$table->add_field('finish_time', XMLDB_TYPE_CHAR, '50', null, XMLDB_NOTNULL, null, null);
		$table->add_field('bluildingsid', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL, null, null);
		$table->add_field('type', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
		$table->add_field('capacity', XMLDB_TYPE_INTEGER, '20', null, null, null, null);
		
		// Adding keys to table bookingrooms_periods.
		$table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
		$table->add_key('bluildingsid', XMLDB_KEY_FOREIGN, array('bluildingsid'), 'bookingrooms_buildings', array('id'));
		
		// Conditionally launch create table for bookingrooms_periods.
		if (!$dbman->table_exists($table)) {
			$dbman->create_table($table);
		}
		
		// Define table bookingrooms_reservations to be created
		$table = new xmldb_table('bookingrooms_reservations');
		
		// Adding fields to table reservasalas_reservas
		$table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
		$table->add_field('reservations_date', XMLDB_TYPE_CHAR, '20', null, null, null, null);
		$table->add_field('period', XMLDB_TYPE_INTEGER, '2', null, null, null, null);
		$table->add_field('confirmed', XMLDB_TYPE_BINARY, null, null, null, null, null);
		$table->add_field('active', XMLDB_TYPE_BINARY, null, null, null, null, null);
		$table->add_field('studentid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
		$table->add_field('roomsid', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
		$table->add_field('student_comment', XMLDB_TYPE_TEXT, null, null, null, null, null);
		$table->add_field('admin_comment', XMLDB_TYPE_TEXT, null, null, null, null, null);
		$table->add_field('ip', XMLDB_TYPE_CHAR, '50', null, null, null, null);
		$table->add_field('creation_date', XMLDB_TYPE_CHAR, '20', null, null, null, null);
		$table->add_field('nombre_evento', XMLDB_TYPE_TEXT, null, null, null, null, null);
		$table->add_field('asistentes', XMLDB_TYPE_INTEGER, '20', null, null, null, null);
		
		// Adding keys to table bookingrooms_reservations
		$table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
		$table->add_key('roomsid', XMLDB_KEY_FOREIGN, array('roomsid'), 'bookingrooms_rooms', array('id'));
		
		// Conditionally launch create table for bookingrooms_reservations
		if (!$dbman->table_exists($table)) {
			$dbman->create_table($table);
		}
		
		// Define table bookingrooms_blocked to be created
		$table = new xmldb_table('bookingrooms_blocked');
		
		// Adding fields to table reservasalas_bloqueados
		$table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
		$table->add_field('blocked_date', XMLDB_TYPE_CHAR, '20', null, null, null, null);
		$table->add_field('reservation_date', XMLDB_TYPE_CHAR, '20', null, null, null, null);
		$table->add_field('status', XMLDB_TYPE_BINARY, null, null, null, null, null);
		$table->add_field('comment', XMLDB_TYPE_CHAR, '150', null, null, null, null);
		$table->add_field('studentid', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
		
		// Adding keys to table bookingrooms_blocked
		$table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
		
		// Conditionally launch create table for bookingrooms_blocked
		if (!$dbman->table_exists($table)) {
			$dbman->create_table($table);
		}
		
		// Define table bookingrooms_otherreservations to be created.
		$table = new xmldb_table('bookingrooms_otherreservations');
		
		// Adding fields to table bookingrooms_otherreservations.
		$table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
		$table->add_field('reservation_date', XMLDB_TYPE_CHAR, '50', null, XMLDB_NOTNULL, null, null);
		$table->add_field('creation_date', XMLDB_TYPE_CHAR, '50', null, XMLDB_NOTNULL, null, null);
		$table->add_field('period', XMLDB_TYPE_CHAR, '50', null, XMLDB_NOTNULL, null, null);
		$table->add_field('userid', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL, null, null);
		$table->add_field('user_comment', XMLDB_TYPE_INTEGER, '20', null, null, null, null);
		$table->add_field('event_name', XMLDB_TYPE_CHAR, '50', null, null, null, 'No name');
		$table->add_field('event_attendees', XMLDB_TYPE_INTEGER, '20', null, null, null, null);
		$table->add_field('event_comment', XMLDB_TYPE_TEXT, null, null, null, null, null);
		$table->add_field('ip', XMLDB_TYPE_INTEGER, '20', null, null, null, null);
		$table->add_field('roomsid', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL, null, null);
		
		// Adding keys to table reservasalas_otrasreservas.
		$table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
		$table->add_key('roomsid', XMLDB_KEY_FOREIGN, array('roomsid'), 'bookingrooms_rooms', array('id'));
		
		// Conditionally launch create table for bookingrooms_otherreservations.
		if (!$dbman->table_exists($table)) {
			$dbman->create_table($table);
		}
		
		// Define table bookingrooms_resoursesrooms to be created.
		$table = new xmldb_table('bookingrooms_resoursesrooms');
		
		// Adding fields to table reservasalas_salarecursos.
		$table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
		$table->add_field('roomsid', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL, null, null);
		$table->add_field('resoursesid', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL, null, null);
		
		// Adding keys to table bookingrooms_resoursesrooms.
		$table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
		$table->add_key('resoursesid', XMLDB_KEY_FOREIGN, array('resoursesid'), 'bookingrooms_resourses', array('id'));
		$table->add_key('roomsid', XMLDB_KEY_FOREIGN, array('roomsid'), 'bookingrooms_rooms', array('id'));
		
		// Conditionally launch create table for bookingrooms_resoursesrooms.
		if (!$dbman->table_exists($table)) {
			$dbman->create_table($table);
		}
		
		// Define table bookingrooms_resourses to be created.
		$table = new xmldb_table('bookingrooms_resourses');
		
		// Adding fields to table bookingrooms_resourses.
		$table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
		$table->add_field('name', XMLDB_TYPE_CHAR, '50', null, XMLDB_NOTNULL, null, null);
		
		// Adding keys to table bookingrooms_resourses.
		$table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
		
		// Conditionally launch create table for bookingrooms_resourses.
		if (!$dbman->table_exists($table)) {
			$dbman->create_table($table);
		}
		
		upgrade_plugin_savepoint(true, 2015060800, 'local', 'bookingrooms');
		
		
	}
	return true;
}
