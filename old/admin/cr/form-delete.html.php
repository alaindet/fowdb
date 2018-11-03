<h2>Delete CR</h2>
<hr>

<form
	action="<?=url_old('admin/cr/action')?>"
    method="post"
	enctype="multipart/form-data"
	class="form-horizontal">

    <!-- Action, token and ID -->
    <input type="hidden" name="admin-cr-action" value="delete">
    <input type="hidden" name="token" value="<?=$_SESSION['token']?>">
    <input type="hidden" name="id" value="<?=$cr['id']?>">
    <input type="hidden" name="version" value="<?=$cr['version']?>">
    
    <?php
        // Translate labels
        $translate = [
            "id" => "ID",
            "is_default" => "Is default?",
            "date_inserted" => "Creation date",
            "date_validity" => "Legal from",
            "version" => "Version",
        ];
        // Little adjustments
        unset($cr['path']);
        $cr['is_default'] = $cr['is_default'] ? "Yes" : "No";
        foreach ($cr as $label => $value):
    ?>
        <div class="form-group">
            <label class="col-sm-2"><?=$translate[$label]?></label>
            <div class="col-sm-10"><?=$value?></div>
        </div>
    <?php endforeach; ?>
    
    <!-- Submit -->
    <div class="form-group">
        <div class="col-xs-12">
            <button type="submit" class="btn btn-danger btn-lg">
                Delete
            </button>
        </div>
    </div>
</form>
