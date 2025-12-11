<?php

use Emeset\Contracts\Cli\Output as OutputInterface;

final class OutputMock implements OutputInterface
{
    public function warning(string $message = "") {}
    public function error(string $message = "") {}
    public function success(string $message = "") {}
    public function info(string $message = "") {}
    public function echo(string $message = "") {}
    public function table(array $data = []) {}
    public function json(array $data) {}
    public function br() {}
    public function input(string $message, array $acceptable = [], $hint = false) {}
    public function password(string $message = "") {}
    public function confirm(string $message = "") {}
    public function progress(int $total, string $message) {}
    public function progressAdvance(int $advance, string $message = "") {}
    public function clear() {}
    public function backgroundRed() {}
    public function backgroundGreen() {}
    public function backgroundBlue() {}
    public function backgroundYellow() {}
    public function padding(int $length, $label, $result, $char = " ") {}
    public function bold(string $message = "") {}
    public function border(string $char = "*", int $length = 80) {}
    public function collumns(array $data = [], $cols = 3) {}
}
