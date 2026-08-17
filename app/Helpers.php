<?php

namespace App;

use App\Models\Holiday;
use Illuminate\Support\Carbon;

class Helpers
{
    private static array $holidayCache = [];

    public static function getGoogleMapsUrl($lat, $lng)
    {
        return "https://maps.google.com/maps?q=$lat,$lng";
    }

    /**
     * Determine if a date is a holiday: weekend (Saturday/Sunday)
     * or a date registered in the holidays table.
     */
    public static function isHoliday($date): bool
    {
        $date = $date instanceof Carbon ? $date : Carbon::parse($date);
        $key = $date->format('Y-m-d');

        if (!array_key_exists($key, self::$holidayCache)) {
            self::$holidayCache[$key] = $date->isWeekend() || Holiday::where('date', $key)->exists();
        }

        return self::$holidayCache[$key];
    }

    /**
     * Get the URL path from the app URL
     *
     * E.g. base url/app url = http://localhost:8000/path => path
     *
     * Returns empty string if base url is root path
     */
    public static function getNonRootBaseUrlPath()
    {
        $segments = explode('/', parse_url(config('app.url'), PHP_URL_PATH));
        return count($segments) < 2 ? '' : $segments[1];
    }
}
