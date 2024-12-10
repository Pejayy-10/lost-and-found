<?php

class Validator {

    // Validate email format
    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    // Validate that the password is at least 6 characters
    public static function validatePassword($password) {
        return strlen($password) >= 6;
    }

    // Validate date format (YYYY-MM-DD)
    public static function validateDate($date) {
        return preg_match("/\d{4}-\d{2}-\d{2}/", $date);
    }
}
