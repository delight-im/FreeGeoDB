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

// BEGIN BUILD PROCESS

echo "# Build log\n\n";

foreach ($files as $fileName => $fileProperties) {

	echo " * ".$fileName."\n";
	if (file_exists($fileName.'.csv')) {
		echo "   * CSV already exists\n";
	}
	else {
		exec('java -jar "'.SHAPEFILE_PARSER_EXECUTABLE.'" "'.$fileProperties['inputShapefile'].'" > '.TEMP_FOLDER.$fileName.'.csv');
		echo "   * CSV created\n";
	}

	if (file_exists(OUTPUT_FOLDER.$fileName.'.json')) {
		echo "   * Output file already exists\n";
	}
	else {
		$csv = array_map('utf8_encode', file(TEMP_FOLDER.$fileName.'.csv'));
		$rows = array_map('str_getcsv', $csv);

		$columnsPerRow = array_unique(array_map('count', $rows));
		if (count($columnsPerRow) !== 2 || min($columnsPerRow) > 1) {
			echo "   * Invalid row/column counts\n";
			echo "     * [".implode(', ', $columnsPerRow)."]\n";
			exit;
		}

		if (isset($fileProperties['renameColumns']) && count($fileProperties['renameColumns']) === max($columnsPerRow)) {
			// remove the first row containing the column names
			array_shift($rows);

			for ($i = 0; $i < count($rows); $i++) {
				if (count($rows[$i]) === max($columnsPerRow)) {
					$newRow = array(
						'id' => $i + 1
					);

					foreach ($fileProperties['renameColumns'] as $columnIndex => $columnName) {
						if (!is_null($columnName)) {
							$newRow[$columnName] = $rows[$i][$columnIndex];
						}
					}

					$rows[$i] = $newRow;
				}
				else {
					unset($rows[$i]);
					echo "   * Skipped row #".$i."\n";
				}
			}

			$json = json_encode($rows, JSON_OUTPUT_FLAGS | JSON_PRETTY_PRINT);
			$json .= "\n";

			file_put_contents(OUTPUT_FOLDER.$fileName.'.json', $json);

			echo "   * Done\n";
		}
		else {
			echo "   * Unhandled columns = {".implode('|', $rows[0])."}\n";
		}
	}
}

// END BUILD PROCESS
