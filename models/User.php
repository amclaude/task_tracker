<?php

require_once __DIR__ . "/../database/Database.php";

class User
{
    public $id;
    public $name;
    public $email;
    public $password;

    public static function getConnection()
    {
        $db = new Database();
        return $db->getConnection();
    }

    public function __construct($id, $name, $email, $password)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    public static function create($name, $email, $password)
    {
        $conn = self::getConnection();
        // Generate uuidv4
        $id = uniqid();

        $hashedPassword = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $sql = "
            INSERT INTO users (
                id,
                name,
                email,
                password
            )
            VALUES (?, ?, ?, ?)
        ";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "ssss",
            $id,
            $name,
            $email,
            $hashedPassword
        );

        $success = $stmt->execute();

        if (!$success) {
            return null;
        }
        // Return success message
        return new User(
            $id,
            $name,
            $email,
            $hashedPassword
        );
    }

    public static function findByEmail($email)
    {
        $conn = self::getConnection();

        $sql = "SELECT * FROM users WHERE email = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return null;
        }

        $row = $result->fetch_assoc();

        return new User(
            $row['id'],
            $row['name'],
            $row['email'],
            $row['password']
        );
    }
}
