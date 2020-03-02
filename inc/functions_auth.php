<?php
// Verifies whether a user is authenticated 
function isAuthenticated() {
    global $session;
    return $session->get('auth_logged_in', false);
}

// Require a user to authenticate in order to use certain resources
function requireAuth() {
    global $session; // need both of these global $session calls?
    if (!isAuthenticated()) {
        global $session;
        $session->getFlashBag()->add('error', 'You must be logged in to access this resource');
        redirect("/login.php");
    }
}
function getAuthenticatedUser() {
    global $session;
    return findUserByUserId($session->get('auth_user_id'));
}

// Save user session data
function saveUserData($user) {
    global $session;
    $session->set('auth_logged_in', true);
    $session->set('auth_user_id', (int) $user['id']);
    
    $session->getFlashBag()->add('success', "Successfully Logged In"); 
    $cookie = new Symfony\Component\HttpFoundation\Cookie('auth_user_id', (int) $user['id']);
}