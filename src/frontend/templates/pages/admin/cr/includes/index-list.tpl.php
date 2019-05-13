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
      <th>Created on</th>
      <th>Valid from</th>
      <th>Version</th>
    </thead>

    <tbody>
      <?php foreach ($items as $item): ?>
        <tr>

          <!-- ID ========================================================= -->
          <td><?=$item['id']?></td>
          
          <!-- Actions ==================================================== -->
          <td>

            <!-- Update -->
            <a
              class="btn btn-xs fd-btn-default fd-btn-warning-hover"
              href="<?=fd_url('cr/update/'.$item['id'])?>"
            >
              <i class="fa fa-pencil"></i>
              Update
            </a>

            <!-- Delete -->
            <a
              class="btn btn-xs fd-btn-default fd-btn-danger-hover"
              href="<?=fd_url('cr/delete/'.$item['id'])?>"
            >
              <i class="fa fa-trash"></i>
              Delete
            </a>

            <!-- Download source -->
            <a
              class="btn btn-xs fd-btn-default fd-btn-danger-hover"
              href="<?=fd_url('cr/file/'.$item['id'])?>"
            >
              <i class="fa fa-download"></i>
              Source
            </a>

          </td>

          <!-- Created on ================================================= -->
          <td><?=$item['date_created']?></td>

          <!-- Valid from ================================================= -->
          <td><?=$item['date_validity']?></td>

          <!-- Version ==================================================== -->
          <td>
            <a
              href="<?=fd_url('cr/'.$item['version'])?>"
              class="btn btn-xs fd-btn-default"
              target="_blank"
            >
              <i class="fa fa-external-link"></i>
            </a>
            <?=$item['version']?>
          </td>

        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
