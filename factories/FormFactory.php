<?php

class FormFactory
{
    public static function criarFormAnimal(string $tipo, string $action): FormGenerico
    {
        return match(strtolower($tipo)) {
            'cachorro' => new FormsCadastroCachorro($action),
            'gato'     => new FormsCadastroGato($action),
            'papagaio' => new FormsCadastroPapagaio($action),
            default    => new FormsCadastroAnimalGenerico($action),
        };
    }
}
