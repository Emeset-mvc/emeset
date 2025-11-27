<?php

/**
 * Exemple de MVC per a M07 Desenvolupament d'aplicacions web en entorn de servidor.
 * Classe abstracta d'una view
 * @author: Dani Prados dprados@cendrassos.net
 *
 * Defineix els mètodes que ha de tenir una view
 *
 **/

namespace Emeset\Contracts\Views;

/**
 * Response: Objecte que encapsula la response.
 * @author: Dani Prados dprados@cendrassos.net
 *
 * Per guarda tota la informació de la response.
 *
 **/
interface Views
{


    /**
     * set:  obté un valor de l'entrada especificada amb el filtre indicat
     *
     * @param $id string identificadro del valor que deem.
     * @param $valor mixed filtre a desar
     *
     **/
    public function set($id, $valor);


    /**
     * setTemplate defineix quina template utilitzarem per la response.
     *
     * @param string $p nom de la template
     * @return void
     */
    public function setTemplate($p);

    /**
     * Genera la response HTTP
     *
     * @return void
     */
    public function show();


    /**
     * hasTemplate.
     *
     * 
     * @return boolean
     */
    public function hasTemplate();

    /**
     * getJson
     *
     * @param Array $params paràmetres que volem retornar en el JSON
     * 
     * @return boolean
     */
    public function getJson(array $params);
}
