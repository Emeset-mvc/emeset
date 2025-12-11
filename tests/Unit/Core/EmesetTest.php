<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Emeset\Emeset;
use Emeset\Container;
use Emeset\Routers\RouterParam;
use Emeset\Http\Request;
use Emeset\Http\Response;
use Emeset\Routers\RouterHttp;
use Emeset\Views\ViewsPHP;

final class EmesetTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $_REQUEST = [];
    }

    public function testHandleExecutesFrontControllerRoute(): void
    {
        $projectRoot = dirname(__DIR__, 3);
        $container = new Container([], $projectRoot);

        // Substituïm el router per RouterParam (més fàcil de provar)
        $container['router'] = function ($c) {
            return new RouterParam($c, $c['config']);
        };

        $app = new Emeset($container);

        // Ruta per defecte (quan r no ve informat)
        $app->route('', function ($req, $res) {
            $res->setBody('ok');
            return $res;
        });

        // No definim $_REQUEST['r'] → RouterParam agafarà ruta ""
        $result = $app->handle();

        $this->assertSame('ok', $result->getBody());
    }
    
}
