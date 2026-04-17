<?php

abstract class Controller
{
    abstract public function despachar(string $acao): void;

    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }

    protected function renderizar(string $view, array $dados = []): void
    {
        extract($dados);
        $viewsBase = __DIR__ . '/../views/';
        require_once $viewsBase . 'layout/header.php';
        require_once $viewsBase . $view . '.php';
        require_once $viewsBase . 'layout/footer.php';
    }

    protected function notFound(): void
    {
        http_response_code(404);
        require_once __DIR__ . '/../views/errors/404.php';
        exit;
    }
}
