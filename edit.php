<?php

	session_start();
	include_once('function.php');
	$db = connect_db();
	
	if(is_auth()){	
		$fname = $_GET['f'];
		if(count($fname) > 0){			 			

			//Производтм запрос в БД с целью проверить на существование
			//запрошеной статьи
			$sql = "SELECT * FROM article WHERE id_post=$fname";
			$query = $db->prepare($sql);
			$query->execute();

			//блок поиска ошибок при запросе к БД
			if($query->errorCode() != PDO::ERR_NONE){
				$info = $query->errorInfo();
				echo "Ошибка!<br>";
				file_put_contents("error.log", $info, FILE_APPEND);			
				exit();
			}

			$post = $query->fetch();

			//Запрошеной статьи нет. MySQL вернула пустой результат (т.е. ноль строк)
			if($post == false){
				header("Location: index.php");
				exit();
			}		

		}else{	
			header("Location: login.php");
			exit();		
		}

		//Блок редактирования статьи
		if(count($_POST) > 0){			
			$title = trim(htmlspecialchars($_POST['title']));
			$content = trim(htmlspecialchars($_POST['content']));

			//Условия если нужно удалить статью
			$delete = $_POST['delete'];
			if($delete){
				header("Location: delete.php?f=" . $post['id_post']);
				exit();
			}

			$answer = '';
			if($title == '' || $content == ''){
				$answer = "Заполните все поля";
			}elseif(iconv_strlen($title,'UTF-8') < 8){
				$answer = "Имя заголовка статьи слишком короткое";
			}else{						
				$sql = "UPDATE article SET title=:title, content=:content WHERE id_post=$fname";				
				$query = $db->prepare($sql);				
				$params = ['title' => $title, 'content' => $content];
				$query->execute($params);

				if($query->errorCode() != PDO::ERR_NONE){
					$info = $query->errorInfo();
					echo "Ошибка!<br>";
					file_put_contents("error.log", $info, FILE_APPEND);
					echo '<a href="add.php">Назад</a>';
					exit();			
				}		

				header("Location: index.php");
				exit();
			}			
		}
		
	}else{	
		header("Location: login.php");
		exit();		
	}	
    
?>
<!doctype html>
<html>
<head>
    <title>Редактирование профиля</title>
</head>
<body>
	<form method="post">
		Название файла<br>
		<input type="text" size="40" name="title" value="<?=$post['title']?>"><br>
		Содержимое файла<br>
		<input size="40" name="content"><?=$post['content']?></input><br>
		<input type="submit" value="Сохранить">
		<input type="submit" name="delete" value="Удалить">
	</form>

	<?=$answer?><br>	
	<a href="index.php">Назад</a><br>
</body>
</html>	