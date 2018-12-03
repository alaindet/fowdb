<?php

// VARIABLES
// $items

$decks = \App\Models\PlayRestriction::$decksLabels;

?>
<div class="table-responsive">
  <table class="table table-striped table-condensed">
    
    <!-- Headings -->
    <thead>
      <th>#</th>
      <th>Actions</th>
      <th>Card</th>
      <th>Format</th>
      <th>Deck</th>
      <th>Copies</th>
    </thead>

    <tbody>
      <?php foreach ($items as $item): ?>
        <tr>

          <!-- # -->
          <td><?=$item['id']?></td>
          
          <!-- Actions -->
          <td>

            <!-- Update -->
            <a
              class="btn btn-xs fd-btn-default fd-btn-warning-hover"
              href="<?=url('restrictions/update/'.$item['id'])?>"
            >
              <i class="fa fa-pencil"></i>
              Update
            </a>

            <!-- Delete -->
            <a
              class="btn btn-xs fd-btn-default fd-btn-danger-hover"
              href="<?=url('restrictions/delete/'.$item['id'])?>"
            >
              <i class="fa fa-trash"></i>
              Delete
            </a>

          </td>

          <!-- Card -->
          <td>
            <a
              href="<?=url_old('card', [
                'code' => urlencode($item['card_code'])
              ])?>"
              class="btn btn-xs fd-btn-default"
              target="_blank"
            >
              <i class="fa fa-external-link"></i>
            </a>
            <a
              href="<?=url('restrictions/manage', ['card' => $item['card_id']])?>"
              class="link-as-text"
            >
              <span class="text-muted">(<?=$item['card_code']?>)</span>
              <?=$item['card_name']?>
            </a>
          </td>

          <!-- Format -->
          <td>
            <a
              href="<?=url('restrictions/manage', ['format' => $item['format_id']])?>"
              class="link-as-text"
            >
              <?=$item['format_name']?>
            </a>
          </td>

          <!-- Deck -->
          <td>
            <a
              href="<?=url('restrictions/manage', ['deck' => $item['deck']])?>"
              class="link-as-text"
            >
              <?=$decks[$item['deck']]?>
            </a>
          </td>

          <!-- Copies -->
          <td>
            <a
              href="<?=url('restrictions/manage', ['copies' => $item['copies']])?>"
              class="link-as-text"
            >
              <?=$item['copies']?>
            </a>
          </td>

        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
