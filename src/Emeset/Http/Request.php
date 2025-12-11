<?php

/**
 * Exemple per a M07.
 *
 * @author: Dani Prados dprados@cendrassos.net
 *
 * Classe gestiona la petició HTTP.
 **/

namespace Emeset\Http;

use Emeset\Contracts\Http\Request as RequestInterface;

/**
 * Request: Classe gestiona la petició HTTP.
 *
 * @author: Dani Prados dprados@cendrassos.net
 *
 * Encapsula la petició HTTP per permetre llegir-la com una entrada.
 **/
class Request implements RequestInterface
{
    public $params = [];
    private bool $testing = false;
    private $fakeGET = [];
    private $fakePOST = [];
    private $fakeSESSION = [];
    private array $fakeSERVER = [];
    private array $fakeFILES = [];


    /**
     * __construct:  Crear el petició http
     **/
    public function __construct()
    {
        //session_start();
    }

    /**
     * get:  obté un valor de l'entrada especificada amb el filtre indicat
     *
     * @param $input   string identificador de l'entrada.
     * @param $id      string amb la tasca.
     * @param $filtre  int filtre a aplicar
     * @param $options int opcions del filtre si volem un array FILTER_REQUIRE_ARRAY
     **/
    public function get($input, $id, $filter = "FILTER_SANITIZE_STRING", $options = 0)
    {
        $result = false;
        if ($this->testing) {
            if ($input === INPUT_POST) return $this->fakePOST[$id] ?? null;
            if ($input === INPUT_GET) return $this->fakeGET[$id] ?? null;
            if ($input === 'SESSION') return $this->fakeSESSION[$id] ?? null;
            if ($input === INPUT_SERVER) return $this->fakeSERVER[$id] ?? null;
            if ($input === 'FILES')     return $this->fakeFILES[$id] ?? null;
        }
        if ($input === 'SESSION') {
            $result = null;
            if (isset($_SESSION[$id])) {
                $result = $_SESSION[$id];
            }
        } elseif ($input === 'FILES') {
            $result = null;
            if (isset($_FILES[$id])) {
                $result = $_FILES[$id];
            }
        } elseif ($input === "INPUT_REQUEST") {
            $result = null;
            if (isset($_REQUEST[$id])) {
                $var = $_REQUEST[$id];
                 if ($filter == "FILTER_SANITIZE_STRING") {
                    $result = htmlspecialchars($var);
                } else {
                    $result = filter_var($var, $filter, $options);
                }
            }
        } elseif ($input === INPUT_SERVER) {
            $result = null;
            if (isset($_SERVER[$id])) {
                $var = $_SERVER[$id];
                if ($filter == "FILTER_SANITIZE_STRING") {
                    $result = htmlspecialchars($var);
                } else {
                    $result = filter_var($var, $filter, $options);
                }
            }
        } else {
            if ($filter == "FILTER_SANITIZE_STRING") {
                $result = filter_input($input, $id, FILTER_DEFAULT, $options);
                if (isset($result)) {
                    $result = htmlspecialchars($result);
                }
            } else {
                $result = filter_input($input, $id, $filter, $options);
            }
        }
        return $result;
    }

    /**
     * getRaw:  obté un valor de l'entrada especificada sense filtrar
     *
     * @param $input   string identificador de l'entrada.
     * @param $id      string amb la tasca.
     * @param $options int opcions del filtre si volem un array FILTER_REQUIRE_ARRAY
     **/
    public function getRaw($input, $id, $options = 0)
    {
        return $this->get($input, $id, FILTER_DEFAULT, $options);
    }

    /**
     * setParams desa el paràmetres de la ruta.
     *
     *  @param array $params  parametres de la ruta.
     *
     * @return void
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * getParam obté el paràmetre $param de la ruta.
     * 
     *  @param string $param  paràmetre a recuperar.
     *
     * @return string
     */
    public function getParam($param)
    {
        return $this->params[$param];
    }

    /**
     * has:  retorna true si l'entrada especificada existeix i false si no.
     *
     * @param $input   string identificador de l'entrada.
     * @param $id      string amb la tasca.
     * return boolean
     **/
    public function has($input, $id)
    {
        $result = false;
        if ($input === 'SESSION') {
            $result = isset($_SESSION[$id]);
        } elseif ($input === 'FILES') {
            $result = isset($_FILES[$id]);
        } elseif ($input === "INPUT_REQUEST") {
            $result = isset($_REQUEST[$id]);
        } else {
            $result = !is_null(filter_input($input, $id, FILTER_DEFAULT));
        }
        return $result;
    }

    public static function fake(array $get = [], array $post = [], array $session = [], array $params = [], array $server = [], array $files = []): self
    {
        $r = new self();
        $r->testing = true;
        $r->fakeGET = $get;
        $r->fakePOST = $post;
        $r->fakeSESSION = $session;
        $r->params = $params;
        $r->fakeSERVER = $server;
        $r->fakeFILES = $files;
        return $r;
    }

    /**
     * isAjax:  retorna true si la petició és AJAX i false si no.
     *
     * return boolean
     * 
     * Mateo Cerna
     **/
    public function isAjax()
    {
        //Comprovem si la petició és AJAX mirant la capçalera HTTP_X_REQUESTED_WITH i si el seu valor és xmlhttprequest
        return !empty($this->has(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH')) && strtolower($this->get(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH')) == 'xmlhttprequest';
    }
}
