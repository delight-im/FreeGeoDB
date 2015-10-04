# Source

## Making changes

If you want to make any changes, please apply them in the JSON files in folder [`json`](json). That's all!

## Exporting distribution files

If you want to regenerate the files in [Distribution](../Distribution) from the authoritative data source in [`json`](json), please run the script [`export.php`](export.php).

You usually don't need to do that, however. Just download the latest distribution files or wait for us to export them again.

## Building from "Natural Earth"

It's possible to recreate the files in [`json`](json) from [Natural Earth](http://www.naturalearthdata.com/). The latter provides ESRI shapefiles that are in the public domain.

In most cases, you will *not* need or want to do this.

Recreating the files in [`json`](json) will lose all improvements and fixes that have been applied there over time.

If you still want to do so, please proceed as follows:

 * Download the following ZIP files from [Natural Earth](http://www.naturalearthdata.com/):
   * `ne_10m_admin_0_countries`
   * `ne_10m_admin_1_states_provinces`
   * `ne_10m_airports`
   * `ne_10m_lakes`
   * `ne_10m_populated_places`
   * `ne_10m_ports`
   * `ne_10m_railroads`
   * `ne_10m_roads`
   * `ne_10m_time_zones`
 * Extract the ZIP files into folders of the same name
 * Move those folders into [`shapefiles`](shapefiles)
 * Get a JAR for the [Java-Shapefile-Parser](https://github.com/delight-im/Java-Shapefile-Parser)
 * Put `Java-Shapefile-Parser.jar` into the current directory
 * Run the script [`build.php`](build.php)
 * Temporary files will be created in [`csv`](csv)
 * Output files will be created in [`json`](json)
