<?php

use \App\Http\Request\Input;
use \App\Services\FileSystem;
use \App\Legacy\AdminView as View;

// ERROR: Not authorized
if (admin_level() === 0) {
  notify('You are not authorized.');
  redirect();
  return;
}

// Alias input
$input = Input::getInstance();

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
if ($input->exists('command', 'GET')) {
  $key = $input->get('command');
  $commandData = $commands[$key];
  $options = $commandData['options'] ?? [];
  $arguments = $commandData['arguments'] ?? [];
  $classes = FileSystem::loadFile(path_src('app/Clint/commands-list.php'));
  $class = $classes[$commandData['name']];
  $command = new $class();
  $command->run($options, $arguments);
  notify($command->message());
  redirect('admin/clint');
}
?>

<div class="page-header">
  <h1>Clint commands</h1>
  <?=View::breadcrumbs([
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
