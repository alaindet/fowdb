<?php

// VARIABLES
// $items

?>

<div class="table-responsive">
  <table class="table table-striped table-condensed">
    
    <!-- Headings -->
    <thead>
      <th>#</th>
      <th>Actions</th>
      <th>Name</th>
      <th>Code</th>
    </thead>

    <tbody class="js-clusters-table">
      <?php foreach ($items as $item): ?>
        <tr>

          <!-- ID -->
          <td><?=$item['id']?></td>

          <!-- Actions -->
          <td>

            <!-- Update -->
            <button
              type="button"
              class="
                btn
                btn-xs
                fd-btn-default
                fd-btn-warning-hover
                js-open-modal
              "
              data-fd-action="update"
              data-fd-id="<?=$item['id']?>"
            >
              <i class="fa fa-pencil"></i>
              Update
            </button>

            <!-- Delete -->
            <button
              type="button"
              class="
                btn
                btn-xs
                fd-btn-default
                fd-btn-danger-hover
                js-open-modal
              "
              data-fd-action="delete"
              data-fd-id="<?=$item['id']?>"
            >
              <i class="fa fa-trash"></i>
              Delete
            </button>

          </td>

          <!-- Name -->
          <td><?=$item['name']?></td>

          <!-- Code -->
          <td><?=$item['code']?></td>

        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
