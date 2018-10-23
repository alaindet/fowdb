<?php if (admin_level() == 0): // Login ?>
	<form
		action="/admin/login/process.php"
		method="post"
		class="form form-inline"
	>
		<?=csrf_token()?>

    <input
      type="hidden"
      name="action"
      value="admin_login"
      class="form-control"
    />

		<fieldset>
			<legend>Admin login</legend>
			
			<label for="username">
				User
				<input
					type="text"
					name="username"
					class="form-control"
				/>
			</label>
			
			<label for="password">
				Password
				<input
					type="password"
					name="password"
					class="form-control"
				/>
			</label>

			<button
        type="submit"
        name="login_submit"
        class="btn btn-primary"
      >
        <i class="fa fa-sign-in"></i>
        Sign in
      </button>

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
