<?php

/**
 * Exemple de MVC per a M07 Desenvolupament d'aplicacions web en entorn de servidor.
 * Contenidor, registra els serveis del Framework Emeset
 * @author: Dani Prados dprados@cendrassos.net
 *
 * Defineix les depedències de l'aplicació i com instanciar-les. Per substituir alguna
 * depedència només cal extendre el contenidor i definir les noves depedències.
 * Com a contenidor utilitza el component Pipmple https://pimple.symfony.com/
 *
 **/

namespace Emeset;

use Emeset\Contracts\Container as ContainerInterface;
use Pimple\Container as PimpleContainer;
use Dotenv\Dotenv;

class Container extends PimpleContainer implements ContainerInterface
{

    /**
     * __construct:  Defineix les dependències del projecte Emeset
     *
     * @param $config array amb la configuració del projecte.
     **/
    public function __construct($config)
    {
        $projectRootPath = dirname(getcwd());
        $dotenv = Dotenv::createImmutable($projectRootPath);
        $dotenv->safeLoad();

        if (is_string($config)) {
            $config = require $config;
        }

        $this["config"] = $config;


        $this["request"] = function ($c) {
            return new \Emeset\Http\Request();
        };

        $this["view"] = function ($c) {
            return new \Emeset\Views\ViewsPHP();
        };

        $this["response"] = function ($c) {
            return new \Emeset\Http\Response($c["view"]);
        };

        $this["router"] = function ($c) {
            return new \Emeset\Routers\RouterHttp($c, $c["config"]);
        };

        $this["FrontController"] = function ($c) {
            return new \Emeset\FrontController($c);
        };

        $this["caller"] = function ($c) {
            return new \Emeset\Caller($c);
        };

        $this["cli"] = function ($c) {
            return new \Emeset\Cli\Cli($argv, $c["cli.parser"], $c["cli.output"], $c["caller"], $c);
        };

        $this["cli.parser"] = function ($c) {
            return new \Emeset\Cli\Parser($argv, $c["cli.garden"]);
        };

        $this["cli.output"] = function ($c) {
            return new \Emeset\Cli\Output($c["cli.garden"]);
        };

        $this["cli.garden"] = function ($c) {
            return new \Garden\Cli\Cli();
        };

    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return mixed
     *
     * @throws \Exception      when identifier not found   
     */
    public function get($id)
    {
        if (!$this->offsetExists($id)) {
            throw new \Exception(sprintf('Identifier "%s" is not defined.', $id));
        }
        try {
            return $this->offsetGet($id);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }


    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return boolean
     */
    public function has($id)
    {
        return $this->offsetExists($id);
    }
}
