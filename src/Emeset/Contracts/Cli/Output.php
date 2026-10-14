<?php

namespace Emeset\Contracts\Cli;



interface Output {
    
        public function warning(string $message = ""): static;

        public function error(string $message = ""): static;

        public function success(string $message = ""): static;

        public function info(string $message = ""): static;

        public function echo(string $message = ""): static;

        public function table(array $data = []): static;

        public function json(array $data): static;

        public function br(): static;

        public function input(string $message, array $acceptable = [], mixed $hint = false): string;

        public function password(string $message = ""): static;

        public function confirm(string $message = ""): bool;

        public function progress(int $total, string $message): static;

        public function progressAdvance(int $advance, string $message = ""): static;

        public function clear(): static;

        public function backgroundRed(): static;

        public function backgroundGreen(): static;

        public function backgroundBlue(): static;

        public function backgroundYellow(): static;

        public function padding(int $length, mixed $label, mixed $result, string $char = " "): static;

        public function bold(string $message = ""): static;

        public function border(string $char = "*", int $length = 80): static;

        public function collumns(array $data = [], int $cols = 3): static;

}
