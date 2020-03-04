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
function updatePassword($password, $username) {
    global $db;
    try {
        $query = $db->prepare('UPDATE users SET password = :password WHERE username = :username');
        $query->bindParam(':password', $password);
        $query->bindParam(':username', $username);
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
        $user = get_object_vars(decodeAuthCookie());
        $id = $user['sub'];

        try {
            $query = $db->prepare('UPDATE tasks SET user_id = :userId WHERE user_id IS NULL');
            $query->bindParam(':userId', $id);
            $query->execute();
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
// Display completed and incompleted tasks assiged to logged in user
function displayAllUserTasks() {
    global $db;
   if (isAuthenticated()) {
        $user = get_object_vars(decodeAuthCookie());
        $id = $user['sub'];
        try {
            $query = $db->prepare('SELECT * FROM tasks WHERE user_id = :userId');
            $query->bindParam(':userId', $id);
            $query->execute();
            $results = $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            return false;
        }
       return $results;
   }
}
// Display completed tasks assiged to logged in user
function displayCompletedUserTasks() {
    global $db;
    if (isAuthenticated()) {   
        $user = get_object_vars(decodeAuthCookie()); 
        /* NOTE to future self:
        * $id = $user['sub'] & $user['sub']
        * is used interchangebly in the bindParams of the last 4 functions
        * getting all, completed and incomplete tasks, because they return
        * the same result and to to demonstrate different ways to obtain 
        * the user id or the same result 
        */ 
        try {
            $query = $db->prepare('SELECT * FROM tasks WHERE user_id = :userId AND status = "1" ');
            $query->bindParam(':userId', $user['sub']);
            $query->execute();
            $results = $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            return false;
        }
       return $results;
    }
}
// Display completed tasks assiged to logged in user
function displayIncompleteUserTasks() {
    global $db;
    if (isAuthenticated()) {   
        $user = get_object_vars(decodeAuthCookie());
        try {
            $query = $db->prepare('SELECT * FROM tasks WHERE user_id = :userId AND status = "0" ');
            $query->bindParam(':userId', $user['sub']);
            $query->execute();
            $results = $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            return false;
        }
       return $results;
    }
}