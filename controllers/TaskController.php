<?php

require_once __DIR__ . '/../models/Task.php';

class TaskController
{
    public function create(
        $user_id,
        $title,
        $description
    ) {
        return Task::create(
            $user_id,
            $title,
            $description
        );
    }

    public function getAll($user_id)
    {
        return Task::getAll($user_id);
    }

    public function complete($task_id)
    {
        return Task::complete($task_id);
    }
    
    public function delete($task_id)
    {
        return Task::delete($task_id);
    }

    public function edit($task_id, $title, $description)
    {
        return Task::update(
            $task_id,
            $title,
            $description
        );
    }
}