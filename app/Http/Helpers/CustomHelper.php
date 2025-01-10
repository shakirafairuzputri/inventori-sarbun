<?php
namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class CustomHelper {
    public static function toggleSign($value) {
        return $value < 0 ? abs($value) : -1 * $value;
    }
}
