# Source and Build Instructions

## Source

 * [Natural Earth](http://www.naturalearthdata.com/)
   * Format: ESRI shapefile
   * Encoding: Windows-1252
   * System: WGS84
   * License: Public domain

## Build Instructions

 1. Get the following `.zip` files from [Natural Earth](http://www.naturalearthdata.com/):
    * `ne_10m_admin_0_countries`
	* `ne_10m_admin_1_states_provinces`
	* `ne_10m_airports`
	* `ne_10m_lakes`
	* `ne_10m_populated_places`
	* `ne_10m_ports`
	* `ne_10m_railroads`
	* `ne_10m_roads`
	* `ne_10m_time_zones`
 2. Unzip all the `.zip` files to a directory with the same name
 3. Get a JAR from the [Java-Shapefile-Parser](https://github.com/delight-im/Java-Shapefile-Parser) project
 4. Put `Java-Shapefile-Parser.jar` into the current directory
 5. Convert the ESRI shapefiles to CSV files by executing the following commands from the current directory:

    ```
    java -jar "Java-Shapefile-Parser.jar" "ne_10m_airports/ne_10m_airports.shp" > airports.csv
    java -jar "Java-Shapefile-Parser.jar" "ne_10m_populated_places/ne_10m_populated_places.shp" > cities.csv
    java -jar "Java-Shapefile-Parser.jar" "ne_10m_admin_0_countries/ne_10m_admin_0_countries.shp" > countries.csv
    java -jar "Java-Shapefile-Parser.jar" "ne_10m_lakes/ne_10m_lakes.shp" > lakes.csv
    java -jar "Java-Shapefile-Parser.jar" "ne_10m_ports/ne_10m_ports.shp" > ports.csv
    java -jar "Java-Shapefile-Parser.jar" "ne_10m_railroads/ne_10m_railroads.shp" > railroads.csv
    java -jar "Java-Shapefile-Parser.jar" "ne_10m_admin_1_states_provinces/ne_10m_admin_1_states_provinces.shp" > regions.csv
    java -jar "Java-Shapefile-Parser.jar" "ne_10m_roads/ne_10m_roads.shp" > roads.csv
    java -jar "Java-Shapefile-Parser.jar" "ne_10m_time_zones/ne_10m_time_zones.shp" > time_zones.csv
	```

 6. Create a new MySQL database with charset `utf8` and collation `utf8_general_ci`
 7. Import the CSV files into the empty MySQL database and, for every file, specify that the first row contains the column names
 8. Rename the new tables from their auto-generated names to the name of the CSV file and change the storage engine to `MyISAM`
 9. Execute the table-specific MySQL commands given below

### `airports`

```
ALTER TABLE airports ADD id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST; # add a unique sequential ID
DELETE FROM airports WHERE the_geom IS NULL OR the_geom = ''; # delete rows that have no geographic data or are empty
ALTER TABLE airports ADD coordinates POINT NOT NULL AFTER id; # add a new spatial column for the coordinates
UPDATE airports SET coordinates = GeomFromText(the_geom); # load the WKT data into the spatial column
ALTER TABLE airports CHANGE `the_geom` `coordinates_wkt` VARCHAR(47) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL; # rename the WKT data column
ALTER TABLE airports ADD SPATIAL(coordinates); # add an index on the spatial column

ALTER TABLE airports
  DROP `scalerank`,
  DROP `featurecla`,
  DROP `location`,
  DROP `wikipedia`,
  DROP `natlscale`,
  DROP `abbrev`;
ALTER TABLE airports
  CHANGE `gps_code` `icao_code` VARCHAR(5) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
```

### `cities`

```
ALTER TABLE cities ADD id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST; # add a unique sequential ID
DELETE FROM cities WHERE the_geom IS NULL OR the_geom = ''; # delete rows that have no geographic data or are empty
ALTER TABLE cities ADD coordinates POINT NOT NULL AFTER id; # add a new spatial column for the coordinates
UPDATE cities SET coordinates = GeomFromText(the_geom); # load the WKT data into the spatial column
ALTER TABLE cities CHANGE `the_geom` `coordinates_wkt` VARCHAR(47) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL; # rename the WKT data column
ALTER TABLE cities ADD SPATIAL(coordinates); # add an index on the spatial column

ALTER TABLE cities
  DROP `SCALERANK`,
  DROP `NATSCALE`,
  DROP `LABELRANK`,
  DROP `FEATURECLA`,
  DROP `DIFFASCII`,
  DROP `NOTE`,
  DROP `LATITUDE`,
  DROP `LONGITUDE`,
  DROP `CHANGED`,
  DROP `NAMEDIFF`,
  DROP `DIFFNOTE`,
  DROP `POP1950`,
  DROP `POP1955`,
  DROP `POP1960`,
  DROP `POP1965`,
  DROP `POP1970`,
  DROP `POP1975`,
  DROP `POP1980`,
  DROP `POP1985`,
  DROP `POP1990`,
  DROP `POP1995`,
  DROP `POP2000`,
  DROP `POP2005`,
  DROP `POP2010`,
  DROP `POP2015`,
  DROP `POP2020`,
  DROP `POP2025`,
  DROP `POP2050`,
  DROP `GEONAMESNO`,
  DROP `NAMEPAR`,
  DROP `COMPARE`,
  DROP `CHECKME`,
  DROP `SOV0NAME`,
  DROP `SOV_A3`,
  DROP `FEATURE_CL`,
  DROP `CAPIN`,
  DROP `CAPALT`,
  DROP `ISO_A2`,
  DROP `POP_MAX`,
  DROP `POP_MIN`,
  DROP `POP_OTHER`,
  DROP `RANK_MAX`,
  DROP `RANK_MIN`,
  DROP `MAX_POP10`,
  DROP `MAX_POP20`,
  DROP `MAX_POP50`,
  DROP `MAX_POP300`,
  DROP `MAX_POP310`,
  DROP `MAX_NATSCA`,
  DROP `MIN_AREAKM`,
  DROP `MAX_AREAKM`,
  DROP `MIN_AREAMI`,
  DROP `MAX_AREAMI`,
  DROP `MIN_PERKM`,
  DROP `MAX_PERKM`,
  DROP `MIN_PERMI`,
  DROP `MAX_PERMI`,
  DROP `MIN_BBXMIN`,
  DROP `MAX_BBXMIN`,
  DROP `MIN_BBXMAX`,
  DROP `MAX_BBXMAX`,
  DROP `MIN_BBYMIN`,
  DROP `MAX_BBYMIN`,
  DROP `MIN_BBYMAX`,
  DROP `MAX_BBYMAX`,
  DROP `MEAN_BBXC`,
  DROP `MEAN_BBYC`,
  DROP `UN_ADM0`,
  DROP `UN_LAT`,
  DROP `UN_LONG`,
  DROP `GEONAMEID`,
  DROP `MEGANAME`,
  DROP `LS_NAME`,
  DROP `LS_MATCH`,
  DROP `FEATURE_CO`,
  DROP `ADMIN1_COD`,
  DROP `ELEVATION`,
  DROP `GN_ASCII`,
  DROP `GN_POP`,
  DROP `GTOPO30`,
  DROP `UN_FID`,
  DROP `CITYALT;
ALTER TABLE cities
  CHANGE `NAME` `name` VARCHAR(33) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  CHANGE `NAMEALT` `name_alt` VARCHAR(43) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  CHANGE `NAMEASCII` `name_ascii` VARCHAR(39) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  CHANGE `ADM0CAP` `is_capital` DECIMAL(2,1) NULL DEFAULT NULL,
  CHANGE `WORLDCITY` `is_world_city` DECIMAL(2,1) NULL DEFAULT NULL,
  CHANGE `MEGACITY` `is_mega_city` INT(1) NULL DEFAULT NULL,
  CHANGE `ADM0NAME` `country` VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  CHANGE `ADM0_A3` `country_iso_alpha3` VARCHAR(3) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  CHANGE `ADM1NAME` `region` VARCHAR(43) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  CHANGE `TIMEZONE` `time_zone` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
```

### `countries`

```
ALTER TABLE countries ADD id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST; # add a unique sequential ID
DELETE FROM countries WHERE the_geom IS NULL OR the_geom = ''; # delete rows that have no geographic data or are empty
ALTER TABLE countries ADD coordinates MULTIPOLYGON NOT NULL AFTER id; # add a new spatial column for the coordinates
UPDATE countries SET coordinates = GeomFromText(the_geom); # load the WKT data into the spatial column
ALTER TABLE countries CHANGE `the_geom` `coordinates_wkt` MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL; # rename the WKT data column
ALTER TABLE countries ADD SPATIAL(coordinates); # add an index on the spatial column

ALTER TABLE countries
  DROP `scalerank`,
  DROP `featurecla`,
  DROP `LABELRANK`,
  DROP `GEOU_DIF`,
  DROP `SU_DIF`,
  DROP `BRK_DIFF`,
  DROP `FORMAL_FR`,
  DROP `NOTE_ADM0`,
  DROP `NOTE_BRK`,
  DROP `NAME_SORT`,
  DROP `MAPCOLOR7`,
  DROP `MAPCOLOR8`,
  DROP `MAPCOLOR9`,
  DROP `MAPCOLOR13`,
  DROP `WIKIPEDIA`,
  DROP `WOE_ID`,
  DROP `WOE_ID_EH`,
  DROP `WOE_NOTE`,
  DROP `NAME_LEN`,
  DROP `LONG_LEN`,
  DROP `ABBREV_LEN`,
  DROP `TINY`,
  DROP `ADM0_DIF`,
  DROP `LEVEL`,
  DROP `NAME_ALT`,
  DROP `POP_EST`,
  DROP `GDP_MD_EST`,
  DROP `GEOUNIT`,
  DROP `SUBUNIT`,
  DROP `BRK_NAME`,
  DROP `NAME`,
  DROP `NAME_LONG`,
  DROP `BRK_GROUP`,
  DROP `ABBREV`,
  DROP `POSTAL`,
  DROP `POP_YEAR`,
  DROP `LASTCENSUS`,
  DROP `GDP_YEAR`,
  DROP `ADM0_A3_UN`,
  DROP `ADM0_A3_WB`,
  DROP `HOMEPART`,
  DROP `SOV_A3`,
  DROP `TYPE`,
  DROP `ADM0_A3`,
  DROP `GU_A3`,
  DROP `SU_A3`,
  DROP `BRK_A3`,
  DROP `UN_A3`,
  DROP `REGION_UN`,
  DROP `REGION_WB`,
  DROP `FIPS_10_`,
  DROP `WB_A2`,
  DROP `WB_A3`,
  DROP `ADM0_A3_IS`,
  DROP `ADM0_A3_US`;
ALTER TABLE `countries`
  CHANGE `ECONOMY` `economy_level` VARCHAR(26) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  CHANGE `INCOME_GRP` `income_level` VARCHAR(23) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  CHANGE `SOVEREIGNT` `sovereign` VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  CHANGE `ADMIN` `name` VARCHAR(40) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  CHANGE `FORMAL_EN` `formal` VARCHAR(52) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  CHANGE `ISO_A2` `iso_alpha2` VARCHAR(3) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  CHANGE `ISO_A3` `iso_alpha3` VARCHAR(3) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  CHANGE `ISO_N3` `iso_numeric3` INT(3) NULL DEFAULT NULL,
  CHANGE `CONTINENT` `continent` VARCHAR(23) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  CHANGE `SUBREGION` `subregion` VARCHAR(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
UPDATE countries SET economy_level = CONCAT("(", SUBSTR(economy_level, 1, 1), "/7)", SUBSTR(economy_level, 3));
UPDATE countries SET income_level = CONCAT("(", SUBSTR(income_level, 1, 1), "/5)", SUBSTR(income_level, 3));
```

### `lakes`

```
ALTER TABLE lakes ADD id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST; # add a unique sequential ID
DELETE FROM lakes WHERE the_geom IS NULL OR the_geom = ''; # delete rows that have no geographic data or are empty
ALTER TABLE lakes ADD coordinates MULTIPOLYGON NOT NULL AFTER id; # add a new spatial column for the coordinates
UPDATE lakes SET coordinates = GeomFromText(the_geom); # load the WKT data into the spatial column
ALTER TABLE lakes CHANGE `the_geom` `coordinates_wkt` MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL; # rename the WKT data column
ALTER TABLE lakes ADD SPATIAL(coordinates); # add an index on the spatial column

ALTER TABLE lakes
  DROP `featurecla`,
  DROP `scalerank`,
  DROP `name_abb`,
  DROP `note`,
  DROP `delta`,
  DROP `year`,
  DROP `admin`;
```

### `ports`

```
ALTER TABLE ports ADD id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST; # add a unique sequential ID
DELETE FROM ports WHERE the_geom IS NULL OR the_geom = ''; # delete rows that have no geographic data or are empty
ALTER TABLE ports ADD coordinates POINT NOT NULL AFTER id; # add a new spatial column for the coordinates
UPDATE ports SET coordinates = GeomFromText(the_geom); # load the WKT data into the spatial column
ALTER TABLE ports CHANGE `the_geom` `coordinates_wkt` VARCHAR(46) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL; # rename the WKT data column
ALTER TABLE ports ADD SPATIAL(coordinates); # add an index on the spatial column

ALTER TABLE ports
  DROP `scalerank`,
  DROP `featurecla`,
  DROP `website`,
  DROP `natlscale`;
```

### `railroads`

```
ALTER TABLE railroads ADD id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST; # add a unique sequential ID
DELETE FROM railroads WHERE the_geom IS NULL OR the_geom = ''; # delete rows that have no geographic data or are empty
ALTER TABLE railroads ADD coordinates MULTILINESTRING NOT NULL AFTER id; # add a new spatial column for the coordinates
UPDATE railroads SET coordinates = GeomFromText(the_geom); # load the WKT data into the spatial column
ALTER TABLE railroads CHANGE `the_geom` `coordinates_wkt` VARCHAR(19561) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL; # rename the WKT data column
ALTER TABLE railroads ADD SPATIAL(coordinates); # add an index on the spatial column

ALTER TABLE railroads
  DROP `rwdb_rr_id`,
  DROP `add`,
  DROP `featurecla`,
  DROP `scalerank`,
  DROP `natlscale`,
  DROP `part`,
  DROP `other_code`,
  DROP `category`,
  DROP `disp_scale`;
```

### `regions`

```
ALTER TABLE regions ADD id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST; # add a unique sequential ID
DELETE FROM regions WHERE the_geom IS NULL OR the_geom = ''; # delete rows that have no geographic data or are empty
ALTER TABLE regions ADD coordinates MULTIPOLYGON NOT NULL AFTER id; # add a new spatial column for the coordinates
UPDATE regions SET coordinates = GeomFromText(the_geom); # load the WKT data into the spatial column
ALTER TABLE regions CHANGE `the_geom` `coordinates_wkt` MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL; # rename the WKT data column
ALTER TABLE regions ADD SPATIAL(coordinates); # add an index on the spatial column

ALTER TABLE regions
  DROP `wikipedia`,
  DROP `note`,
  DROP `scalerank`,
  DROP `datarank`,
  DROP `abbrev`,
  DROP `labelrank`,
  DROP `featurecla`,
  DROP `name_len`,
  DROP `mapcolor9`,
  DROP `mapcolor13`,
  DROP `fips`,
  DROP `fips_alt`,
  DROP `woe_id`,
  DROP `woe_name`,
  DROP `latitude`,
  DROP `longitude`,
  DROP `type`,
  DROP `check_me`,
  DROP `OBJECTID_1`,
  DROP `sameascity`,
  DROP `area_sqkm`,
  DROP `gn_level`,
  DROP `gn_region`,
  DROP `name_local`,
  DROP `code_local`,
  DROP `adm0_label`,
  DROP `sub_code`,
  DROP `gns_level`,
  DROP `gns_region`,
  DROP `iso_3166_2`,
  DROP `geonunit`,
  DROP `adm0_sr`,
  DROP `gadm_level`,
  DROP `adm1_cod_1`,
  DROP `gns_id`,
  DROP `adm1_code`,
  DROP `diss_me`,
  DROP `hasc_maybe`,
  DROP `provnum_ne`,
  DROP `woe_label`,
  DROP `sov_a3`,
  DROP `gns_name`,
  DROP `gns_lang`,
  DROP `gns_adm1`,
  DROP `gu_a3`;
ALTER TABLE `regions`
  CHANGE `iso_a2` `iso_alpha2` VARCHAR(2) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  CHANGE `adm0_a3` `country_iso_alpha3` VARCHAR(3) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  CHANGE `admin` `country` VARCHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
```

### `roads`

```
ALTER TABLE roads ADD id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST; # add a unique sequential ID
DELETE FROM roads WHERE the_geom IS NULL OR the_geom = ''; # delete rows that have no geographic data or are empty
ALTER TABLE roads ADD coordinates MULTILINESTRING NOT NULL AFTER id; # add a new spatial column for the coordinates
UPDATE roads SET coordinates = GeomFromText(the_geom); # load the WKT data into the spatial column
ALTER TABLE roads CHANGE `the_geom` `coordinates_wkt` VARCHAR(8154) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL; # rename the WKT data column
ALTER TABLE roads ADD SPATIAL(coordinates); # add an index on the spatial column

ALTER TABLE roads
  DROP `scalerank`,
  DROP `featurecla`,
  DROP `note`,
  DROP `ne_part`,
  DROP `ignore`,
  DROP `add`,
  DROP `rwdb_rd_id`,
  DROP `edited`,
  DROP `name`,
  DROP `namealt`,
  DROP `namealtt`,
  DROP `sov_a3`,
  DROP `question`,
  DROP `localtype`,
  DROP `labelrank`,
  DROP `toll`,
  DROP `orig_fid`,
  DROP `uident`,
  DROP `routeraw`,
  DROP `label`,
  DROP `label2`,
  DROP `local`,
  DROP `localalt`,
  DROP `prefix`,
  DROP `level`;
ALTER TABLE `roads`
  CHANGE `expressway` `is_express` INT(1) NULL DEFAULT NULL;
UPDATE roads SET type = NULL WHERE type = 'Unknown';
```

### `time_zones`

```
ALTER TABLE time_zones ADD id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST; # add a unique sequential ID
DELETE FROM time_zones WHERE the_geom IS NULL OR the_geom = ''; # delete rows that have no geographic data or are empty
ALTER TABLE time_zones ADD coordinates MULTIPOLYGON NOT NULL AFTER id; # add a new spatial column for the coordinates
UPDATE time_zones SET coordinates = GeomFromText(the_geom); # load the WKT data into the spatial column
ALTER TABLE time_zones CHANGE `the_geom` `coordinates_wkt` MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL; # rename the WKT data column
ALTER TABLE time_zones ADD SPATIAL(coordinates); # add an index on the spatial column

ALTER TABLE time_zones
  DROP `objectid`,
  DROP `scalerank`,
  DROP `featurecla`,
  DROP `map_color6`,
  DROP `map_color8`,
  DROP `note`,
  DROP `time_zone`,
  DROP `zone`,
  DROP `iso_8601`,
  DROP `tz_namesum`;
ALTER TABLE time_zones
  CHANGE `name` `offset` DECIMAL(5,2) NULL DEFAULT NULL,
  CHANGE `utc_format` `name` VARCHAR(9) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  CHANGE `tz_name1st` `name_alt` VARCHAR(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
```
