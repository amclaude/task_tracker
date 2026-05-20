<?php

class Database
{
    private $host;
    private $username;
    private $password;
    private $database;
    private $port;

    public function __construct()
    {
        $this->host =
            getenv("DB_HOST") ?: 'localhost';

        $this->username =
            getenv("DB_USER") ?: 'root';

        $this->password =
            getenv("DB_PASSWORD") ?: '';

        $this->database =
            getenv("DB_NAME") ?: 'task_tracker';

        $this->port =
            getenv("DB_PORT") ?: 3306;
    }

    public function getConnection()
    {
        mysqli_report(
            MYSQLI_REPORT_ERROR |
            MYSQLI_REPORT_STRICT
        );

        $isProduction =
            getenv("APP_ENV") === "production";

        // Production (Cloud DB with SSL)
        if ($isProduction) {

            $conn = mysqli_init();

            $conn->real_connect(
                $this->host,
                $this->username,
                $this->password,
                $this->database,
                (int)$this->port,
                null,
                MYSQLI_CLIENT_SSL
            );

            return $conn;
        }

        // Local development
        $conn = new mysqli(
            $this->host,
            $this->username,
            $this->password,
            $this->database,
            (int)$this->port
        );

        return $conn;
    }
}