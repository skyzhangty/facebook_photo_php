<?php
	require_once('photo.php');

	$url = 'https://graph.facebook.com/vanderbilt/photos/uploaded';
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
			
			#create table
			$sql = "create table if not exists photos (
				id int(6) unsigned auto_increment primary key,
				photo_id varchar(300) not null,
				name varchar(1000) not null,
				image_url varchar(1000) not null,
				created_time varchar(100) not null,
				likes int(10) not null,
				unique index (`photo_id`))";
			$conn->exec($sql);

			#insert photos
			$sql = "insert into photos (photo_id, name, image_url, created_time, likes)
					values (:photo_id, :name, :image_url, :created_time, :num_of_likes) 
					on duplicate key update name=values(name), image_url=values(image_url), 
					created_time=values(created_time), likes=values(likes)";
			$stmt = $conn->prepare($sql);
			$stmt->bindParam(':photo_id', $photo_id);
			$stmt->bindParam(':name', $name);
			$stmt->bindParam(':image_url', $image_url);
			$stmt->bindParam(':created_time', $created_time);
			$stmt->bindParam(':num_of_likes', $num_of_likes);
			
			foreach ($photo_arr as $photo) {
				$photo_id = $photo->getPhotoId();
				$name = $photo->getName();
				$image_url = $photo->getUrl();
				$created_time = $photo->getCreateTime();
				$num_of_likes = $photo->getLikes();

				$stmt->execute();
			}

		}
		catch(PDOException $e) {
			echo $sql . $e->getMessage(); 
		}
		$conn = null;
	}

	
?>
	