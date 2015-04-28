<!DOCTYPE html>
<html>
<head>
	<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
	<script type="text/javascript">
	$(document).ready(function() {
		$('.photo').click(function(event) {
			var targetId = "detail"+$(this).attr('id');
			$('.detail').each(function(index) {
				var id = $(this).attr('id');
				if(id == targetId) {
					$('#'+id).show();
				} 
				else {
					$('#'+id).hide();
				}
			});
		});
	});
	</script>
	<title>Task2</title>
</head>
<body>
	<?php generate_pic_list() ?>
</body>
</html>

<?php 
	function generate_pic_list() {
		echo "<table>";
		
		$result_arr = get_all_pics();

		foreach ($result_arr as $result) {
			$id = $result['id'];
			$photo_id = $result['photo_id'];
			$name = $result['name'];
			$image_url = $result['image_url'];
			$created_time = $result['created_time'];
			$likes = $result['likes'];

			echo "<tr>";
			echo "<td style='cursor:pointer;' class='photo' id='$id'><img src=$image_url></td>";
			echo "<td style='display:none;' class='detail' id='detail$id'><ul><li>Photo id: $photo_id</li><li>Name: $name</li><li>Image_url: $image_url</li><li>Created time: $created_time</li><li>Likes: $likes</li></td>";
			echo "</tr>";
		}
		echo "</table>";
	}

	function get_all_pics() {
		$servername = "127.0.0.1";
		$username = "root";
		$password = "";

		try {
			$conn = new PDO("mysql:host=$servername;dbname=photodb", $username, $password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$sql = "select * from photos";
			$statement = $conn->prepare($sql);
			$statement->execute();

			$result_arr = $statement->fetchAll(PDO::FETCH_ASSOC);

		}
		catch(PDOException $e) {
			echo $e->getMessage(); 
		}
		$conn = null;
		return $result_arr;
	}
?>