<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" href="jokes.css">
		<title><?=$title?></title>
	</head>
	<body>
		<header>
			<h1>Internet Joke  Database</h1>
		</header>
		<nav>
			<ul>
				<li><a href="index.php">Home</a></li>
				<li><a href="index.php?route=joke/list">Jokes List</a></li>
				<li><a href="index.php?route=joke/edit">Add a new Joke</a></li> <!--this was initially addjoke.php-->
				<!--Adding a log in/out link -->
				<?php if ($loggedIn): ?>
					<li><a href="index.php?route=logout">Log Out</a></li>
				<?php else: ?>
					<li><a href="index.php?route=login">Log In</a></li>
				<?php endif; ?>
			</ul>
		<main>
			<?=$output?>
		</main>
		<footer>
			&copy; IJDB <?=date('Y')?>. All Rights Reserved.
		</footer>
	</body>
</html>