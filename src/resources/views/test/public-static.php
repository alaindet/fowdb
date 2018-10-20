<div class="page-header">
  <h1>Public dummy form</h1>
</div>

<form method="post" action="/test/public/static">
  <?=csrf_token();?>
  <label for="name">
    Your name here:
    <input type="text" id="name" name="name" />
  </label>
</form>
