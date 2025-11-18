<?php

/**
 * Exemple per a M07.
 *
 * @author: Dani Prados dprados@cendrassos.net
 *
 * Objecte que encapsula la response.
 **/

namespace Emeset\Http;

use Emeset\Contracts\Http\Response as ResponseInterface;
use Emeset\Contracts\Views\Views;

/**
 * Response: Objecte que encapsula la response.
 *
 * @author: Dani Prados dprados@cendrassos.net
 *
 * Per guarda tota la informació de la response.
 **/
class Response implements ResponseInterface
{

    public $values = [];
    public $header = false;
    public $redirect = false;
    public $json = false;
    public $body = "";
    public $view;

    /**
     * __construct:  Té tota la informació per crear la response
     *
     * @param $path string path fins a la carpeta de plantilles.
     **/
    public function __construct(Views $view)
    {
        $this->view = $view;
    }

    /**
     * set:  obté un valor de l'entrada especificada amb el filtre indicat
     *
     * @param $id    string identificadro del valor que deem.
     * @param $value mixed valor a desar
     **/
    public function set($id, $value)
    {
        $this->view->set($id, $value);
    }

    /**
     * setSession guarda un valor a la sessió
     *
     * @param  string $id    clau del valor que volem desar
     * @param  mixed  $valor variable que volem desar
     * @return void
     */
    public function setSession($id, $value)
    {
        $_SESSION[$id] = $value;
    }

    /**
     * Esborra el valor indicat de la sessió
     *
     * @param $id
     * @return void
     */
    public function unsetSession($id)
    {
        unset($_SESSION[$id]);
    }

    /**
     * setCookie funció afegida per consistència crea una cookie.
     *
     * Accepta exament els mateixos paràmetres que la funció setcookie de php.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  integer $expire
     * @param  string  $path
     * @param  string  $domain
     * @param  boolean $secure
     * @param  boolean $httponly
     * @return void
     */
    public function setCookie($name, $value = "", $expire = 0, $path = "", $domain = "", $secure = false, $httponly = false)
    {
        setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
    }

    /**
     * setHeader Afegeix una capçalera http a la response
     *
     * @param  string $header capçalera http
     * @return void
     */
    public function setHeader($header)
    {
        $this->header = $header;
    }

    /**
     * redirect.  Defineix la response com una redirecció. (accepta els mateixos paràmetres que header)
     *
     * @param  string $header capçalera http amb la
     *                        redirecció
     * @return void
     */
    public function redirect($header)
    {
        $this->setHeader($header);
        $this->redirect = true;
    }

    /**
     * setTemplate defineix quina template utilitzarem per la response.
     *
     * @param  string $p nom de la template
     * @return void
     */
    public function setTemplate($p)
    {
        $this->view->setTemplate($p);
    }

    /**
     * setJson força que la response sigui en format json.
     *
     * @return void
     */
    public function setJSON()
    {
        $this->json = true;
    }

    /**
     * setBody utilitza el paràmetre body com a contingut de la resposta HTTP.
     *
     *  @param string $body  Contingut de la resposta HTTP
     *
     * @return void
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * render crea la resposta com string i la retorna
     *
     *  @param string $body  Contingut de la resposta HTTP
     *
     * @return void
     */
    public function render(): string
    {
        // Redireccions normalment no tenen cos
        if ($this->redirect) {
            return '';
        }

        if ($this->body !== '') {
            return $this->body;
        }

        if ($this->view->hasTemplate()) {
            ob_start();
            $this->view->show();
            return ob_get_clean();
        }

        return $this->view->getJson();
    }

    /**
     * Genera la response HTTP
     *
     * @return void
     */
    public function response()
    {
        if ($this->redirect) {
            header($this->header);
        } else {
            if ($this->header !== false) {
                header($this->header);
            }

            echo $this->render();
        }
    }

    /**
     * Obté el headers
     *
     * @return string|false
     */
    public function getHeader(): string|false
    {
        return $this->header;
    }

    /**
     * Obté si és una redirecció
     *
     * @return string|false
     */
    public function isRedirect(): bool
    {
        return $this->redirect;
    }

    /**
     * Obté si és JSON
     *
     * @return string|false
     */
    public function isJson(): bool
    {
        return $this->json;
    }

    /**
     * Obté si la resposta directa
     *
     * @return string|false
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * Obté si la vista
     *
     * @return string|false
     */
    public function getView(): \Emeset\Contracts\Views\Views
    {
        return $this->view;
    }
}
