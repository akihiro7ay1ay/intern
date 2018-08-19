<?php
$dsn = 'mysql:dbname=co_138_99sv_coco_com;host=localhost';
$user='co-138.99sv-coco';
define(PASS,'S96rYIvx');



$password=$_POST['pass'];
$table_name="tbtest";

$name=$_POST['name'];
$comm=$_POST['comment'];

$edi_num=$_POST['edi_num'];
$edi_row=$_POST['edi_row'];
$edi_bool=$_POST['edi_bool'];
if(empty($edi_row))$edi_bool=false;	//intialized

$del_num=$_POST['del_num'];

function check_password($password)
{
	$bool=false;
	if(empty($password)) {echo 'パスワードが入力されていません<br/>';}
	else if($password===PASS) {$bool=true;}
	return $bool;
}

try
{
	if(!empty($password))
	{
		$pdo=new PDO
		(
			$dsn, $user, $password,
			array(
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        			PDO::ATTR_EMULATE_PREPARES => false//動的プレースホルダ使用禁止
			     )
		);
	}


//edition
	if($edi_bool)
	{
		if(check_password($password))
		{
			$sql="UPDATE $table_name SET name='$name', comment='$comm' WHERE id=$edi_row"; 
			$result=$pdo->query($sql);
		}
	}
	if(ctype_digit($edi_num))
	{	
		if(check_password($password))
		{
			$sql="SELECT * FROM $table_name";
			$result=$pdo->query($sql);
			foreach($result as $row)
			{
				if($edi_num==$row['id'])
				{
					$edi_row=$edi_num;
					$edi_bool=true;
					$edi_name=$row['name'];
					$edi_comm=$row['comment'];
				}
			}
		}
	}
	
//delete
	if(ctype_digit($del_num))
	{
		if(check_password($password))
		{
			$sql="DELETE FROM $table_name WHERE id=$del_num";
			$result=$pdo->query($sql);
		}
	}
//register
	if(!empty($name) and !empty($comm) and !$edi_bool)
	{
		if(check_password($password))
		{
			$sql=$pdo -> prepare("INSERT INTO $table_name (name, comment) VALUES (:name, :comment)");
			$sql -> bindParam(':name', $name, PDO::PARAM_STR);
			$sql -> bindParam(':comment', $comm, PDO::PARAM_STR);
			$sql -> execute();
			$date=date("Y/m/d H:i");
			echo "$name, $comm を受け付けました。 $date";
		}
	}

}
catch(PDOException $e)
{
	echo 'パスワードが間違っています<br/>';
	$error=$e->getMessage();
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="Shift-JIS">
		<title>
		</title>
	</head>
	<body>
		<form action="mission_4.php" method="post">
			投稿者名前　 <input type="text" name="name" value = <?php  echo $edi_name;?>><br/>
			コメント　　 <input type="text" name="comment" value = <?php echo $edi_comm;?>><br/>
			<br/>
			削除対象番号 <input type="text" name="del_num" value=""><br/>
			編集対象番号 <input type="text" name="edi_num" value=""><br/>
			<br/>
			パスワード　 <input type="text" name="pass" placeholder="パスワードは必須です"><br/>

				     <input type="hidden" name="edi_row" value=<?php echo $edi_row;?>>
				     <input type="hidden" name="edi_bool" value=<?php echo $edi_bool;?>>
			<input type="submit" value="送信">
		</form>
	</body>
</html>

<?php
	$pdo=new PDO($dsn,$user,'S96rYIvx');
	//データベースの削除
	//$sql='DELETE FROM'.$table_name;
	//$result=$pdo->query($sql);
	
	//データベースの作成
	/*
	$sql="CREATE TABLE tbtest"
	."("
	."id INT,"
	."name char(32),"
	."comment TEXT"
	.")";
	$result=$pdo->query($sql);
	*/
	
	//データベースの要素追加
	//$sql ="INSERT INTO $table_name VALUES('1', 'taima', 'test')";
	
	//データベースの要素削除
	//$sql="DELETE FROM $table_name where id=3";
	//$result = $pdo -> query($sql);
	
	//型を変更、オートインクリメントに
	//$sql="ALTER TABLE tbtest CHANGE id id INT AUTO_INCREMENT PRIMARY KEY";
	//$result=$pdo->query($sql);
	
	//表示
	$sql="SELECT * FROM $table_name";
	$result=$pdo->query($sql);
	echo 'データベース内部<br/>';
	echo 'id |'.'name |'.'   comment   '.'<br>';
	$db_count=0;
	foreach ($result as $row)
	{
		/*
		print_r($row);
		echo '<br/>';
		*/
		echo $row['id'].' |';
		echo $row['name'].'|';
		echo $row['comment'].'<br>';
		$db_count++;
	}
	
	//投稿番号リセット
	if(!$db_count)
	{
		$sql="ALTER TABLE $table_name CHANGE id id INT  AUTO_INCREMENT PRIMARY KEY";
		$result=$pdo->query($sql);
	}
?>
