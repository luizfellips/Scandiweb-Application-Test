<?php
namespace Module;

class Connection
{
    private static $connection;

    private function __construct()
    {
    }

    public static function getConnection()
    {

        $dsn = 'mysql:host=localhost;dbname=' . DB_NAME . ';';

        if (!isset($connection)) {
            $connection = new \PDO($dsn, DB_USER, DB_PASSWORD);
            $connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }
        return $connection;
    }
}
?>