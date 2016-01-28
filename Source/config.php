<?php

/*
 * Copyright (c) delight.im <info@delight.im>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

// BEGIN SETTINGS

set_time_limit(0);
error_reporting(E_ALL);
ini_set('display_errors', 'stdout');
mb_internal_encoding('utf-8');

header('Content-type: text/plain; charset=utf-8');

// END SETTINGS

// BEGIN CONSTANTS

define('SHAPEFILE_PARSER_EXECUTABLE', 'Java-Shapefile-Parser.jar');
define('JSON_OUTPUT_FLAGS', JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
define('INPUT_FOLDER', 'shapefiles/');
define('TEMP_FOLDER', 'csv/');
define('OUTPUT_FOLDER', 'json/');
define('EXPORT_FOLDER', '../Distribution/');

// END CONSTANTS

// BEGIN FILE DEFINITIONS

$files = array(
	'cities' => array(
		'inputShapefile' => INPUT_FOLDER.'ne_10m_populated_places/ne_10m_populated_places.shp',
		'renameColumns' => array(
			'coordinates_wkt',
			null,
			null,
			null,
			null,
			'name',
			null,
			'name_alt',
			null,
			'name_ascii',
			'is_capital',
			null,
			null,
			'is_world_city',
			'is_mega_city',
			null,
			null,
			'country',
			'country_iso_alpha3',
			'region',
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			'time_zone',
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null
		)
	),
	'airports' => array(
		'inputShapefile' => INPUT_FOLDER.'ne_10m_airports/ne_10m_airports.shp',
		'renameColumns' => array(
			'coordinates_wkt',
			'name_international',
			null,
			null,
			null,
			null,
			null,
			'name_local',
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			'language',
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			'name_short',
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			'continent',
			'country',
			null,
			'city',
			null,
			'icao',
			'iata',
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			'category',
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null
		)
	),
	'time_zones' => array(
		'inputShapefile' => INPUT_FOLDER.'ne_10m_time_zones/ne_10m_time_zones.shp',
		'renameColumns' => array(
			'coordinates_wkt',
			null,
			null,
			null,
			'offset',
			null,
			null,
			null,
			null,
			'name',
			null,
			null,
			'places',
			'dst_places',
			'name_alt',
			null
		)
	),
	'ports' => array(
		'inputShapefile' => INPUT_FOLDER.'ne_10m_ports/ne_10m_ports.shp',
		'renameColumns' => array(
			'coordinates_wkt',
			null,
			null,
			'name',
			null,
			null
		)
	),
	'lakes' => array(
		'inputShapefile' => INPUT_FOLDER.'ne_10m_lakes/ne_10m_lakes.shp',
		'renameColumns' => array(
			'coordinates_wkt',
			'type',
			null,
			'name',
			null,
			'name_alt',
			null,
			null,
			'dam',
			null,
			null
		)
	),
	'roads' => array(
		'inputShapefile' => INPUT_FOLDER.'ne_10m_roads/ne_10m_roads.shp',
		'renameColumns' => array(
			'coordinates_wkt',
			null,
			null,
			'type',
			'country',
			null,
			null,
			'name',
			null,
			null,
			null,
			null,
			'length_km',
			'has_toll',
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			'continent',
			'is_express',
			null
		)
	),
	'railroads' => array(
		'inputShapefile' => INPUT_FOLDER.'ne_10m_railroads/ne_10m_railroads.shp',
		'renameColumns' => array(
			'coordinates_wkt',
			null,
			'multi_track',
			'electric',
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			'continent'
		)
	),
	'countries' => array(
		'inputShapefile' => INPUT_FOLDER.'ne_10m_admin_0_countries/ne_10m_admin_0_countries.shp',
		'renameColumns' => array(
			'coordinates_wkt',
			null,
			null,
			null,
			'sovereign',
			null,
			null,
			null,
			null,
			'name',
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			'formal',
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			'economy_level',
			'income_level',
			null,
			null,
			'iso_alpha2',
			'iso_alpha3',
			'iso_numeric3',
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			'continent',
			null,
			'subregion',
			null,
			null,
			null,
			null,
			null,
			null
		)
	),
	'regions' => array(
		'inputShapefile' => INPUT_FOLDER.'ne_10m_admin_1_states_provinces/ne_10m_admin_1_states_provinces.shp',
		'renameColumns' => array(
			'coordinates_wkt',
			null,
			null,
			null,
			null,
			null,
			null,
			'country_iso_alpha2',
			null,
			'name',
			'name_alt',
			null,
			'type_local',
			'type',
			null,
			'hasc_code',
			null,
			null,
			'region',
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			'postal',
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			'country_iso_alpha3',
			null,
			'country',
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			'region_sub',
			null,
			null,
			null,
			null,
			null
		)
	)
);

// END FILE DEFINITIONS

// BEGIN SCHEMA DEFINITIONS

$schemas = array(
	'mysql' => array(
		'cities' => array(
			'columns' => array(
				'int(10) NOT NULL DEFAULT \'0\'',
				null,
				'varchar(255) DEFAULT NULL',
				'varchar(255) DEFAULT NULL',
				'varchar(255) DEFAULT NULL',
				'tinyint(1) DEFAULT NULL',
				'tinyint(1) DEFAULT NULL',
				'tinyint(1) DEFAULT NULL',
				'varchar(255) DEFAULT NULL',
				'varchar(3) DEFAULT NULL',
				'varchar(255) DEFAULT NULL',
				'varchar(255) DEFAULT NULL'
			),
			'spatial' => array(
				'reference' => 'coordinates_wkt',
				'type' => 'point NOT NULL'
			)
		),
		'airports' => array(
			'columns' => array(
				'int(10) NOT NULL DEFAULT \'0\'',
				null,
				'varchar(255) DEFAULT NULL',
				'varchar(255) DEFAULT NULL',
				'varchar(3) DEFAULT NULL',
				'varchar(255) DEFAULT NULL',
				'varchar(2) DEFAULT NULL',
				'varchar(2) DEFAULT NULL',
				'varchar(255) DEFAULT NULL',
				'varchar(4) DEFAULT NULL',
				'varchar(3) DEFAULT NULL',
				'varchar(255) DEFAULT NULL'
			),
			'spatial' => array(
				'reference' => 'coordinates_wkt',
				'type' => 'point NOT NULL'
			)
		),
		'time_zones' => array(
			'columns' => array(
				'int(10) NOT NULL DEFAULT \'0\'',
				null,
				'decimal(5,2) DEFAULT NULL',
				'varchar(9) DEFAULT NULL',
				'varchar(255) DEFAULT NULL',
				'varchar(255) DEFAULT NULL',
				'varchar(255) DEFAULT NULL'
			),
			'spatial' => array(
				'reference' => 'coordinates_wkt',
				'type' => 'multipolygon NOT NULL'
			)
		),
		'ports' => array(
			'columns' => array(
				'int(10) NOT NULL DEFAULT \'0\'',
				null,
				'varchar(255) DEFAULT NULL'
			),
			'spatial' => array(
				'reference' => 'coordinates_wkt',
				'type' => 'point NOT NULL'
			)
		),
		'lakes' => array(
			'columns' => array(
				'int(10) NOT NULL DEFAULT \'0\'',
				null,
				'varchar(255) DEFAULT NULL',
				'varchar(255) DEFAULT NULL',
				'varchar(255) DEFAULT NULL',
				'varchar(255) DEFAULT NULL'
			),
			'spatial' => array(
				'reference' => 'coordinates_wkt',
				'type' => 'multipolygon NOT NULL'
			)
		),
		'roads' => array(
			'columns' => array(
				'int(10) NOT NULL DEFAULT \'0\'',
				null,
				'varchar(255) DEFAULT NULL',
				'varchar(3) DEFAULT NULL',
				'varchar(255) DEFAULT NULL',
				'smallint(4) DEFAULT NULL',
				'tinyint(1) DEFAULT NULL',
				'varchar(255) DEFAULT NULL',
				'tinyint(1) DEFAULT NULL'
			),
			'spatial' => array(
				'reference' => 'coordinates_wkt',
				'type' => 'multilinestring NOT NULL'
			)
		),
		'railroads' => array(
			'columns' => array(
				'int(10) NOT NULL DEFAULT \'0\'',
				null,
				'tinyint(1) DEFAULT NULL',
				'tinyint(1) DEFAULT NULL',
				'varchar(255) DEFAULT NULL'
			),
			'spatial' => array(
				'reference' => 'coordinates_wkt',
				'type' => 'multilinestring NOT NULL'
			)
		),
		'countries' => array(
			'columns' => array(
				'int(10) NOT NULL DEFAULT \'0\'',
				null,
				'varchar(255) DEFAULT NULL',
				'varchar(255) DEFAULT NULL',
				'varchar(255) DEFAULT NULL',
				'varchar(255) DEFAULT NULL',
				'varchar(255) DEFAULT NULL',
				'varchar(3) DEFAULT NULL',
				'varchar(3) DEFAULT NULL',
				'smallint(3) DEFAULT NULL',
				'varchar(255) DEFAULT NULL',
				'varchar(255) DEFAULT NULL'
			),
			'spatial' => array(
				'reference' => 'coordinates_wkt',
				'type' => 'multipolygon NOT NULL'
			)
		),
		'regions' => array(
			'columns' => array(
				'int(10) NOT NULL DEFAULT \'0\'',
				null,
				'varchar(2) DEFAULT NULL',
				'varchar(255) DEFAULT NULL',
				'varchar(255) DEFAULT NULL',
				'varchar(255) DEFAULT NULL',
				'varchar(255) DEFAULT NULL',
				'varchar(8) DEFAULT NULL',
				'varchar(255) DEFAULT NULL',
				'varchar(255) DEFAULT NULL',
				'varchar(3) DEFAULT NULL',
				'varchar(255) DEFAULT NULL',
				'varchar(255) DEFAULT NULL'
			),
			'spatial' => array(
				'reference' => 'coordinates_wkt',
				'type' => 'multipolygon NOT NULL'
			)
		)
	)
);

// END SCHEMA DEFINITIONS
