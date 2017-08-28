<?php
class Validation {
    const NOT_ONE_ERROR_MESSAGE =
            ' must be the value 1.';
    static function notOne($str) {
        if ($str !== '1') {
            return true;
        } else {
            return false;
        }
    }
    const NOT_ALPHA_SPACE_ERROR_MESSAGE =
            ' must contain only alphabetic characters, possibly separated by spaces.';
    static function notAlphaSpace($str) {
        if (!preg_match('/^[A-Za-z]+(\s+[A-Za-z]+)*$/', $str)) {
            return true;
        } else {
            return false;
        }
    }
    const NOT_ALPHANUM_SPACE_ERROR_MESSAGE =
            ' must contain only alphabetic and numeric characters, possibly separated by spaces.';
    static function notAlphaNumSpace($str) {
        if (!preg_match('/^[A-Za-z0-9]+(\s+[A-Za-z0-9]+)*$/', $str)) {
            return true;
        } else {
            return false;
        }
    }
    const NOT_IPV4_ADDR_ERROR_MESSAGE =
            ' must be a valid IPv4 Address.';
    static function notIPv4Addr($str) {
        $_1to254 = "(([1-9]|[1-9][0-9]|1[0-9][0-9])|(2[0-4][0-9]|25[0-4]))";
        $_0to254 = "(([0-9]|[1-9][0-9]|1[0-9][0-9])|(2[0-4][0-9]|25[0-4]))";
        $validIPv4AddrRegEx = "$_1to254\.$_0to254\.$_0to254\.$_1to254";
        if (!preg_match("/^$validIPv4AddrRegEx$/", $str)) {
            return true;
        } else {
            return false;
        }
    }
    const FILE_UPLOAD_ERROR_MESSAGE =
            ' must be a valid file. Please try again.';
    static function fileUploadError($file) {
        if ($file['error'] > 0) {
            return true;
        } else {
            return false;
        }
    }
    const NOT_ALPHANUM_ERROR_MESSAGE =
            ' must contain only alphanumeric characters.';
    static function notAlphaNumeric($str) {
        if (!ctype_alnum($str)) {
            return true;
        } else {
            return false;
        }
    }
}
?>
