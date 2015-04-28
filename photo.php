<?php
	class photo {
		private $photo_id = "";
		private $name = "";
		private $image_url = "";
		private $created_time = "";
		private $num_of_likes = "";

		public function getPhotoId() {
			return $this->photo_id;
		}
		public function setPhotoId($photo_id) {
			$this->photo_id = $photo_id;
		}

		public function getName() {
			return $this->name;
		}
		public function setName($name) {
			$this->name = $name;
		}

		public function getUrl() {
			return $this->image_url;
		}
		public function setUrl($image_url) {
			$this->image_url = $image_url;
		}

		public function getCreateTime() {
			return $this->created_time;
		}
		public function setCreatedTime($created_time) {
			$this->created_time = $created_time;
		}

		public function getLikes() {
			return $this->num_of_likes;
		}
		public function setLikes($num_of_likes) {
			$this->num_of_likes = $num_of_likes;
		}

	}
?>