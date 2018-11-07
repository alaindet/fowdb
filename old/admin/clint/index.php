<?php

// Check authorization and bounce back intruders
\App\Legacy\Authorization::allow();

// Alias input
$input = \App\Http\Request\Input::getInstance();

// List the available clint commands
$commands = [
  'cache-config' => [
    'label' => 'Cache the configuration',
    'name' => 'config:cache'
  ],
  'cache-clear' => [
    'label' => 'Clear the configuration file',
    'name' => 'config:clear'
  ],
  'env-to-production' => [
    'label' => 'Switch environment to production',
    'name' => 'env:switch',
    'arguments' => ['production']
  ],
  'env-to-development' => [
    'label' => 'Switch environment to development',
    'name' => 'env:switch',
    'arguments' => ['development']
  ],
  'lookup-cache' => [
    'label' => 'Cache lookup data',
    'name' => 'lookup:cache'
  ],
];

// Execute Clint command
if ($input->has('command')) {
  $key = $input->get('command');
  $commandData = $commands[$key];
  $options = $commandData['options'] ?? [];
  $arguments = $commandData['arguments'] ?? [];
  $classes = \App\Services\FileSystem::loadFile(
    path_src('app/Clint/commands-list.php')
  );
  $class = $classes[$commandData['name']];
  $command = new $class();
  $command->run($options, $arguments);
  alert($command->message());
  redirect_old('admin/clint');
}
?>

<div class="page-header">
  <h1>Clint commands</h1>
  <?=\App\Legacy\AdminView::breadcrumbs([
    'Clint' => '#'
  ])?>
</div>

<ul>
  <?php foreach ($commands as $key => $command): ?>
    <li>
      <a
        href="<?php echo url_old('admin/clint', ['command' => $key]); ?>"
        class="btn btn-link btn-lg separate"
      >
        <?=$command['label']?>
      </a>
    </li>
  <?php endforeach; ?>
</ul>