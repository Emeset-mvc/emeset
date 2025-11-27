<?php

/**
 * Exemple per a M07.
 *
 * @author: Dani Prados dprados@cendrassos.net
 *
 * Objecte que encapsula la response.
 **/

namespace Emeset\Contracts\Http;

/**
 * Response: Objecte que encapsula la response.
 *
 * @author: Dani Prados dprados@cendrassos.net
 *
 * Per guarda tota la informació de la response.
 **/
interface Response
{


    /**
     * set:  obté un valor de l'entrada especificada amb el filtre indicat
     *
     * @param $id    string identificadro del valor que deem.
     * @param $value mixed valor a desar
     **/
    public function set($id, $value);

    /**
     * setSession guarda un valor a la sessió
     *
     * @param  string $id    clau del valor que volem desar
     * @param  mixed  $valor variable que volem desar
     * @return void
     */
    public function setSession($id, $value);


    /**
     * Esborra el valor indicat de la sessió
     *
     * @param $id
     * @return void
     */
    public function unsetSession($id);


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
    public function setCookie($name, $value = "", $expire = 0, $path = "", $domain = "", $secure = false, $httponly = false);
    

    /**
     * setHeader Afegeix una capçalera http a la response
     *
     * @param  string $header capçalera http
     * @return void
     */
    public function setHeader($header);

    /**
     * redirect.  Defineix la response com una redirecció. (accepta els mateixos paràmetres que header)
     *
     * @param  string $header capçalera http amb la
     *                        redirecció
     * @return void
     */
    public function redirect($header);

    /**
     * setTemplate defineix quina template utilitzarem per la response.
     *
     * @param  string $p nom de la template
     * @return void
     */
    public function setTemplate($p);


    /**
     * setJson força que la response sigui en format json.
     *
     *  @param Array $params paràmetres que volem retornar en JSON
     * 
     * @return void
     */
    public function setJSON(Array $params);


    /**
     * setBody utilitza el paràmetre body com a contingut de la resposta HTTP.
     *
     *  @param string $body  Contingut de la resposta HTTP
     *
     * @return void
     */
    public function setBody($body);


    /**
     * Genera la response HTTP
     *
     * @return void
     */
    public function response();
}
