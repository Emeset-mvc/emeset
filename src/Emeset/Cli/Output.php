<?php

namespace Emeset\Cli;

use League\CLImate\CLImate;

class Output implements \Emeset\Contracts\Cli\Output {
    
        public ClImate $cli;
    
        public function __construct(Climate $cli) {
            $this->cli = $cli;
        }
    
        public function warning(string $message = "") {
            $this->cli->yellow($message);
            return $this;
        }

        public function error(string $message = "") {
            $this->cli->red($message);
            return $this;
        }

        public function success(string $message = "") {
            $this->cli->green($message);
            return $this;
        }

        public function info(string $message = "") {
            $this->cli->blue($message);
            return $this;
        }

        public function echo(string $message = "") {
            $this->cli->out($message);
            return $this;
        }

        public function table(array $data = []) {
            $this->cli->table($data);
            return $this;
        }

        public function json(array $data) {
            $this->cli->json($data);
            return $this;
        }

        public function br() {
            $this->cli->br();
            return $this;
        }

        public function input(string $message, array $acceptable = [], $hint = false) {
            $input = $this->cli->input($message);
            if(count($acceptable) > 0){
                $input->accept($acceptable, $hint);
            }
            return $input->prompt();
        }

        public function password(string $message = "") {
            $this->cli->password($message)->prompt();
            return $this;
        }

        public function confirm(string $message = "") {
            $this->cli->confirm($message)->confirmed();
            return $this;
        }

        public function progress(int $total, string $message) {
            $this->cli->progress()->total($total);
            return $this;
        }

        public function progressAdvance(int $advance,  string $message = "") {
            $this->cli->progress()->advance($advance, $message);
            return $this;
        }

        public function clear() {
            $this->cli->clear();
            return $this;
        }

        public function backgroundRed() {
            $this->cli->backgroundRed();
            return $this;
        }

        public function backgroundGreen() {
            $this->cli->backgroundGreen();
            return $this;
        }

        public function backgroundBlue() {
            $this->cli->backgroundBlue();
            return $this;
        }

        public function backgroundYellow() {
            $this->cli->backgroundYellow();
            return $this;
        }

        public function padding(int $length, $label, $result, $char = " ") {
            $this->cli->padding($length)->char($char)->label($label)->result($result);
            return $this;
        }

        public function bold(string $message = "") {
            $this->cli->bold($message);
            return $this;
        }

        public function border(string $char = "*", int $length = 80) {
            $this->cli->border($char, $length);
            return $this;
        }

        public function collumns(array $data = [], $cols = 3) {
            $this->cli->columns($data, $cols);
            return $this;
        }

}