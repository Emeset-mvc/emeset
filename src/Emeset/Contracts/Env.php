<?php

namespace Emeset\Contracts;

interface Env
{
    /**
     * Obté una variable d'entorn
     * @param string $key identificador de la variable
     * @param mixed $default valor per defecte si no existeix la variable
     * @return mixed
     */
    public static function get(string $key, mixed $default = null): mixed;
}
