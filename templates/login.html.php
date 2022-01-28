<?php 
if (isset($error)):
	echo '<div class="errors">'.$error.'</div>';
endif;
?>
<form method="post" action="">
	<label for="email">Your Email address:</label>
	<input type="text" name="email" id="email">
	<label for="password">Your password:</label>
	<input type="password" name="password" id="password">
	<input type="submit" name="login" value="Log In">
</form>
<p>Don't have an account? <a href="index.php?route=author/register">Click here to register an account</a></p>