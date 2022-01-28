<!--Only display the edit form if adding a new joke or the viewer is the joke author -->
<?php if (empty($joke->id) || $user->id == $joke->authorid || $user->hasPermission(\Ijdb\Entity\Author::EDIT_JOKES)): ?>
<form action="" method="post">
	<input type="hidden" name="joke[id]" value="<?=$joke->id ?? ''?>">
	<label for="joke">Type your joke here:</label>
	<textarea id="joke" name="joke[joke]" rows="3" cols="40">
		<?=$joke->joke ?? ''?>
	</textarea><br><br><br><br>
	<!--Include the categories available for an author to select from -->
	<p>Select categories for this joke:</p>
	<?php foreach ($categories as $category): ?>
		<label><?=$category->name?></label>
		<?php if ($joke && $joke->hasCategory($category->id)): ?>
			<input type="checkbox" checked name="category[]" value="<?=$category->id?>">
		<?php else: ?>
			<input type="checkbox" name="category[]" value="<?=$category->id?>">
		<?php endif; ?>
	<?php endforeach; ?>
	<input type="submit" name="submit" value="Save">
</form>
<?php else: ?>
<p>You may only edit jokes that you posted.</p>
<?php endif; ?>