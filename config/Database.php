<?php
class Database {
    private static ?PDO $pdo = null;

    public static function connect(): PDO {
        if(self::$pdo === null){
            self::$pdo = new PDO("mysql:host=localhost;dbname=coach_platform;charset=utf8","root","Root@123");
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$pdo;
    }
}
