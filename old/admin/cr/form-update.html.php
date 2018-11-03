<h2>Update CR</h2>
<hr>

<form
    action="<?=url_old('admin/cr/action')?>"
    method="post"
    enctype="multipart/form-data"
    class="form-horizontal">

    <!-- Action, token, ID and old-verion -->
    <input type="hidden" name="admin-cr-action" value="update">
    <input type="hidden" name="token" value="<?=$_SESSION['token']?>">
    <input type="hidden" name="id" value="<?=$cr['id']?>">
    <input type="hidden" name="old-version" value="<?=$cr['version']?>">


    <!-- Uploader -->
    <div class="form-group form-section">
        <label class="col-sm-2">
            TXT File
        </label>
        <div class="col-sm-10">
            <a
              href="<?=url_old('admin/cr/raw', ['v' => $cr['version']])?>"
              target="_blank"
              class="btn btn-info btn-xs"
            >
              <i class="fa fa-external-link"></i>
              View
            </a>
            <span class="text-info">(Load a new file to overwrite the existing)</span>
            <br><br>
            <input type="file" name="crfile">
        </div>
    </div>
    
    <!-- Version -->
    <div class="form-group form-section">
        <label class="col-sm-2">Version</label>
        <div class="col-sm-10">
            <input type="text" name="version" placeholder="Ex.: 6.3a" class="form-control" value="<?=$cr['version']?>">
        </div>
    </div>
    
    <!-- Validity -->
    <div class="form-group form-section">
        <label class="col-sm-2">
            Legal from<br>
            (<strong>yyyy-mm-dd</strong>)
        </label>
        <div class="col-sm-10">
            <input type="text" name="validity" placeholder="Ex.: 2017-03-19" class="form-control" value="<?=$cr['date_validity']?>">
        </div>
    </div>
    
    <!-- Set as default -->
    <div class="form-group form-section">
        <?php
            $checked = $cr['is_default'] ? " checked=\"true\" " : "";
        ?>
        <label class="col-sm-2">Default</label>
        <div class="col-sm-10">
            <label class="btn btn-default active">
                <input name="set-default"<?=$checked?>type="checkbox">
                Set as new default
            </label>
        </div>
    </div>
    
    <!-- Submit -->
    <div class="form-group">
        <div class="col-sm-10 col-sm-offset-2">
            <button type="submit" class="btn btn-primary btn-lg">
                Update
            </button>
        </div>
    </div>
</form>
