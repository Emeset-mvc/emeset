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
     * @param $projectRootPath permet definir el path del projecte
     **/
    public function __construct(array|string $config, ?string $projectRootPath = null)
    {
        if ($projectRootPath === null) {
            $isCLI = (php_sapi_name() === 'cli');

            // En CLI (phpunit, comandes) volem que l’arrel sigui el cwd
            if ($isCLI) {
                $projectRootPath = getcwd();
            } else {
                // En entorn web continua funcionant com fins ara
                $projectRootPath = dirname(getcwd());
            }
        }

        $isTest = defined('PHPUNIT_COMPOSER_INSTALL') || defined('__PHPUNIT_BOOTSTRAP')
            || getenv('PHPUNIT_RUNNING') === '1';


        $envFile = $isTest ? '.env.test' : '.env';

        // Si estem en test però no existeix .env.test → fallback a .env
        if (!file_exists($projectRootPath . '/' . $envFile)) {
            $envFile = '.env';
        }

        $dotenv = Dotenv::createImmutable($projectRootPath, $envFile);
        $dotenv->safeLoad();

        // 3. Carregar config (igual que abans)
        if (is_string($config)) {
            $config = require $config;
        }

        parent::__construct($config);

        $this["config"] = $config;


        $this["request"] = (fn(PimpleContainer $c): \Emeset\Http\Request => new \Emeset\Http\Request());

        $this["view"] = (fn(PimpleContainer $c): \Emeset\Views\ViewsPHP => new \Emeset\Views\ViewsPHP());

        $this["response"] = (fn(PimpleContainer $c): \Emeset\Http\Response => new \Emeset\Http\Response($c["view"]));

        $this["router"] = (fn(PimpleContainer $c): \Emeset\Routers\RouterHttp => new \Emeset\Routers\RouterHttp($c, $c["config"]));

        $this["FrontController"] = (fn(PimpleContainer $c): \Emeset\FrontController => new \Emeset\FrontController($c));

        $this["caller"] = (fn(PimpleContainer $c): \Emeset\Caller => new \Emeset\Caller($c));

        $this["cli"] = (fn(PimpleContainer $c): \Emeset\Cli\Cli => new \Emeset\Cli\Cli($c["cli.parser"], $c["cli.output"], $c["caller"], $c));

        $this["cli.parser"] = (fn(PimpleContainer $c): \Emeset\Cli\Parser => new \Emeset\Cli\Parser($_SERVER["argv"], $c["cli.garden"]));

        $this["cli.output"] = (fn(PimpleContainer $c): \Emeset\Cli\Output => new \Emeset\Cli\Output($c["cli.Climate"]));

        $this["cli.garden"] = (fn(PimpleContainer $c): \Garden\Cli\Cli => new \Garden\Cli\Cli());

        $this["cli.Climate"] = (fn(PimpleContainer $c): \League\CLImate\CLImate => new \League\CLImate\CLImate());
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
    public function get(string $id): mixed
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
    public function has(string $id): bool
    {
        return $this->offsetExists($id);
    }
}
