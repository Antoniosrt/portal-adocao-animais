<?php

// Copie este arquivo para Database.php e preencha as credenciais
class Database
{
    private static ?PDO $instance = null;

    private string $host   = 'localhost';
    private int    $port   = 5432;
    private string $dbname = 'portal_adocao';
    private string $user   = 'postgres';
    private string $pass   = 'SUA_SENHA_AQUI';

    private function __construct() {}
    private function __clone() {}

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $db  = new self();
            $dsn = "pgsql:host={$db->host};port={$db->port};dbname={$db->dbname}";
            self::$instance = new PDO($dsn, $db->user, $db->pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
            self::$instance->exec("SET NAMES 'UTF8'");
        }
        return self::$instance;
    }
}
