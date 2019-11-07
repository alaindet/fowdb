<?php

namespace App\Clint\Commands;

use App\Clint\Commands\Command;

class CardsLegalityCommand extends Command
{
    public $name = "cards:legality";

    /**
     * Run the Clint command cards:legality
     *
     * Ex.:
     * $ php clint cards:legality <arguments> [options]
     *
     * @return CardsLegalityCommand
     */
    public function run(): Command
    {
        // Add your logic here... 
        // To access the command values, use $this->values
        // To access the command options, use $this->options
        // To access Clint paths, use $this->getPath() (see docs)

        // Uncomment to set a title (default is "Clint command cards:legality")
        // $this->setTitle("Your title here...");

        $this->setMessage("Your message here...");

        return $this;
    }
}
