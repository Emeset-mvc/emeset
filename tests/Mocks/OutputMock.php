<?php

use Emeset\Contracts\Cli\Output as OutputInterface;

final class OutputMock implements OutputInterface
{
    public function warning(string $message = ""): static { return $this; }
    public function error(string $message = ""): static { return $this; }
    public function success(string $message = ""): static { return $this; }
    public function info(string $message = ""): static { return $this; }
    public function echo(string $message = ""): static { return $this; }
    public function table(array $data = []): static { return $this; }
    public function json(array $data): static { return $this; }
    public function br(): static { return $this; }
    public function input(string $message, array $acceptable = [], mixed $hint = false): string { return ""; }
    public function password(string $message = ""): static { return $this; }
    public function confirm(string $message = ""): bool { return false; }
    public function progress(int $total, string $message): static { return $this; }
    public function progressAdvance(int $advance, string $message = ""): static { return $this; }
    public function clear(): static { return $this; }
    public function backgroundRed(): static { return $this; }
    public function backgroundGreen(): static { return $this; }
    public function backgroundBlue(): static { return $this; }
    public function backgroundYellow(): static { return $this; }
    public function padding(int $length, mixed $label, mixed $result, string $char = " "): static { return $this; }
    public function bold(string $message = ""): static { return $this; }
    public function border(string $char = "*", int $length = 80): static { return $this; }
    public function collumns(array $data = [], int $cols = 3): static { return $this; }
}
