<?php

class Router
{
    public function despachar(): void
    {
        $acao = $_GET['acao'] ?? 'listar';
        $tipo = $_GET['tipo'] ?? 'cachorro';

        // Rota de comentários
        if ($acao === 'comentario_adicionar' || $acao === 'comentario_deletar') {
            $pdo        = Database::getInstance();
            $dao        = new ComentarioDAO($pdo);
            $controller = new ComentarioController($dao);
            $controller->despachar($acao);
            return;
        }

        // Rota de histórico de saúde
        if ($acao === 'historico_adicionar' || $acao === 'historico_deletar') {
            $pdo        = Database::getInstance();
            $dao        = new HistoricoSaudeDAO($pdo);
            $controller = new HistoricoController($dao);
            $controller->despachar($acao);
            return;
        }

        // Rota principal de animais
        $pdo        = Database::getInstance();
        $dao        = DAOFactory::criarAnimalDAO($tipo, $pdo);
        $controller = new AnimalController($dao);
        $controller->despachar($acao);
    }
}
