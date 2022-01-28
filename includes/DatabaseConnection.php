<?php
$pdo = new PDO('mysql:host=localhost;dbname=practice;charset=utf8',
			'ijdbuser', 'MySQL@ITSBEST');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);