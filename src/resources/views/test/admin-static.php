<div class="page-header">
  <h1>Admin dummy form</h1>
</div>

<form method="post" action="/test/admin/static">
  <?=csrf_token()?>
  <label for="name">
    Your name here:
    <input type="text" id="name" name="name" />
  </label>
</form>
