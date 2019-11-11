<?php

namespace App\Clint\Commands;

use App\Clint\Commands\Command;

class %COMMAND_CLASS% extends Command
{
    public $name = "%COMMAND_NAME%";

    /**
     * Run the Clint command %COMMAND_NAME%
     *
     * Ex.:
     * $ php clint %COMMAND_NAME% <values> [options]
     *
     * @return Command
     */
    public function run(): Command
    {
        // Add your logic here... 
        // To access the command values, use $this->values
        // To access the command options, use $this->options
        // To access Clint paths, use $this->getPath() (see docs)

        // Uncomment to set a title (default is "Clint command %COMMAND_NAME%")
        // $this->setTitle("Your title here...");

        $this->setMessage("Your message here...");

        return $this;
    }
}
