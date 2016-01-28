# FreeGeoDB

Free database of geographic place names and corresponding geospatial data

## Entities

 * airports
 * cities
 * countries (admin-0)
 * lakes
 * ports
 * railroads
 * regions (admin-1)
 * roads
 * time zones

## Formats

 * [CSV](Distribution/CSV/)
 * [JSON](Distribution/JSON/)
 * [MySQL](Distribution/MySQL/)

## Representation of geographic coordinates

All geographic coordinates are stored in the [WKT format](https://en.wikipedia.org/wiki/Well-known_text).

In WKT, the pairs of coordinates are always written as `x y`.

Such tuples, potentially along with other tuples, are then wrapped by one of 18 geometric objects, e.g. `POINT`, `MULTILINESTRING` or `MULTIPOLYGON`.

The most basic example would be `POINT(x y)`.

Accordingly, the order of latitude and longitude in those tuples is always `long lat`, as in `POINT(long lat)`.

## Authoritative data source

The single authoritative source for all data is in [`Source/json`](Source/json).

If you want to make any changes, please apply them in that folder only.

All other data files are generated from the files in that authoritative set.

## Contributing

All contributions are welcome! If you wish to contribute, please create an issue first so that your feature, problem or question can be discussed.

## License

```
Copyright (c) delight.im <info@delight.im>

Except where otherwise noted, all content is licensed under a
Creative Commons Attribution 4.0 International License.

You should have received a copy of the license along with this
work. If not, see <http://creativecommons.org/licenses/by/4.0/>.
```
