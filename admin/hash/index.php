<?php if (admin_level() == 1): /*Admin level 1*/ ?>
	<?php
		// Generate an anti-CSRF token if one doesn't exist
		if (!isset($_SESSION['token'])) {
			$_SESSION['token'] = sha1(uniqid(mt_rand(), true));
		}

		// Check for anti-CSRF token
		if (isset($_POST['token']) AND $_POST['token'] == $_SESSION['token']) {
			// Generate hash
			$hash = password_hash($_POST['toHash'], PASSWORD_BCRYPT);
		}

	?>

	<?php if(isset($_POST['action']) AND $_POST['action'] == 'hash_it'): /*Display hash*/ ?>
		<!-- Original string -->
		<h4>Original string</h4>
		<pre style="white-space:pre-wrap;"><?=$_POST['toHash']?></pre>
		
		<!-- Hashed -->
		<h4>Hashed</h4>
		<pre style="white-space:pre-wrap;"><?=$hash?></pre>
		<br />
		
		<!-- Hash again -->
		<form action="" method="post" class="form form-inline">
			<!-- To be hashed hash -->
			<input type="hidden" name="toHash" value="<?=$_POST['toHash']?>" class="form-control" />
			
			<!-- Token (hidden) -->
			<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
			
			<!-- Action (hidden) -->
			<input type="hidden" name="action" value="hash_it" class="form-control" />
			
			<!-- Submit -->
			<button type="submit" class="btn btn-primary btn-lg">
				Hash Again
			</button>
		</form>
		<hr>
		<!-- Hash another string -->
		<a href="/index.php?p=hash">
			<button type="button" class="btn btn-primary btn-lg">
				Hash another string
			</button>
		</a>
	<?php else: ?>
	<form action="" method="post" class="form form-inline">
		<fieldset>
			<legend>Generate hash</legend>
			
			<!-- To hash -->
			<label for="password">To hash</label>
			<input type="text" name="toHash" value="" autofocus class="form-control" />
			
			<!-- Token (hidden) -->
			<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
			
			<!-- Action (hidden) -->
			<input type="hidden" name="action" value="hash_it" class="form-control" />
			
			<!-- Submit -->
			<input type="submit" name="submit" value="Hash It" class="form-control" />
		</fieldset>
	</form>
	<?php endif; ?>

	<!-- Admin menu link -->
	<hr>
	<a href="/index.php?p=admin">
		<button type="button" class="btn btn-default">
				&larr; Admin menu
		</button>
	</a>
<?php else: ?>
	<div class="well">Please <a href="index.php?p=admin"><strong>login</strong></a></div>
<?php endif; ?>
