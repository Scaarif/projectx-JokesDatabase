<?php 
if (!empty($errors)) : //i.e if there are errors in the form
	?>
	<div class="errors">
		<p>Your account could not be created, please check the following:</p>
		<ul>
			<?php
			foreach($errors as $error) :
				?>
				<li><?=$error ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
<?php endif; ?>
<form action="" method="post">
	<label for="email">Your email address</label>
	<input type="text" name="author[email]" id="email" value="<?=$author['email'] ?? ''?>">
	<label for="name">Your name</label>
	<input type="text" name="author[name]" id="name" value="<?=$author['name'] ?? ''?>">
	<label for="password">Password</label>
	<input type="password" name="author[password]" id="password" value="<?=$author['password'] ?? ''?>">
	<input type="submit" name="submit" value="Register account">
</form>