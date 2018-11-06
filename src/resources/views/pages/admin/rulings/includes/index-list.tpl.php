<?php
// VARIABLES
// $rulings
?>

<div class="table-responsive">
  <table class="table table-striped table-condensed">
    
    <!-- Headings -->
    <thead>
      <th>#</th>
      <th>Actions</th>
      <th>Card</th>
      <th>Errata</th>
      <th>Date</th>
      <th>Ruling</th>
    </thead>

    <tbody>
      <?php foreach ($rulings as $item): ?>
        <tr>

          <!-- # -->
          <td>
            <?=$item['ruling_id']?>
          </td>
          
          <!-- Actions -->
          <td>

            <!-- Edit -->
            <a
              class="btn btn-xs fd-btn-default fd-btn-warning-hover"
              href="<?=url('rulings/update/'.$item['ruling_id'])?>"
            >
              <i class="fa fa-pencil"></i>
              Edit
            </a>

            <!-- Edit -->
            <a
              class="btn btn-xs fd-btn-default fd-btn-danger-hover"
              href="<?=url('rulings/delete/'.$item['ruling_id'])?>"
            >
              <i class="fa fa-trash"></i>
              Delete
            </a>

          </td>

          <!-- Card -->
          <td>
            <a href="<?=url_old('card', ['code' => urlencode($item['card_code'])])?>">
              <span class="text-muted">(<?=$item['card_code']?>)</span>
              <?=$item['card_name']?>
            </a>
          </td>

          <!-- Errata -->
          <td>
            <?=$item['ruling_is_errata']
              ? '<span class="text-danger">YES</span>'
              : 'NO'  
            ?>
          </td>

          <!-- Ruling date -->
          <td>
            <?=$item['ruling_date']?>
          </td>

          <!-- Ruling text -->
          <td>
            <?=render(substr($item['ruling_text'], 0, 30).'[...]')?>
          </td>

        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
