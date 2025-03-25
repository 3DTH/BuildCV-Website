<?php
class StringHelper {
    public static function createSlug($string) {
        $search = array(
            '#(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)#u',
            '#(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)#u',
            '#(ì|í|ị|ỉ|ĩ)#u',
            '#(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)#u',
            '#(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)#u',
            '#(ỳ|ý|ỵ|ỷ|ỹ)#u',
            '#(đ)#u',
            '#[^a-z0-9\s]#i',
        );
        $replace = array(
            'a', 'e', 'i', 'o', 'u', 'y', 'd', ''
        );
        $string = preg_replace($search, $replace, $string);
        $string = strtolower($string);
        $string = preg_replace('/[^a-z0-9]/', '-', $string);
        $string = preg_replace('/-+/', '-', $string);
        $string = trim($string, '-');
        return $string;
    }
    
    public static function truncate($string, $length = 100, $append = '...') {
        $string = trim($string);
        if (strlen($string) > $length) {
            $string = wordwrap($string, $length);
            $string = explode("\n", $string, 2);
            $string = $string[0] . $append;
        }
        return $string;
    }
}