<?php

namespace Emeset\Cli;

class Parser implements \Emeset\Contracts\Cli\Parser {
    public \Garden\Cli\Cli $cli;

    public array $argv = [];

    public function __construct($argv, \Garden\Cli\Cli $cli) {
        $this->argv = $argv;
        $this->cli = $cli;
        
    }

    public function command($command, $description = ""){
        return $this->cli->command($command)->description($description);
    }

    public function parse() {   
        return $this->cli->parse($this->argv);
    }

}