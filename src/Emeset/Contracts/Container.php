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

namespace Emeset\Contracts;


interface Container
{

    
    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return mixed
     *
     * @throws \Exception      when identifier not found   
     */
    public function get(string $id): mixed;


    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return boolean
     */
    public function has(string $id): bool;
}
