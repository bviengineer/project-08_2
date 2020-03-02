<?php
// Verifies whether a user is authenticated 
function isAuthenticated() {
    // global $session;
    // return $session->get('auth_logged_in', false);
    return decodeAuthCookie('auth_logged_in');
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
// Returns the logged in user
function getAuthenticatedUser() {
    // global $session;
    // return findUserByUserId($session->get('auth_user_id'));
    return findUserByUserId(decodeAuthCookie('auth_user_id'));
}

// Save user session data
function saveUserData($user) {
    global $session;
    $session->set('auth_logged_in', true);
    $session->set('auth_user_id', (int) $user['id']);
    
    $session->getFlashBag()->add('success', "Successfully Logged In"); 
    $data = ['auth_user_id' => (int) $user['id'] ];
    $expTime = time() + 3600;
    $cookie = setAuthCookie(json_encode($data), $expTime);
    // $cookie = new Symfony\Component\HttpFoundation\Cookie(
    //     'auth_user_id', 
    //     (int) $user['id']
    // );
    redirect('/', ['cookies' => [$cookie] ]);
}
// Setting of cookie
function setAuthCookie($data, $expTime) {
    $cookie = new Symfony\Component\HttpFoundation\Cookie(
        'auth', 
        json_encode($data),
        $expTime,
        '/',
        'localhost',
        false,
        true
    );
    return $cookie;
}
// Decoding of cookie
function decodeAuthCookie($prop = null) {
    $cookie = json_decode(request()->cookies->get('auth'));
    if ($prop === null) {
        return $cookie;
    }
    if (!isset($cookie->prop)) {
        return false;
    }
    return $cookie->prop;
}