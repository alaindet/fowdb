<div class="row">
  <div class="col-xs-12 col-sm-offset-4 col-sm-4">
    <div class="panel panel-default text-center">

      <div class="panel-heading">
        <h3 class="panel-title">Sign In</h3>
      </div>

      <div class="panel-body">
        <form
          action="<?=url('login')?>"
          method="post"
          role="form"
        >
          <?=csrf_token()?>
              
          <fieldset>
              
            <!-- Username -->
            <div class="form-group">
              <input
                type="text"
                name="username"
                class="form-control input-lg"
                placeholder="Username"
                autofocus
                required
              >
            </div>

            <!-- Password -->
            <div class="form-group">
              <input
                type="password"
                name="password"
                class="form-control input-lg"
                placeholder="Password"
                required
              >
            </div>

            <?php /*
            <div class="checkbox">
              <label>
                <input
                  type="checkbox"
                  name="remember"
                  value="1"
                >
                  Remember Me
              </label>
            </div>
            */ ?>

              <button
                type="submit"
                class="btn btn-lg btn-primary"
              >
                <i class="fa fa-sign-in"></i>
                Sign In
              </button>

          </fieldset>
        </form>
      </div>
    </div>
  </div>
</div>
