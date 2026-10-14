<?php

namespace Emeset\Cli;

use Emeset\Contracts\Cli\Output;
use Emeset\Contracts\Container;

class Cli {
    /** @var array<string, callable|array|string> */
    public array $actions = [];
    public Parser $args;  //Must be encapsulated in a class to hide the implementation details

    public function __construct(public Parser $cli, public Output $output, public \Emeset\Caller $caller, public Container $container)
    {
    }

    public function addCommand(string $command, callable|array|string $action, string $description = ""): mixed {
        $this->actions[$command] = $action;
        return $this->cli->command($command)->description($description);
    }

    public function run(): void {
        $this->args = $this->cli->parse();
        $call = $this->caller->resolve($this->actions[$this->args->getCommand()]);
        $call($this->args, $this->output, $this->container);
    }

}
