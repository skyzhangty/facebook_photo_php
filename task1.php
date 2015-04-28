<?php
	require_once('photo.php');

	define("VANDY_ID", "61441666906");
	define("ACCESS_TOKEN", "CAACEdEose0cBADaT9Lls9VwivZCPOIZBfxWjjgvw3jfeJKRpCvBwo4VhJxCRbtUlKI6MsAo2QsHFxwvtlHUt39Bw5OHZBZCNnQGQusZBjTJyU3RRFlWn1GAx9Wrnt8eFthlOuKW1da6m7ZA77J24moNGDhXrDaYnDsaJ28jO72m5Rey24oSfL0bdgGZBuu3AxJmQfAwP7gvr2isALMKZAkGsRtVLb1SZB3TkZD");

	$url = 'https://graph.facebook.com/v2.3/'.VANDY_ID.'/photos/uploaded?access_token='.ACCESS_TOKEN;
	$photo_data_arr = send_http_get_req($url)['data'];

	$photo_arr = array();
	foreach ($photo_data_arr as $photo) {
		$id = $photo['id'];
		$name = $photo['name'];
		$image_url = $photo['source'];
		$created_time = $photo['created_time'];
		$num_of_likes = sizeof($photo['likes']['data']);

		#create photo object array
		$photo = new Photo;
		$photo->setPhotoId($id);
		$photo->setName($name);
		$photo->setUrl($image_url);
		$photo->setCreatedTime($created_time);
		$photo->setLikes($num_of_likes);
		array_push($photo_arr, $photo);
		

	}
	update_database($photo_arr);


	function send_http_get_req($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_POST, false);
		$resp_arr = json_decode(curl_exec($ch), true);

		return $resp_arr;
	}

	function update_database($photo_arr) {
		$servername = "127.0.0.1";
		$username = "root";
		$password = "";

		try {
			$conn = new PDO("mysql:host=$servername;dbname=mysql", $username, $password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			#create db
			$sql = "create database if not exists photodb";
			$conn->exec($sql);
			#use db
			$sql = "use photodb";
			$conn->exec($sql);
			#drop table
			$sql = "drop table if exists photos";
			$conn->exec($sql);
			#create table
			$sql = "create table if not exists photos (
				id int(6) unsigned auto_increment primary key,
				photo_id varchar(300) not null,
				name varchar(1000) not null,
				image_url varchar(1000) not null,
				created_time varchar(100) not null,
				likes int(10) not null)";
			$conn->exec($sql);

			#insert photos
			foreach ($photo_arr as $photo) {
				$photo_id = $photo->getPhotoId();
				$name = $photo->getName();
				$name = str_replace("'", "\'", $name);
				$name = str_replace('"', '\"', $name);
				$image_url = $photo->getUrl();
				$created_time = $photo->getCreateTime();
				$num_of_likes = $photo->getLikes();

				$sql = "insert into photos (photo_id, name, image_url, created_time, likes)
					values ('$photo_id', '$name', '$image_url', '$created_time', '$num_of_likes')";
				$conn->exec($sql);
			}

		}
		catch(PDOException $e) {
			echo $sql . $e->getMessage(); 
		}
		$conn = null;
	}

	
?>
	