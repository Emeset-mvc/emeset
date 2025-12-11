<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Emeset\Env;

final class EnvTest extends TestCase
{
    public function testGetReturnsDefaultWhenKeyIsMissing(): void
    {
        unset($_ENV['EMESET_TEST_KEY']);

        $this->assertSame(
            'default-value',
            Env::get('EMESET_TEST_KEY', 'default-value')
        );
    }

    public function testGetReturnsEnvValueWhenPresent(): void
    {
        $_ENV['EMESET_TEST_KEY'] = 'from-env';

        $this->assertSame(
            'from-env',
            Env::get('EMESET_TEST_KEY', 'default-value')
        );
    }
}
