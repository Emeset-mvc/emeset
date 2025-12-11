<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Emeset\Views\ViewsPHP;

final class ViewsPHPTest extends TestCase
{
    public function testSetStoresValues(): void
    {
        $view = new ViewsPHP(__DIR__ . '/fixtures/');

        $view->set('title', 'Hola');
        $view->set('message', 'Món');

        $this->assertSame(
            ['title' => 'Hola', 'message' => 'Món'],
            $view->getValues()
        );
    }

    public function testHasTemplateDetectsTemplate(): void
    {
        $view = new ViewsPHP();

        $this->assertFalse($view->hasTemplate());

        $view->setTemplate('index.php');

        $this->assertTrue($view->hasTemplate());
    }

    public function testGetJsonReturnsAllValuesWhenWildcard(): void
    {
        $view = new ViewsPHP();
        $view->set('a', 1);
        $view->set('b', 2);

        $json = $view->getJson(['*']);

        $this->assertSame(
            json_encode(['a' => 1, 'b' => 2]),
            $json
        );
    }

    public function testGetJsonFiltersSelectedKeys(): void
    {
        $view = new ViewsPHP();
        $view->set('a', 1);
        $view->set('b', 2);

        $json = $view->getJson(['b']);

        $this->assertSame(
            json_encode(['b' => 2]),
            $json
        );
    }
}
