<?
    require_once 'AdminLogin.php';

    $loginController = new AdminLogin();
    CacheCountry::setCacheBehavior(SessionAdmin::getCacheBehavior());
    CacheCountry::destroyCache();
    $loginController->logout(SessionAdmin::getLoggedUser());
   
?>