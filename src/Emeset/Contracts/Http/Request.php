<?php

/**
 * Exemple de MVC per a M07 Desenvolupament d'aplicacions web en entorn de servidor.
 * Interface d'una petició HTTP.
 * @author: Dani Prados dprados@cendrassos.net
 *
 * Request que encapsularà la petició HTTP per permetre llegir-la com una entrada.
 *
 **/

namespace Emeset\Contracts\Http;

/**
 * Request: interficie que ha d'implementar una petició HTTP.
 * Encapsula la petició HTTP per permetre llegir-la com una entrada.
 * 
 * @package Emeset\Http
 **/
interface Request
{

    /**
     * get:  obté un valor de l'entrada especificada amb el filtre indicat
     *
     * @param $input   string identificador de l'entrada.
     * @param $id      string amb la tasca.
     * @param $filtre  int filtre a aplicar
     * @param $options int opcions del filtre si volem un array FILTER_REQUIRE_ARRAY
     **/
    public function get($input, $id, $filter = "FILTER_SANITIZE_STRING", $options = 0);
    

    /**
     * getRaw:  obté un valor de l'entrada especificada sense filtrar
     *
     * @param $input   string identificador de l'entrada.
     * @param $id      string amb la tasca.
     * @param $options int opcions del filtre si volem un array FILTER_REQUIRE_ARRAY
     **/
    public function getRaw($input, $id, $options = 0);
    

    /**
     * setParams desa el paràmetres de la ruta.
     *
     *  @param array $params  parametres de la ruta.
     *
     * @return void
     */
    public function setParams($params);

    /**
     * getParam obté el paràmetre $param de la ruta.
     * 
     *  @param string $param  paràmetre a recuperar.
     *
     * @return string
     */
    public function getParam($param);

    /**
     * hasParam valida el paràmetre $param de la ruta.
     * 
     * @param string $param paràmetre a recuperar.
     * 
     * @return string
     */
    public function hasParam($param);

    /**
     * has:  retorna true si l'entrada especificada existeix i false si no.
     *
     * @param $input   string identificador de l'entrada.
     * @param $id      string amb la tasca.
     * return boolean
     **/
    public function has($input, $id);
    
}
