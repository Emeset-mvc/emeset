<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Emeset\Http\Request;

final class RequestTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $_SESSION = [];
        $_REQUEST = [];
        $_SERVER = [];
        $_FILES = [];
    }

    public function testFakeRequestReturnsInjectedValues(): void
    {
        $request = Request::fake(
            ['page' => '1'], // GET
            ['name' => 'Dani'], // POST
            ['user' => 'admin'], // SESSION
            ['id' => 42], // params
            [ // SERVER
                'REQUEST_METHOD' => 'GET',
                'REQUEST_URI' => '/hello',
            ],
            [ // FILES
                'upload' => ['name' => 'file.txt'],
            ]
        );

        $this->assertSame('1', $request->get(INPUT_GET, 'page'));
        $this->assertSame('Dani', $request->get(INPUT_POST, 'name'));
        $this->assertSame('admin', $request->get('SESSION', 'user'));
        $this->assertSame(42, $request->getParam('id'));
        $this->assertSame('GET', $request->get(INPUT_SERVER, 'REQUEST_METHOD'));
        $this->assertSame(
            ['name' => 'file.txt'],
            $request->get('FILES', 'upload')
        );
    }

    public function testHasUsesSessionAndRequestSuperglobals(): void
    {
        $_SESSION['user'] = 'admin';
        $_REQUEST['r'] = 'home';

        $request = new Request();

        $this->assertTrue($request->has('SESSION', 'user'));
        $this->assertFalse($request->has('SESSION', 'missing'));

        $this->assertTrue($request->has('INPUT_REQUEST', 'r'));
        $this->assertFalse($request->has('INPUT_REQUEST', 'missing'));
    }

    public function testGetRawOnFakeRequestReturnsUnescapedValue(): void
    {
        $request = Request::fake(
            ['html' => '<b>bold</b>']
        );

        $this->assertSame(
            '<b>bold</b>',
            $request->getRaw(INPUT_GET, 'html')
        );
    }

    public function testIsAjaxReturnsFalseWhenHeaderMissing(): void
    {
        unset($_SERVER['HTTP_X_REQUESTED_WITH']);

        $request = new Request();

        $this->assertFalse($request->isAjax());
    }
}
