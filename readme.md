# Ecowitt Weather Block

![License](https://img.shields.io/badge/license-GPL--3.0--or--later-blue.svg)
![PHP Version](https://img.shields.io/badge/PHP-8.1%2B-blue.svg)
![WordPress Version](https://img.shields.io/badge/WordPress-6.1%2B-blue.svg)
![GitHub Issues](https://img.shields.io/github/issues/gin0115/ecowitt-weather-block)
![WP6.4 [PHP7.4-8.3] Tests](https://github.com/gin0115/ecowitt-weather-block/actions/workflows/WP_6_4.yaml/badge.svg)
![WP6.8 [PHP8.0-8.4] Tests](https://github.com/gin0115/ecowitt-weather-block/actions/workflows/WP_6_8.yaml/badge.svg)
![WP6.9 [PHP8.0-8.5] Tests](https://github.com/gin0115/ecowitt-weather-block/actions/workflows/WP_6_9.yaml/badge.svg)

A WordPress block plugin for displaying real-time and historical weather data from [Ecowitt](https://www.ecowitt.com/) weather stations.

## Features

- **Live Weather Block** — displays current weather observations with configurable auto-refresh
- **Weather History Block** — renders historical weather charts with adjustable date ranges and aggregation intervals
- **Multiple Themes** — default, materialike, slate and dashboard themes with customisable colours and icon sets
- **Unit Conversion** — automatic conversion between metric and imperial units for all measurement types
- **Caching** — database-backed observation cache to reduce API calls
- **GitHub Auto-Updates** — checks for new releases and provides one-click updates from the WordPress dashboard

## Supported Measurements

| Category | Measurements |
|---|---|
| **Outdoor** | Temperature, Feels Like, Dew Point, Humidity |
| **Indoor** | Temperature, Humidity |
| **Wind** | Speed, Gust, Direction |
| **Rainfall** | Rate, Hourly, Daily, Event, Weekly, Monthly, Yearly |
| **Pressure** | Relative, Absolute |
| **Solar** | Solar Radiation, UV Index |
| **Air Quality** | PM1.0, PM2.5, PM4.0, PM10 (real-time AQI, 24h AQI) |
| **CO2** | CO2 Level, 24h Average |
| **Soil** | Moisture (up to 8 channels) |
| **Leaf** | Wetness (up to 8 channels) |
| **Lightning** | Distance, Strike Count |
| **Water Leak** | Up to 4 channels |
| **Battery** | Status for all connected sensors |

## Requirements

- WordPress 6.1+
- PHP 8.1+
- An [Ecowitt](https://www.ecowitt.com/) weather station with API access

## Setup

1. Download the latest release from the [releases page](https://github.com/gin0115/ecowitt-weather-block/releases)
2. Upload and activate the plugin in WordPress
3. Navigate to **Settings > Ecowitt Weather** and add your Ecowitt API connection (Application Key + API Key)
4. Add the **Live Weather** or **Weather History** block to any post or page via the block editor

## Development

### Prerequisites

- Node.js 18+
- Composer
- PHP 8.1+

### Install dependencies

```bash
composer install
npm ci
```

### Build assets

```bash
npm run build
```

### Watch for changes

```bash
npm start
```

### Run tests

```bash
composer test:php
```

### Static analysis

```bash
composer analyse:php
```

### Lint

```bash
composer lint:php
npm run lint:scripts
npm run lint:styles
```

## Built With

- [Perique Framework](https://github.com/Pink-Crab/Perique-Framework) — WordPress plugin framework
- [Ecowitt API v3](https://doc.ecowitt.net/) — Weather station data
- [Recharts](https://recharts.org/) — React charting library for history visualisation

## License

[GPL-3.0-or-later](https://www.gnu.org/licenses/gpl-3.0.html)

## Change Log

* 1.0.0-RC1 — Initial release candidate
