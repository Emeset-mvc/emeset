<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Emeset\Http\Response;
use Emeset\Views\ViewsPHP;

final class ResponseTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];
    }

    private function createResponse(): Response
    {
        return new Response(new ViewsPHP());
    }

    public function testSetDelegatesToView(): void
    {
        $view = new ViewsPHP();
        $response = new Response($view);

        $response->set('title', 'Hola');

        $this->assertSame(['title' => 'Hola'], $view->getValues());
    }

    public function testSetSessionStoresValue(): void
    {
        $response = $this->createResponse();

        $response->setSession('user', 'admin');

        $this->assertSame('admin', $_SESSION['user'] ?? null);
    }

    public function testUnsetSessionRemovesValue(): void
    {
        $_SESSION['user'] = 'admin';

        $response = $this->createResponse();
        $response->unsetSession('user');

        $this->assertArrayNotHasKey('user', $_SESSION);
    }

    public function testRedirectSetsHeaderAndFlag(): void
    {
        $response = $this->createResponse();

        $response->redirect('Location: /home');

        $this->assertTrue($response->isRedirect());
        $this->assertSame('Location: /home', $response->getHeader());
    }

    public function testRenderReturnsBodyWhenSet(): void
    {
        $response = $this->createResponse();
        $response->setBody('Custom body');

        $this->assertSame('Custom body', $response->render());
    }

    public function testRenderReturnsEmptyStringForRedirect(): void
    {
        $response = $this->createResponse();
        $response->redirect('Location: /home');

        $this->assertSame('', $response->render());
    }

    public function testRenderFallsBackToJsonWhenNoTemplateOrBody(): void
    {
        $response = $this->createResponse();

        $response->set('a', 1);

        $this->assertSame('{"a":1}', $response->render());
    }
}
