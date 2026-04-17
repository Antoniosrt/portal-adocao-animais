<?php

class HistoricoController extends Controller
{
    private IHistoricoDAO $dao;

    public function __construct(IHistoricoDAO $dao)
    {
        $this->dao = $dao;
    }

    public function despachar(string $acao): void
    {
        match($acao) {
            'historico_deletar' => $this->deletar((int)($_GET['id'] ?? 0)),
            default             => $this->adicionar(),
        };
    }

    private function adicionar(): void
    {
        $animalId = (int)($_GET['animal_id'] ?? 0);
        $form     = new FormsHistoricoSaude("index.php?acao=historico_adicionar&animal_id={$animalId}");

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $form->validar($_POST)) {
            $registro = new HistoricoSaude();
            $registro->setAnimalId($animalId);
            $registro->setDataEvento($_POST['data_evento'] ?? '');
            $registro->setTipo($_POST['tipo'] ?? 'outro');
            $registro->setDescricao($_POST['descricao'] ?? '');
            $registro->setVeterinario($_POST['veterinario'] ?? '');
            $this->dao->inserir($registro);
        }

        $this->redirect("index.php?acao=detalhe&id={$animalId}#historico");
    }

    private function deletar(int $id): void
    {
        $animalId = (int)($_GET['animal_id'] ?? 0);
        $this->dao->deletar($id);
        $this->redirect("index.php?acao=detalhe&id={$animalId}#historico");
    }
}
