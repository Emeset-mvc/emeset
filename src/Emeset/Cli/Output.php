<?php

namespace Emeset\Cli;

use League\CLImate\CLImate;

class Output implements \Emeset\Contracts\Cli\Output {
    
        public ?\League\CLImate\TerminalObject\Dynamic\Progress $progress = null;
    
        public function __construct(public ClImate $cli)
        {
        }
    
        public function warning(string $message = ""): static {
            $this->cli->yellow($message);
            return $this;
        }

        public function error(string $message = ""): static {
            $this->cli->red($message);
            return $this;
        }

        public function success(string $message = ""): static {
            $this->cli->green($message);
            return $this;
        }

        public function info(string $message = ""): static {
            $this->cli->blue($message);
            return $this;
        }

        public function echo(string $message = ""): static {
            $this->cli->out($message);
            return $this;
        }

        public function table(array $data = []): static {
            $this->cli->table($data);
            return $this;
        }

        public function json(array $data): static {
            $this->cli->json($data);
            return $this;
        }

        public function br(): static {
            $this->cli->br();
            return $this;
        }

        public function input(string $message, array $acceptable = [], mixed $hint = false): string {
            $input = $this->cli->input($message);
            if(count($acceptable) > 0){
                $input->accept($acceptable, $hint);
            }
            return $input->prompt();
        }

        public function password(string $message = ""): static {
            $this->cli->password($message)->prompt();
            return $this;
        }

        public function confirm(string $message = ""): bool {
            $this->cli->confirm($message)->confirmed();
            return $this;
        }

        public function progress(int $total, string $message): static {
            $this->progress = $this->cli->progress()->total($total);
            return $this;
        }

        public function progressAdvance(int $advance, string $message = ""): static {
            $this->progress->advance($advance, $message);
            return $this;
        }

        public function clear(): static {
            $this->cli->clear();
            return $this;
        }

        public function backgroundRed(): static {
            $this->cli->backgroundRed();
            return $this;
        }

        public function backgroundGreen(): static {
            $this->cli->backgroundGreen();
            return $this;
        }

        public function backgroundBlue(): static {
            $this->cli->backgroundBlue();
            return $this;
        }

        public function backgroundYellow(): static {
            $this->cli->backgroundYellow();
            return $this;
        }

        public function padding(int $length, mixed $label, mixed $result, string $char = " "): static {
            $this->cli->padding($length)->char($char)->label($label)->result($result);
            return $this;
        }

        public function bold(string $message = ""): static {
            $this->cli->bold($message);
            return $this;
        }

        public function border(string $char = "*", int $length = 80): static {
            $this->cli->border($char, $length);
            return $this;
        }

        public function collumns(array $data = [], int $cols = 3): static {
            $this->cli->columns($data, $cols);
            return $this;
        }

}
