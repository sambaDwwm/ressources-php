<?php
/**
* @project ApPHP Calendar
* @copyright (c) 2014 ApPHP
* @author ApPHP <info@apphp.com>
* @license http://www.gnu.org/licenses/
*/

// TABLES DEFINITION
define('CALENDAR_TABLE', DB_PREFIX.'calendar');      
define('EVENTS_TABLE', DB_PREFIX.'events');      
define('EVENTS_CATEGORIES_TABLE', DB_PREFIX.'events_categories');
define('EVENTS_LOCATIONS_TABLE', DB_PREFIX.'events_locations');
define('EVENTS_PARTICIPANTS_TABLE', DB_PREFIX.'events_participants');
define('PARTICIPANTS_TABLE', DB_PREFIX.'participants');

// DATABASE TYPE
define('DB_TYPE', 'MySQLi'); /* possible values: PDO, MySQLi */

// RETURN TYPES FOR DATABASE_QUERY FUNCTION
define('CAL_ALL_ROWS', 0);
define('CAL_FIRST_ROW_ONLY', 1);
define('CAL_DATA_ONLY', 0);
define('CAL_ROWS_ONLY', 1);
define('CAL_DATA_AND_ROWS', 2);
define('CAL_FIELDS_ONLY', 3);
define('CAL_FETCH_ASSOC', 'mysqli_fetch_assoc');
define('CAL_FETCH_ARRAY', 'mysqli_fetch_array');

