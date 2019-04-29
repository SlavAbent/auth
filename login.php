<?php
	session_start();

	if(count($_POST) > 0){
        
        if($_POST['login'] == 'mastergnom' && $_POST['password'] == 'ilovestones'){						
            $_SESSION['auth'] = true;
			if(isset($_POST['remember'])){
				setcookie('login', hash('ripemd128', 'mastergnom'), time() + 86400);
				setcookie('password', hash('ripemd128', 'ilovestones'), time() + 86400);
			}
            header('Location: index.php');
            exit();
        }
    }
    else{
        unset($_SESSION['auth']);
		setcookie('login', hash('ripemd128', 'mastergnom'), time() - 1);
		setcookie('password', hash('ripemd128', 'ilovestones'), time()  - 1);
    }
?>

<!doctype html>
<html>
<head>
    <title>Авторизация</title>
</head>
<body>

<form method="post">
	Логин<br>
	<input type="text" name="login"><br>
	Пароль<br>
	<input type="text" name="password"><br>
	<input type="checkbox" name="remember">Запомнить меня
	<input type="submit" value="Войти">
</form>


</body>
</html>	