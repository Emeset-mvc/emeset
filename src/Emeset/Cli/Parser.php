<?php

namespace Emeset\Cli;

class Parser implements \Emeset\Contracts\Cli\Parser {
    public \Garden\Cli\Cli $cli;
    public \Garden\Cli\Args $args;

    public array $argv = [];

    public function __construct($argv, \Garden\Cli\Cli $cli) {
        $this->argv = $argv;
        $this->cli = $cli;
        
    }

    public function command($command, $description = ""){
        return $this->cli->command($command)->description($description);
    }

    public function parse() {   
        $this->args = $this->cli->parse($this->argv);
        return $this;
    }

    public function getOpt($arg, $default = null) {
        return $this->args->getOpt($arg, $default);
    }

    public function getArgs() {
        return $this->args->getArgs();
    }

    public function getCommand() {
        return $this->args->getCommand();
    }

}