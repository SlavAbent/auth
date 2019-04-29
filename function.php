<? 
	function is_auth(){
		if(isset($_SESSION['auth']) || ($_COOKIE['login'] == hash('ripemd128', 'admin') && $_COOKIE['password'] == hash('ripemd128', 'qwerty'))):
			$_SESSION['auth'] = true;
			return true;		
		else:
			return false;	
		endif;
	}

	function connect_db(){
		$db = new PDO('mysql:host=localhost;dbname=tested','root', '');
		$db->exec('SET NAMES UTF8');
		$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		return $db;
	}
 ?>