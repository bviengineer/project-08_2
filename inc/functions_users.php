<?php

// Find a user by username
function findUserByUsername($username) {
    global $db;
    try {
        $query = $db->prepare('SELECT * from users where username = :username');
        $query->bindParam(':username', $username);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result;
    } catch (\Exception $e) {
        throw $e;
    }
}
// Create a new user account
function createNewUser($username, $password) {
    global $db;
    try {
        $query = $db->prepare('INSERT INTO users (username, password) VALUES (:username, :password)' );
        $query->bindParam(':username', $username);
        $query->bindParam(':password', $password);
        $query->execute();
        return findUserByUsername($username);
    } catch (\Exception $e) {
        throw $e;
    }
}
// Find a user by username
function findUserByUserId($id) {
    global $db;

    try {
        $query = $db->prepare('SELECT * from users where id = :id');
        $query->bindParam(':id', $id);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result;
    } catch (\Exception $e) {
        throw $e;
    }
}
// Update a user's password
function updatePassword($password, $userId) {
    global $db;

    try {
        $query = $db->prepare('UPDATE users SET password = :password WHERE username = :userId');
        $query->bindParam(':password', $password);
        $query->bindParam(':userId', $userId);
        $query->execute();
    } catch (\Exception $e) {
        return false;
    }
    return true;
}
// Assign new task(s) to logged in user
function assignUserNewTasks() {
    global $db;

    if (isAuthenticated()) {
        $id = getAuthenticatedUser();
        $user = findUserByuserId($id); 

        try {
            $query = $db->prepare('UPDATE tasks SET user_id = :userId WHERE user_id IS NULL');
            $query->bindParam(':userId', $user['id']);
            $query->execute();
        } catch (\Exception $e) {
            throw $e;
        }
    }
}