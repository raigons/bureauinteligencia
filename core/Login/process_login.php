<div>
    Aguarde...
</div>
<?    
    require_once 'core/Login/LoginController.php';
    require_once 'core/User/UserDao.php';
    //require_once 'core/User/User.php';    

    $loginController = new LoginController(new UserDao(Connection::connect()));
    try{
        if($loginController->login($_POST['username'], $_POST['password'])){
            if(isset($_POST['redirect_to_datacenter']) && $_POST['redirect_to_datacenter'] == true)                
                header("Location: ".Config::get('baseurl') . 'estatisticas_cafeeiras');
            else
                header("Location: ".Config::get('baseurl'));
        }else{
            header("Location: ".Config::get('baseurl').'index?login-fail=true');
        }
    }catch(PDOException $err){
        die($err->getMessage());
    }
?>