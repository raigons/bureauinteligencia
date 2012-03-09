<?php
/**
 * Description of SessionAdmin
 *
 * @author ramon
 */
class SessionAdmin {
    private static $ADMIN_SESSION = 'bureau_admin_session';

    private static $Admin_Cache_Behavior = "behavior";
    
    private static function setCacheBehavior(){
        $behavior = new SessionBehavior();
        $_SESSION[self::$Admin_Cache_Behavior] = serialize($behavior);
    }
    
    public static function getCacheBehavior(){
        return unserialize($_SESSION[self::$Admin_Cache_Behavior]);
    }
    
    public static function getAdminName(){
        return self::getLoggedUser()->name();
    }

    public static function getAdminUsername(){
        return self::getLoggedUser()->username();
    }

    public static function login(AdminUser $userAdmin){        
        $_SESSION[self::$ADMIN_SESSION] = serialize($userAdmin);        
        self::setCacheBehavior();
    }

    public static function logout(){
        $_SESSION[self::$ADMIN_SESSION] = null;
    }

    public static function isLogged(){
        return !empty ($_SESSION[self::$ADMIN_SESSION]);
    }
    
    /**
     * @return AdminUser
     */
    public static function getLoggedUser(){
       return self::isLogged() ? unserialize($_SESSION[self::$ADMIN_SESSION]) : null;
    }
}
?>
