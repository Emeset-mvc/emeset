<?php

namespace Emeset\Contracts\Cli;

interface Parser {

    public function command($command, $description = "");

    public function parse(); 

}