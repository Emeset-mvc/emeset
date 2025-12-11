<?php

namespace Emeset\Cli;

use Emeset\Contracts\Cli\Output;
use Emeset\Contracts\Container;

class Cli {
    public Parser $cli;
    public Output $output;
    public \Emeset\Caller $caller;
    public Container $container;

    public array $actions = [];
    public \Emeset\Cli\Parser $args;  //Must be encapsulated in a class to hide the implementation details

    public function __construct(Parser $cli, Output $output, \Emeset\Caller $caller, Container $container) {
        $this->cli = $cli;
        $this->output = $output;
        $this->caller = $caller;
        $this->container = $container;
    }

    public function addCommand(string $command,  $action, string $description = "") {
        $this->actions[$command] = $action;
        return $this->cli->command($command)->description($description);
    }

    public function run() {
        $this->args = $this->cli->parse();
        $call = $this->caller->resolve($this->actions[$this->args->getCommand()]);
        $call($this->args, $this->output, $this->container);
    }

}