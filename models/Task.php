<?php

require_once __DIR__ . '/../database/Database.php';

class Task
{
    public $id;
    public $user_id;
    public $title;
    public $description;
    public $status;
    public $created_at;
    public $updated_at;
    public $completed_at;

    public function __construct(
        $id,
        $user_id,
        $title,
        $description,
        $status,
        $created_at,
        $updated_at,
        $completed_at
    ) {
        $this->id = $id;

        $this->user_id = $user_id;

        $this->title = $title;

        $this->description = $description;

        $this->status = $status;

        $this->created_at = $created_at;

        $this->updated_at = $updated_at;

        $this->completed_at = $completed_at;
    }

    public static function getConnection() {
        $db = new Database();
        return $db->getConnection();
    }

    public static function create($user_id, $title, $description)
    {
        $conn = self::getConnection();

        // Generate uuidv4
        $id = uniqid();

        $status = 'pending';
        $created_at = date('Y-m-d H:i:s');
        $updated_at = date('Y-m-d H:i:s');
        $completed_at = null;

        $sql = "
            INSERT INTO tasks (
                id,
                user_id,
                title,
                description,
                status,
                created_at,
                updated_at,
                completed_at
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "ssssssss",
            $id,
            $user_id,
            $title,
            $description,
            $status,
            $created_at,
            $updated_at,
            $completed_at
        );

        $success = $stmt->execute();

        if (!$success) {
            return null;
        }

        return new Task(
            $id,
            $user_id,
            $title,
            $description,
            $status,
            $created_at,
            $updated_at,
            $completed_at
        );
    }

    public static function getAll() {
        $conn = self::getConnection();

        $sql = "SELECT * FROM tasks WHERE user_id = ? ORDER BY updated_at DESC";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $_SESSION['user_id']);
        $result = $stmt->execute();

        $tasks = [];

        if ($result) {
            $res = $stmt->get_result();
            while ($row = $res->fetch_assoc()) {
                $tasks[] = new Task(
                    $row['id'],
                    $row['user_id'],
                    $row['title'],
                    $row['description'],
                    $row['status'],
                    $row['created_at'],
                    $row['updated_at'],
                    $row['completed_at']
                );
            }
        }

        return $tasks;
    }

    public static function complete($id) {
        $conn = self::getConnection();

        $status = 'completed';
        $updated_at = date('Y-m-d H:i:s');
        $completed_at = date('Y-m-d H:i:s');

        $sql = "
            UPDATE tasks
            SET status = ?, updated_at = ?, completed_at = ?
            WHERE id = ? AND user_id = ?
        ";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "sssss",
            $status,
            $updated_at,
            $completed_at,
            $id,
            $_SESSION['user_id']
        );

        return $stmt->execute();
    }

    public static function delete($id) {
        $conn = self::getConnection();

        $sql = "DELETE FROM tasks WHERE id = ? AND user_id = ?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "ss",
            $id,
            $_SESSION['user_id']
        );

        return $stmt->execute();
    }

    public static function update($id, $title, $description) {
        $conn = self::getConnection();

        $updated_at = date('Y-m-d H:i:s');

        $sql = "
            UPDATE tasks
            SET title = ?, description = ?, updated_at = ?
            WHERE id = ? AND user_id = ?
        ";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "sssss",
            $title,
            $description,
            $updated_at,
            $id,
            $_SESSION['user_id']
        );

        return $stmt->execute();
    }
}
