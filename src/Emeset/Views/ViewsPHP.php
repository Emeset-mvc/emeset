<?php

/**
 * Exemple de MVC per a M07 Desenvolupament d'aplicacions web en entorn de servidor.
 * Implementa una view
 * @author: Dani Prados dprados@cendrassos.net
 *
 * Defineix els mètodes que ha de tenir una view
 *
 **/

namespace Emeset\Views;
use Emeset\Contracts\Views\Views;

/**
 * Response: Objecte que encapsula la response.
 * @author: Dani Prados dprados@cendrassos.net
 *
 * Per guarda tota la informació de la response.
 *
 **/
class ViewsPHP implements Views
{

    public $values = [];
    public $path;
    public $template = '';

    /**
     * __construct:  Té tota la informació per crear la response
     *
     * @param $path string path fins a la carpeta de plantilles.
     **/
    public function __construct($path = "../App/Views/")
    {
        $this->path = $path;
    }

    /**
     * set:  obté un valor de l'entrada especificada amb el filtre indicat
     *
     * @param $id string identificadro del valor que deem.
     * @param $valor mixed filtre a desar
     *
     **/
    public function set($id, $valor)
    {
        $this->values[$id] = $valor;
    }


    /**
     * setTemplate defineix quina template utilitzarem per la response.
     *
     * @param string $p nom de la template
     * @return void
     */
    public function setTemplate($p)
    {
        $this->template = $p;
    }

    /**
     * hasTemplate.
     *
     * 
     * @return boolean
     */
    public function hasTemplate()
    {
        return $this->template != '';
    }

    /**
     * hasTemplate.
     *
     * 
     * @return boolean
     */
    public function getJson()
    {
        return json_encode($this->values);
    }

    /**
     * Genera la response HTTP
     *
     * @return void
     */
    public function show()
    {
        extract($this->values);
        include($this->path . $this->template);
    }

    public function getValues(): array
    {
        return $this->values;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }
}
