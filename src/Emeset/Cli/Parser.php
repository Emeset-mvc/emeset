<?php

namespace Emeset\Cli;

class Parser implements \Emeset\Contracts\Cli\Parser {
    public \Garden\Cli\Args $args;

    public function __construct(public array $argv, public \Garden\Cli\Cli $cli)
    {
    }

    public function command(string $command, string $description = ""): mixed {
        return $this->cli->command($command)->description($description);
    }

    public function parse(): self {
        $this->args = $this->cli->parse($this->argv);
        return $this;
    }

    public function getOpt(string $arg, mixed $default = null): mixed {
        return $this->args->getOpt($arg, $default);
    }

    public function getArgs(): array {
        return $this->args->getArgs();
    }

    public function getCommand(): string {
        return $this->args->getCommand();
    }

}
