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
      <th>Default</th>
      <th>Multi-cluster?</th>
      <th>Clusters</th>
    </thead>

    <tbody>
      <?php foreach ($items as $item): ?>
        <tr>

          <!-- # -->
          <td><?=$item->id?></td>
          
          <!-- Actions -->
          <td>

            <!-- Update -->
            <a
              class="btn btn-xs fd-btn-default fd-btn-warning-hover"
              href="<?=fd_url('formats/update/'.$item->id)?>"
            >
              <i class="fa fa-pencil"></i>
              Update
            </a>

            <!-- Delete -->
            <a
              class="btn btn-xs fd-btn-default fd-btn-danger-hover"
              href="<?=fd_url('formats/delete/'.$item->id)?>"
            >
              <i class="fa fa-trash"></i>
              Delete
            </a>

          </td>

          <!-- Name -->
          <td>
            <a href="<?=fd_url('cards', ['format' => $item->code])?>">
              <?=$item->name?>
            </a>
          </td>

          <!-- Code -->
          <td><?=$item->code?></td>

          <!-- Default -->
          <td>
            <?php if ($item->is_default): ?>
              <strong class="text-danger">YES</strong>
            <?php else: ?>
              NO
            <?php endif; ?>
          </td>

          <!-- Multi-cluster -->
          <td>
            <?php if ($item->is_multi_cluster): ?>
              <strong class="text-danger">YES</strong>
            <?php else: ?>
              NO
            <?php endif; ?>
          </td>

          <!-- Clusters -->
          <td>
            <ul class="fd-list">
              <?php foreach($item->clusters as $cluster): ?>
                <li>
                  <a href="<?=fd_url('cards', ['cluster' => $cluster->code])?>">
                    <?=$cluster->name?>
                  </a>
                </li>
              <?php endforeach; ?>
            </ul>

          </td>

        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
