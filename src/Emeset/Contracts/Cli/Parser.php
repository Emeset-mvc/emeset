<?php

namespace Emeset\Contracts\Cli;

interface Parser {

    public function command($command, $description = "");

    public function parse(); 

    public function getOpt($arg, $default = null); 

    public function getArgs();

    public function getCommand();

}