<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Emeset\Cli\Cli as EmesetCli;
use Emeset\Contracts\Container as ContainerInterface;
use Emeset\Contracts\Cli\Output as OutputInterface;
use Emeset\Contracts\Cli\Parser as ParserInterface;

require_once __DIR__ . '/../../Mocks/OutputMock.php';

final class CliTest extends TestCase
{
    public function testRunExecutesRegisteredCommand(): void
    {
        $executed = false;
        $_SERVER["argv"] = ["script", "hello"];

        $container = new \Emeset\Container([]);
        $container["cli.output"] = (fn(\Pimple\Container $c): \OutputMock => new OutputMock());

        $cli = $container["cli"];
        

        $cli->addCommand('hello', function (\Emeset\Cli\Parser $args, \Emeset\Contracts\Cli\Output $out, \Emeset\Contracts\Container $c) use (&$executed): void {
            $executed = true;
        }, 'Test command');

        $cli->run();

        $this->assertTrue($executed);
    }
}
