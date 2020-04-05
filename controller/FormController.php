<?php


class FormController
{
    public static function sanitize($data){
        $data = trim($data);
        $data = htmlspecialchars($data);
        return mysqli_real_escape_string(DBAccess::getController()->getConnection(), $data);
    }
}