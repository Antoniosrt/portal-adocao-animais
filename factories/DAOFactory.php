<?php

class DAOFactory
{
    public static function criarAnimalDAO(string $tipo, PDO $pdo): IAnimalDAO
    {
        return match(strtolower($tipo)) {
            'cachorro' => new CachorroDAO($pdo),
            'gato'     => new GatoDAO($pdo),
            'papagaio' => new PapagaioDAO($pdo),
            default    => new AnimalGenericoDAO($pdo),
        };
    }
}
