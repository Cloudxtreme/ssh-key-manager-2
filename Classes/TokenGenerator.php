<?php
class TokenGenerator {
    public static function GenerateToken($len = 15) {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $token = '';
        for ($i = 0; $i < $len; $i++) {
            $token .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $token;
    }
}
?>
