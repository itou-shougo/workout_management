<?php
// データベース管理クラス
class DatabaseManager
{
    public function connectDatabase()
    {
        if (!defined('DSN')) {
            define('DSN', 'pgsql:host=host; dbname=dbname;');
        }
        if (!defined('DB_USERNAME')) {
            define('DB_USERNAME', 'dbusername');
        }
        if (!defined('DB_PASSWORD')) {
            define('DB_PASSWORD', 'dbpassword');
        }

        try {
            $dbh =  new PDO(DSN, DB_USERNAME, DB_PASSWORD);
            return $dbh;
        } catch (PDOException $e) {
            print($e->getMessage());
            die();
        }
    }
}
