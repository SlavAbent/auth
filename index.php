<?php
	session_start();
	include_once('function.php');
	$db = connect_db();


?>
<!doctype html>
<html>
<head>
    <title>Профили пользователей</title>
</head>
<body>
    <?php

		$sql = "SELECT * FROM article ORDER BY dt_post DESC";
		$query = $db->prepare($sql);
		$query->execute();

		if($query->errorCode() != PDO::ERR_NONE){//блок поиска ошибок
			$info = $query->errorInfo();
			echo "Ошибка!<br>";
			file_put_contents("error.log", $info, FILE_APPEND);			
			exit();
		}

		$news = $query->fetchAll();

		if(empty($news)){
			echo "Профилей нет!<br>";
		}else{
			foreach($news as $one){ ?>			
				<a href="article.php?f=<?php echo $one['id_post'];?>"><?php echo $one['title'];?></a>	
				<hr>
		<? }
		}

    	if(is_auth()){
			$_SESSION['auth'] = true;
			echo '<a href="add.php">Добавить профиль пользователя</a><br><a href="login.php">Выход</a>';			
		}else{
			echo '<a href="login.php">Авторизоваться</a>';	
		}
	
	?>
	
</body>
</html>	