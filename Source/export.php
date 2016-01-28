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

require __DIR__.'/config.php';

// BEGIN EXPORT PROCESS

echo "# Export log\n\n";

$jsonFiles = glob(OUTPUT_FOLDER.'*');

foreach ($jsonFiles as $jsonFile) {
	$jsonFileName = pathinfo($jsonFile, PATHINFO_FILENAME);
	echo " * ".$jsonFileName."\n";

	$jsonIn = file_get_contents($jsonFile);
	$rows = json_decode($jsonIn, true, 512, JSON_BIGINT_AS_STRING);

	// export as JSON
	$jsonOut = json_encode($rows, JSON_OUTPUT_FLAGS);
	$jsonOut .= "\n";
	file_put_contents(EXPORT_FOLDER.'JSON/'.$jsonFileName.'.json', $jsonOut);
	echo "   * JSON exported\n";

	// export as CSV
	$csv = createCsv($rows);
	file_put_contents(EXPORT_FOLDER.'CSV/'.$jsonFileName.'.csv', $csv);
	echo "   * CSV exported\n";

	// export as MySQL
	$mysql = createMysql($jsonFileName, $schemas['mysql'][$jsonFileName], $rows);
	$mysql .= "\n";
	file_put_contents(EXPORT_FOLDER.'MySQL/'.$jsonFileName.'.sql', $mysql);
	echo "   * MySQL exported\n";
}

// END EXPORT PROCESS

function createCsv($data) {
	$firstElement = current($data);
	$columnNames = array_map('escapeForCsv', array_keys($firstElement));

	$out = implode(',', $columnNames)."\n";

	foreach ($data as $columns) {
		$columns = array_map('escapeForCsv', $columns);
		$out .= implode(',', $columns)."\n";
	}

	return $out;
}

function escapeForCsv($entry) {
	if (is_string($entry) && $entry !== '') {
		return '"'.str_replace('"', '""', $entry).'"';
	}
	else {
		return $entry;
	}
}

function createMysql($tableName, $schema, $data) {
	$firstElement = current($data);
	$columnNames = array_keys($firstElement);

	$out = "CREATE TABLE IF NOT EXISTS `".$tableName."` (\n";
	$columnIndex = 0;
	foreach ($columnNames as $columnName) {
		if ($columnName === $schema['spatial']['reference']) {
			$out .= "  `coordinates` ".$schema['spatial']['type'].",\n";
		}
		else {
			$out .= "  `".$columnName."` ".$schema['columns'][$columnIndex].",\n";
		}

		$columnIndex++;
	}
	$out .= "  PRIMARY KEY (id),\n";
	$out .= "  SPATIAL KEY coordinates (coordinates)\n";
	$out .= ") ENGINE=MyISAM DEFAULT CHARSET=utf8;\n";
	$out .= "\n";

	$rowsPerClause = 200;

	foreach ($data as $rowIndex => $columns) {
		if ($rowIndex % $rowsPerClause === 0) {
			if ($rowIndex > 0) {
				$out .= ";\n";
			}
			$out .= "INSERT INTO `".$tableName."` VALUES\n";
		}
		else {
			$out .= ",\n";
		}
		$out .= "(";

		$columnIndex = 0;
		foreach ($columns as $columnName => $cell) {
			if ($columnIndex > 0) {
				$out .= ", ";
			}

			if ($columnName === $schema['spatial']['reference']) {
				$out .= 'ST_GeomFromText(\''.$columns[$schema['spatial']['reference']].'\')';
			}
			else {
				$out .= escapeForMysql($cell);
			}

			$columnIndex++;
		}
		$out .= ")";
	}

	$out .= ";";

	return $out;
}

function escapeForMysql($entry) {
	if (is_string($entry)) {
		return '\''.strtr($entry, array(
			"'" => '\\\'',
			'"' => '\\"',
			"\n" => '\\n',
			"\r" => '\\r',
			"\t" => '\\t',
			'\\' => '\\\\'
		)).'\'';
	}
	elseif (is_int($entry) || is_float($entry)) {
		return $entry;
	}
	else {
		throw new Exception('Unexpected data type: '.$entry);
	}
}
