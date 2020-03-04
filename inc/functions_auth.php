<?php
// Verifies whether a user is authenticated 
function isAuthenticated() {
    return decodeAuthCookie();
}
// Require a user to authenticate in order to use certain resources
function requireAuth() {
    if (!isAuthenticated()) {
        global $session;
        $session->getFlashBag()->add('error', 'You must be logged in to access this resource');
        redirect("/login.php");
    }
}
// Returns the logged in user
function getAuthenticatedUser() {
    return findUserByUserId(decodeAuthCookie('auth_user_id'));
}
// Save user session data
function saveUserData($user) {
    global $session;
    $session->getFlashBag()->add('success', "Successfully Logged In");
    $expTime = time() + 3600;
    $jwt = Firebase\JWT\JWT::encode([
        'iss' => request()->getBaseUrl(),
        'sub' => (int) $user['id'],
        'exp' => $expTime,
        'iat' => time(),
        'nbf' => time(),
    ], getenv('SECRET_KEY'), 'HS256'); 
    $cookie = setAuthCookie($jwt, $expTime);
    redirect('/', ['cookies' => [$cookie] ]);
}
// Setting of cookie
function setAuthCookie($data, $expTime) {
    $cookie = new Symfony\Component\HttpFoundation\Cookie(
        'auth', 
        $data,
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
    try {
        Firebase\JWT\JWT::$leeway=1;
        $cookie = Firebase\JWT\JWT::decode(
            request()->cookies->get('auth'),
            getenv('SECRET_KEY'),
            ['HS256']
        );
    } catch (Exception $e) {
        return false;
    }
    if ($prop === null) {
        return $cookie;
    }
    if ($prop == 'auth_user_id') {
        $prop = 'sub';
    }
    if (!isset($cookie->$prop)) {
        return false;
    }
    return $cookie->$prop;
}