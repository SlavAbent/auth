<?php
//валидация строк
//защита от SQL-иньекции

	session_start();
	include_once('function.php');
	$db = connect_db();
	
	if(!is_auth()){
		header('Location: index.php');
		exit();	
    }    
        
    if(count($_POST) > 0){

        $title = trim(htmlspecialchars($_POST['title']));
        $content = trim(htmlspecialchars($_POST['content']));

        //Серка на унильность заголовка статьи в базе
        $sql = "SELECT title FROM article";
        $query = $db->prepare($sql);
        $query->execute();			
        if($query->errorCode() != PDO::ERR_NONE){
            $info = $query->errorInfo();
            echo "Ошибка!<br>";
            file_put_contents("error.log", $info, FILE_APPEND);
            echo '<a href="add.php">Назад</a>';
            exit();			
        }			
        $detected = false;
        $uniqTitle = $query->fetchAll();
        foreach($uniqTitle as $id_dom => $attrs){
            if(in_array($title, $attrs)) $detected = true;				
        }//Конец кода для сверки на уникальность заголовка
        
        if($title == '' || $content == ''){
            $answer = "Заполните все поля";
        }elseif(iconv_strlen($title,'UTF-8') < 8){
            $answer = "Имя заголовка статьи слишком короткое";
        }elseif($detected){
            $answer = "Имя такого заголовка уже существует!";
        }else{			
            
            $sql = "INSERT INTO article(title, content) VALUES (:title, :content)";
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
    }else{
        $title = '';
        $content = '';
        $answer = "Заполните все поля";
    }					

?>
<!doctype html>
<html>
<head>
    <title>Добавление профиля</title>
</head>
<body>
	<form method="post">
		Как вас зовут?<br>
		<input size="40" type="text" name="title" value="<?=$title?>"><br>
		Пароль<br>
		<input type="password"  size="40" name="content"><?=$content?></input><br>
		<input type="submit" value="Сохранить"><br>
	</form>
	<?=$answer?><br>
	<a href="index.php">Назад</a>
</body>
</html>	