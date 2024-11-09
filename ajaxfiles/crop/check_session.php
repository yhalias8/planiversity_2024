<?php
	session_start();

	if (isset($_SESSION['done'])) {
		echo true;
	} else {
		echo false;
	}
?>