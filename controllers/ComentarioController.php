<?php

class ComentarioController extends Controller
{
    private IComentarioDAO $dao;

    public function __construct(IComentarioDAO $dao)
    {
        $this->dao = $dao;
    }

    public function despachar(string $acao): void
    {
        match($acao) {
            'comentario_deletar'   => $this->deletar((int)($_GET['id'] ?? 0)),
            default                => $this->adicionar(),
        };
    }

    private function adicionar(): void
    {
        $animalId = (int)($_GET['animal_id'] ?? 0);
        $form     = new FormsComentario("index.php?acao=comentario_adicionar&animal_id={$animalId}");

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $form->validar($_POST)) {
            $comentario = new Comentario();
            $comentario->setAnimalId($animalId);
            $comentario->setAutor($_POST['autor'] ?? '');
            $comentario->setTexto($_POST['texto'] ?? '');
            $this->dao->inserir($comentario);
        }

        $this->redirect("index.php?acao=detalhe&id={$animalId}#comentarios");
    }

    private function deletar(int $id): void
    {
        $animalId = (int)($_GET['animal_id'] ?? 0);
        $this->dao->deletar($id);
        $this->redirect("index.php?acao=detalhe&id={$animalId}#comentarios");
    }
}
