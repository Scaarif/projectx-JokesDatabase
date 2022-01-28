<form action="" method="post">
	<input type="hidden" name="category[id]" value="<?=$category->id ?? '' ?>">
	<label for="categoryname">Enter category name:</label>
	<input type="text" name="category[name]" id="categoryname" value="<?=$category->name ?? '' ?>"/>
	<input type="submit" name="submit" value="Save">
</form>