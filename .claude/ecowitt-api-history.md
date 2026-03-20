# Ecowitt API v3 - History Endpoint Findings

## Authentication

All endpoints use query string params (no headers/tokens):
- `application_key` - Application key from Ecowitt dashboard
- `api_key` - API key from Ecowitt dashboard
- `mac` - Device MAC address

These are stored in the `Connection` class (`src/Ecowitt/Api/Connection/Connection.php`).

---

## Endpoint Comparison

### Real-Time (existing)

**URL**: `GET https://api.ecowitt.net/api/v3/device/real_time`

**Params**:
| Param | Required | Value |
|---|---|---|
| `application_key` | Yes | Auth |
| `api_key` | Yes | Auth |
| `mac` | Yes | Device MAC |
| `call_back` | Yes | `all` works here |

**Response structure** - single snapshot per measurement:
```json
{
  "code": 0,
  "msg": "success",
  "time": "1772657584",
  "data": {
    "outdoor": {
      "temperature": {
        "time": "1772657583",
        "unit": "ºF",
        "value": "43.7"
      }
    }
  }
}
```

Each measurement has: `time` (unix timestamp string), `unit`, `value`.

---

### History (new)

**URL**: `GET https://api.ecowitt.net/api/v3/device/history`

**Params**:
| Param | Required | Value | Notes |
|---|---|---|---|
| `application_key` | Yes | Auth | |
| `api_key` | Yes | Auth | |
| `mac` | Yes | Device MAC | |
| `call_back` | Yes | Sensor group names | **`all` does NOT work** - returns error `40016: "all is invalid"` |
| `start_date` | Yes | `YYYY-MM-DD HH:MM:SS` | Start of date range |
| `end_date` | Yes | `YYYY-MM-DD HH:MM:SS` | End of date range |
| `cycle_type` | Yes | Aggregation interval | See below |

**`call_back` values** (comma-separated):
- `outdoor` - temperature, feels_like, app_temp, dew_point, humidity
- `indoor` - temperature, humidity, dew_point, feels_like, app_tempin
- `rainfall` - rain_rate, daily, event, 1_hour, weekly, monthly, yearly
- `wind` - wind_speed, wind_gust, wind_direction
- `pressure` - relative, absolute
- TODO: test `solar_and_uvi`, `lightning`, `battery`, channel groups etc.

**`cycle_type` values** (confirmed `4hour` works):
- `auto` - untested
- `5min` - untested
- `30min` - untested
- `1hour` - untested
- `4hour` - confirmed working, returned 23 data points for ~3 day range
- `1day` - untested

**Response structure** - time series per measurement:
```json
{
  "code": 0,
  "msg": "success",
  "time": "1772657696",
  "data": {
    "outdoor": {
      "temperature": {
        "unit": "ºF",
        "list": {
          "1772323200": "38.6",
          "1772337600": "42.5",
          "1772352000": "47.8"
        }
      }
    }
  }
}
```

Each measurement has: `unit`, `list` (object of `unix_timestamp: value` pairs).

---

## Key Differences: Real-Time vs History

| | Real-Time | History |
|---|---|---|
| Endpoint | `/device/real_time` | `/device/history` |
| `call_back` | `all` works | Must specify groups explicitly |
| Date params | Ignored | Required (`start_date`, `end_date`) |
| `cycle_type` | N/A | Required |
| Measurement format | `{ time, unit, value }` | `{ unit, list: { timestamp: value } }` |
| Data points | Always 1 | Multiple (depends on range + cycle_type) |

---

## Error Codes Seen

| Code | Message | Cause |
|---|---|---|
| `0` | `success` | OK |
| `40016` | `all is invalid` | Used `call_back=all` on history endpoint |

---

## Existing Plugin Code

- `Ecowitt.php:120` - `get_observation_history()` facade method exists, calls `Observation_Service`
- `Observation_Service` - `get_observation_history()` method does NOT exist yet (needs implementing)
- Real-time URL built at `Observation_Service:94`
- Base URL from config: `https://api.ecowitt.net/api/v3`

---

## Caching Decision

- **Custom DB table** chosen for historical observation data
- Reason: users need to be able to query anything (not just simple key-value cache)
- No caching exists currently - clean slate
- This is a distributable plugin - must work on any WP install, no assumptions about host environment
- Will need Perique migration module (not yet in composer.json) - review Perique migration docs when implementing
- Transients rejected: not queryable enough. Object cache rejected: not persistent on most hosts. Browser DB rejected: no server-side benefit.

---

## TODO

- [ ] Test remaining `call_back` groups: `solar_and_uvi`, `lightning`, `battery`, channel groups
- [ ] Test all `cycle_type` values
- [ ] Test max date range limits (API may have a cap)
- [ ] Create History DTO (different structure from real-time Measurement DTO)
- [ ] Implement `Observation_Service::get_observation_history()`
- [ ] Decide on view/presentation layer for historical data (charts? tables?)
