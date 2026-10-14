<?php

namespace Emeset\Contracts\Cli;

interface Parser {

    public function command(string $command, string $description = ""): mixed;

    public function parse(): self;

    public function getOpt(string $arg, mixed $default = null): mixed;

    public function getArgs(): array;

    public function getCommand(): string;

}
