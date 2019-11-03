<?php

namespace App\Clint\Commands;

use App\Clint\Commands\Command;

class %CLASS_NAME% extends Command
{
    public $name = '%COMMAND_NAME%';

    public function run(array $options, array $arguments): void
    {
        // Uncomment to set a title (default is command name)
        // $this->title = 'Your title here...';

        $this->message = 'Write your message here...';
    }
}
