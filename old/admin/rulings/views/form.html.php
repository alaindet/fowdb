<?php
  // VARIABLES
  // $title
  // $input
  // $action
  // $ruling (only on edit, delete)

  // INPUT NAMES
  // id       :  Ruling ID
  // card-id  :  Card ID
?>
<div class="page-header">
  <h1><?=$title?></h1>
  <?=breadcrumbs([
    'Admin' => url_old('admin'),
    'Rulings' => url_old('admin/rulings'),
    ucfirst($action) => '#'
  ])?>
</div>

<div class="row">
  <div class="col-xs-12 col-sm-9">
    <form
      action="/admin/rulings/actions.sql.php"
      method="post"
      class="form-horizontal"
      id="ruling-form"
    >

      <!-- Ruling ID -->
      <?php if ($action !== 'create'): ?>
        <input
          type="hidden"
          name="id"
          value="<?=$_REQUEST['id']?>"
        />
      <?php endif; ?>

      <!-- Card ID -->
      <input
        type="hidden"
        name="card-id"
        id="card_id"
        value="<?=$ruling['cards_id']?>"
      />

      <!-- Delete button -->
      <?php if ($action === 'delete'): ?>
        <div class="form-group">
          <div class="col-sm-12">
            <button
              type="submit"
              class="btn btn-danger btn-lg"
            >
              <i class="fa fa-times"></i>
              <?=ucfirst($action)?> Ruling
            </button>
          </div>
        </div>
      <?php endif; ?>

      <!-- Card Name -->
      <?php if ($action === 'create'): ?>

      <div class="form-group">
        <label for="name-suggest" class="col-sm-2 control-label">
          Card
        </label>

      </div>

      <?php else: ?>



      <?php endif; ?>


      <div class="form-group">
        <label for="name_suggest" class="col-sm-2 control-label">
          <?=($action === 'create') ? 'Search Card' : 'Card' ?>
        </label>
        <div class="col-sm-10">

          <?php if ($action === 'create'): ?>

            <input
              type="text"
              class="form-control"
              name="name_suggest"
              id="name_suggest"
              placeholder="Card name here.."
              value="<?=$ruling['name']?>"
            />

          <?php else: ?>

            <input
              type="hidden"
              name="name_suggest"
              value="<?=$ruling['name']?>"
            />

            <p class="help-block">
              <?=$ruling['name']?>
            </p>

          <?php endif; ?>

        </div>
      </div>

      <!-- Card code -->
      <div class="form-group">
        <label for="code" class="col-sm-2 control-label">Code</label>
        <div class="col-sm-10">
          <!-- Code (hidden) -->
          <input type="hidden" name="code" value="<?=$ruling['code']?>" />
          <!-- Code (input) -->
          <input type="text" class="form-control" name="code" id="code" value="<?=$ruling['code']?>" placeholder="Code.."<?=$not?> <?=(in_array($action, ['edit', 'delete'])) ? " disabled=true" : ""?>/>
        </div>
      </div>

      <!-- Card image -->
      <div class="form-group" id="card_image">
        <?php if ($action == 'edit' OR $action == 'delete' OR ($action == 'create' AND isset($_GET['card_id']))): ?>
          <div class="col-sm-offset-2 col-sm-10">
            <a href="<?=$ruling['image_path']?>" data-lightbox='cards' data-title="<?=$ruling['name']?>">
              <img src="<?=$ruling['thumb_path']?>" alt="<?=$ruling['name']?>">
            </a>
          </div>
        <?php endif; ?>
      </div>

      <!-- FLAG: Is errata -->
      <?php $checked = $ruling['is_errata'] ? " checked='true'" : ""; ?>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <div class="checkbox">
            <label>
              <h4>
                <input type="checkbox" name="is_errata" <?=$checked?> value="1"<?=$not?>>
                <span class="label label-danger">It's an errata</span>
              </h4>
            </label>
          </div>
        </div>
      </div>


      <?php if ($action == 'edit'): ?>
        <!-- FLAG: Update date -->
        <div class="form-group">
          <label for="date" class="col-sm-2 control-label">
            Date
            <br>
            <small>YYYY-MM-DD</small>
          </label>
          <div class="col-sm-10">
            <input
              type="text"
              class="form-control"
              name="date"
              placeholder="Date (Ex.: YYYY-MM-DD).."
              value="<?=$ruling['created']?>"
            />
          </div>
        </div>
      <?php endif; ?>


      <?php if (isset($_GET['req'])): ?>
        <!-- FLAG: Delete request -->
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <div class="checkbox">
              <label>
                <h4>
                  <input type="checkbox" name="req_del" value="yes" checked="true" />
                  <span class="label fdb-label-black">Delete request</span>
                </h4>
              </label>
            </div>
          </div>
        </div>

        <!-- This form comes from a ruling request -->
        <input type="hidden" name="req_form" value="yes" />

        <!-- Request ID -->
        <input type="hidden" name="req_id" value="<?=$req['id']?>" />
      <?php endif; ?>

      <!-- Ruling -->
      <div class="form-group">
        <label for="ruling" class="col-sm-2 control-label">Ruling</label>
        <div class="col-sm-10">
          <textarea name="ruling" class="form-control" rows="10" placeholder="Ruling.."<?=$not?>><?php
            if (isset($req['request'])) { echo 'REQUEST___'.$req['request'].'___REQUEST'; }
            else { echo $ruling['ruling']; }
          ?></textarea>
        </div>
      </div>

      <!-- Action -->
      <input type="hidden" name="action" id="action" value="<?=$action?>" />

      <!-- Submit -->
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-primary">
            <?=strtoupper($action[0]).substr($action, 1)." Ruling"?>
          </button>
        </div>
      </div>

    </form>
  </div>
</div>

<?php if ($action !== 'delete'): // Conventions ?>
  <hr>
  <div class="row">
    <div class="col-xs-12">
      <?php require __DIR__ . '/conventions.html.php'; ?>
    </div>
  </div>
<?php endif; ?>
