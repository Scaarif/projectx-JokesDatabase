			<?php if (isset($error)): ?>
			<p>
				<?php echo $error; ?>
			</p>
		<?php else: ?>
			<div class="jokelist">
				<ul class="categories">
					<?php foreach ($categories as $category): ?>
						<li><a href="index.php?route=joke/list&category=<?=$category->id?>">
						<?=$category->name?></a></li>
					<?php endforeach; ?>
				</ul>
			<div class="jokes"> 
			<p><?=$totalJokes?> jokes have been submitted to the Internet Joke Database</p>
			<?php foreach ($jokes as $joke): ?>
				<blockquote>

					<!--p -->
						<!--?= htmlspecialchars($joke->joke, ENT_QUOTES, 'UTF-8') ? Replaced with...-->
						<?=(new \Ninja\Markdown($joke->joke))->toHtml() ?> <!--Should properly format the jokes: include emphasis, bold, hyperlinks etc where necessary -->
						(by <a href="mailto:<?= htmlspecialchars($joke->getAuthor()->email, ENT_QUOTES, 'UTF-8');?>"><?=htmlspecialchars($joke->getAuthor()->name, ENT_QUOTES, 'UTF-8'); ?></a> on 
						<?php
						$date = new DateTime($joke->dateadded);
						echo $date->format('jS F Y');
						?>)

						<!--Hide the edit and delete buttons for non-owners/unauthorised users of jokes -->
						<?php if ($user): ?>
						<?php if ($user->id == $joke->authorid || $user->hasPermission(\Ijdb\Entity\Author::EDIT_JOKES)): ?>
						<a href="index.php?route=joke/edit&id=<?=$joke->id?>">Edit</a>
						<?php endif; ?>
						<?php if ($user->id == $joke->authorid || $user->hasPermission(\Ijdb\Entity\Author::DELETE_JOKES)): ?>
						<form action="index.php?route=joke/delete" method="post">
							<input type="hidden" name="id" value="<?=$joke->id?>">
							<input type="submit" name="submit" value="Delete">
						</form>
						<?php endif; ?><!--i.e display the buttons only for the joke authors/authorised individuals-->
					<?php endif; ?>
					<!--/p-->
				</blockquote>
			<?php endforeach; ?>

			Select page:

			<?php 
			//Calculate the number of pages
			$numPages = ceil($totalJokes/10); //divides and rounds off to the next whole no
			//Display a link for each page
			for ($i = 1; $i <= $numPages; $i++):
				if ($i == $currentPage):
			?>
			<a class="currentpage" href="index.php?route=joke/list&page=<?=$i?><?=!empty($categoryid) ? '&category='.$categoryid: '' ?>"><?=$i?></a>
			<?php else: ?>
				<a href="index.php?route=joke/list&page=<?=$i?> <?=!empty($categoryid) ? '&category='.$categoryid: '' ?> "><?=$i?></a>
			<?php endif; ?>
		<?php endfor; ?>
		<?php endif; ?>
		</div>
	</div>