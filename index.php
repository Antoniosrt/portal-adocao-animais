<?php
declare(strict_types=1);

// Autoloader: mapeia NomeClasse → diretório correspondente
spl_autoload_register(function (string $classe): void {
    $mapa = [
        'Database'          => 'config',
        'Controller'        => 'core',
        'Router'            => 'core',
        'Animal'            => 'models',
        'Cachorro'          => 'models',
        'Gato'              => 'models',
        'Papagaio'          => 'models',
        'AnimalGenerico'    => 'models',
        'Comentario'        => 'models',
        'HistoricoSaude'    => 'models',
        'IAnimalDAO'        => 'dao/interfaces',
        'IComentarioDAO'    => 'dao/interfaces',
        'IHistoricoDAO'     => 'dao/interfaces',
        'AnimalDAO'         => 'dao',
        'CachorroDAO'       => 'dao',
        'GatoDAO'           => 'dao',
        'PapagaioDAO'       => 'dao',
        'AnimalGenericoDAO' => 'dao',
        'ComentarioDAO'     => 'dao',
        'HistoricoSaudeDAO' => 'dao',
        'Campo'             => 'forms',
        'CampoTexto'        => 'forms/campos',
        'CampoEmail'        => 'forms/campos',
        'CampoTextarea'     => 'forms/campos',
        'CampoSelect'       => 'forms/campos',
        'CampoNumero'       => 'forms/campos',
        'CampoData'         => 'forms/campos',
        'CampoArquivo'      => 'forms/campos',
        'CampoCheckbox'     => 'forms/campos',
        'FormGenerico'                  => 'forms',
        'FormsCadastroCachorro'         => 'forms',
        'FormsCadastroGato'             => 'forms',
        'FormsCadastroPapagaio'         => 'forms',
        'FormsCadastroAnimalGenerico'   => 'forms',
        'FormsComentario'               => 'forms',
        'FormsHistoricoSaude'           => 'forms',
        'AnimalController'              => 'controllers',
        'ComentarioController'          => 'controllers',
        'HistoricoController'           => 'controllers',
        'UploadService'                 => 'services',
        'FormFactory'                   => 'factories',
        'AnimalFactory'                 => 'factories',
        'DAOFactory'                    => 'factories',
    ];

    if (isset($mapa[$classe])) {
        require_once __DIR__ . '/' . $mapa[$classe] . '/' . $classe . '.php';
    }
});

$router = new Router();
$router->despachar();
