<?php
class DBFactory{
    public static function getMysqlConnexionWithPDO(){
        $db = new PDO('mysql:host=localhost;dbname=gesapp', 'root', '');
        $db-> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //learn how to catch exception
        return $db;
    }
}
?>