
<?php
class DateHelper {
    public static function format($date, $format = 'd/m/Y') {
        $timestamp = strtotime($date);
        return date($format, $timestamp);
    }
}


