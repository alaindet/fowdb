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
      <th>Cluster</th>
      <th>Count</th>
      <th>Released in</th>
      <th>Spoiler?</th>
    </thead>

    <tbody>
      <?php foreach ($items as $item): ?>
        <tr>

          <!-- # -->
          <td><?=$item['set_id']?></td>
          
          <!-- Actions -->
          <td>

            <!-- Update -->
            <a
              class="btn btn-xs fd-btn-default fd-btn-warning-hover"
              href="<?=fd_url('sets/update/'.$item['set_id'])?>"
            >
              <i class="fa fa-pencil"></i>
              Update
            </a>

            <!-- Delete -->
            <a
              class="btn btn-xs fd-btn-default fd-btn-danger-hover"
              href="<?=fd_url('sets/delete/'.$item['set_id'])?>"
            >
              <i class="fa fa-trash"></i>
              Delete
            </a>

          </td>

          <!-- Name -->
          <td><?=$item['set_name']?></td>

          <!-- Code -->
          <td><?=$item['set_code']?></td>

          <!-- Cluster -->
          <td>
            <?=$item['cluster_name']?> 
            (<?=strtoupper($item['cluster_code'])?>)
          </td>

          <!-- Count -->
          <td><?=$item['set_count']?></td>

          <!-- Released in -->
          <td><?=$item['set_date']?></td>

          <!-- Is spoiler -->
          <td>
            <?=$item['set_is_spoiler']
              ? '<span class="text-danger">YES</span>'
              : 'NO'
            ?>
          </td>

        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
