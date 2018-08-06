<?php
    // Load password_* polyfills
    require $_SERVER['DOCUMENT_ROOT'] . "/vendor/ircmaxell/password-compat/lib/password.php";

	// Generate an anti-CSRF token if one doesn't exist
	if (!isset($_SESSION['token'])) {
		$_SESSION['token'] = sha1(uniqid(mt_rand(), true));
	}

	// Check for anti-CSRF token
	if (isset($_POST['token']) AND $_POST['token'] == $_SESSION['token']) {
	    
	    // Genereate BCRYPT hash from escaped string
	    $hashed = password_hash(htmlentities($_POST['toHash'], ENT_QUOTES, 'UTF-8'), PASSWORD_DEFAULT);
	}
?>

<h1>Hash a string!</h1>

<?php if(isset($_POST['action']) AND $_POST['action'] == 'hash_it'): /* SHOW HASHED STRING */ ?>
	
	<!-- Original string -->
	<h2>Original string</h2>
	<h3 style="font-family:monospace"><?=$_POST['toHash']?></h3>
	
	<!-- Hashed string -->
	<h2>Hashed string</h2>
	<h3 style="font-family:monospace"><?=$hashed?></h3>
		
	<!-- Hash again -->
	<form action="/?p=freehash" method="post" class="form form-inline">
		<input type="hidden" name="toHash" value="<?=$_POST['toHash']?>" class="form-control" />
		<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
		<input type="hidden" name="action" value="hash_it" class="form-control" />
		<input type="submit" name="submit" value="Hash Again" class="btn btn-primary btn-lg" />
	</form>
	<hr>
	
	<!-- Hash another string -->
	<a href="/index.php?p=freehash">
		<button type="button" class="btn btn-primary btn-lg">Hash another string</button>
	</a>
	
<?php else: /* GENERATE HASH */?>
    <form action="" method="post" class="form form-inline">
    	<fieldset>
    		<legend>Generate hash</legend>
    		<label for="password">
    			To hash
    			<input type="text" name="toHash" value="" autofocus class="form-control" />
    		</label>
    		<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
    		<input type="hidden" name="action" value="hash_it" class="form-control" />
    		<input type="submit" name="submit" value="Hash It" class="form-control" />
    	</fieldset>
    </form>
<?php endif; ?>