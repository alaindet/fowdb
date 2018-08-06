<?php if (admin_level() == 0): // Login ?>
	<form action="" method="post" class="form form-inline">
		<fieldset>
			<legend>Admin login</legend>
			
			<?php if (isset($wrong_password)): // Wrong credentials ?>
				<div><span class="label label-danger">Wrong username or password</span></div><br>
			<?php endif; ?>
			
			<label for="username">
				User&nbsp;<input type="text" name="username" value="" class="form-control">
			</label>
			
			<label for="password">
				Password&nbsp;<input type="password" name="password" value="" class="form-control">
			</label>
			
			<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">			
			<input type="hidden" name="action" value="admin_login" class="form-control">
			<input type="submit" name="login_submit" value="Log In" class="form-control">
		</fieldset>
	</form>
<?php else: // Logout ?>
	<hr>
	<form action="" method="post">
		<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
		<input type="hidden" name="action" value="admin_logout" class="form-control">
		<input type="submit" name="submit" class="btn btn-primary" value="Log out">
	</form>
<?php endif; ?>
