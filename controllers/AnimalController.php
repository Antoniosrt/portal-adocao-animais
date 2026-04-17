<?php

class AnimalController extends Controller
{
    private IAnimalDAO $dao;

    public function __construct(IAnimalDAO $dao)
    {
        $this->dao = $dao;
    }

    public function despachar(string $acao): void
    {
        match($acao) {
            'detalhe' => $this->detalhe((int)($_GET['id'] ?? 0)),
            'criar'   => $this->criar(),
            'editar'  => $this->editar((int)($_GET['id'] ?? 0)),
            'deletar' => $this->deletar((int)($_GET['id'] ?? 0)),
            default   => $this->listar(),
        };
    }

    private function listar(): void
    {
        $pdo     = Database::getInstance();
        $dao     = new AnimalDAO($pdo);
        $animais = $dao->listarTodos();
        $this->renderizar('animal/listar', ['animais' => $animais]);
    }

    private function detalhe(int $id): void
    {
        $animal = $this->dao->buscarPorId($id);
        if (!$animal) { $this->notFound(); return; }

        $pdo         = Database::getInstance();
        $comentarios = (new ComentarioDAO($pdo))->buscarPorAnimal($id);
        $historico   = (new HistoricoSaudeDAO($pdo))->buscarPorAnimal($id);
        $formComent  = new FormsComentario('index.php?acao=comentario_adicionar&animal_id=' . $id);
        $formHist    = new FormsHistoricoSaude('index.php?acao=historico_adicionar&animal_id=' . $id);

        $this->renderizar('animal/detalhe', compact('animal', 'comentarios', 'historico', 'formComent', 'formHist'));
    }

    private function criar(): void
    {
        $tipo   = $_GET['tipo'] ?? 'cachorro';
        $action = "index.php?acao=criar&tipo={$tipo}";
        $form   = FormFactory::criarFormAnimal($tipo, $action);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $postValido = $form->validar($_POST);
            $fotoValida = $this->validarFoto($form, $_FILES);

            if ($postValido && $fotoValida) {
                $animal = AnimalFactory::criarDePost($tipo, $_POST, $_FILES);
                $id     = $this->dao->inserir($animal);
                $this->redirect("index.php?acao=detalhe&tipo={$tipo}&id={$id}");
                return;
            }
            $form->preencherValores($_POST);
        }

        $this->renderizar('animal/formulario', ['form' => $form, 'tipo' => $tipo, 'titulo' => 'Cadastrar Animal']);
    }

    private function editar(int $id): void
    {
        $animal = $this->dao->buscarPorId($id);
        if (!$animal) { $this->notFound(); return; }

        $tipo   = strtolower($animal->getEspecie());
        $action = "index.php?acao=editar&tipo={$tipo}&id={$id}";
        $form   = FormFactory::criarFormAnimal($tipo, $action);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $postValido = $form->validar($_POST);
            $fotoValida = $this->validarFoto($form, $_FILES);

            if ($postValido && $fotoValida) {
                $fotoAnterior = $animal->getFotoPath();

                $animal->setNome($_POST['nome'] ?? '');
                $animal->setIdadeMeses((int)($_POST['idade_meses'] ?? 0));
                $animal->setDescricao($_POST['descricao'] ?? '');
                $animal->setStatus($_POST['status'] ?? 'disponivel');

                if (!empty($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
                    $upload = new UploadService(__DIR__ . '/../uploads/animais');
                    $animal->setFotoPath($upload->processar($_FILES['foto'], $tipo));
                    if ($fotoAnterior) {
                        (new UploadService(__DIR__ . '/../uploads/animais'))->remover($fotoAnterior);
                    }
                }

                $this->atualizarEspecificos($animal, $tipo, $_POST);
                $this->dao->atualizar($animal);
                $this->redirect("index.php?acao=detalhe&tipo={$tipo}&id={$id}");
                return;
            }
            $form->preencherValores($_POST);
        } else {
            $dados = array_merge($animal->toArray(), $animal->getAtributosEspecificos());
            $form->preencherValores($dados);
        }

        $this->renderizar('animal/formulario', ['form' => $form, 'tipo' => $tipo, 'titulo' => 'Editar Animal', 'animal' => $animal]);
    }

    private function deletar(int $id): void
    {
        $animal = $this->dao->buscarPorId($id);
        if ($animal && $animal->getFotoPath()) {
            (new UploadService(__DIR__ . '/../uploads/animais'))->remover($animal->getFotoPath());
        }
        $this->dao->deletar($id);
        $this->redirect('index.php?acao=listar');
    }

    // Delega a validação do arquivo ao CampoArquivo do formulário.
    // Separado de form->validar() porque $_FILES não faz parte de $_POST.
    private function validarFoto(FormGenerico $form, array $files): bool
    {
        $campoFoto = $form->getCampoArquivo();
        if (!$campoFoto) return true;

        $fileEntry = $files[$campoFoto->getNome()] ?? ['error' => UPLOAD_ERR_NO_FILE];
        return $campoFoto->validarArquivo($fileEntry);
    }

    private function atualizarEspecificos(Animal $animal, string $tipo, array $post): void
    {
        match($tipo) {
            'cachorro' => $this->aplicarCachorro($animal, $post),
            'gato'     => $this->aplicarGato($animal, $post),
            'papagaio' => $this->aplicarPapagaio($animal, $post),
            default    => $this->aplicarGenerico($animal, $post),
        };
    }

    private function aplicarCachorro(Animal $c, array $p): void
    {
        /** @var Cachorro $c */
        $c->setRaca($p['raca'] ?? '');
        $c->setPorte($p['porte'] ?? 'medio');
        $c->setVacinado(isset($p['vacinado']) && $p['vacinado'] === '1');
    }

    private function aplicarGato(Animal $g, array $p): void
    {
        /** @var Gato $g */
        $g->setRaca($p['raca'] ?? '');
        $g->setPelagem($p['pelagem'] ?? 'curto');
        $g->setCastrado(isset($p['castrado']) && $p['castrado'] === '1');
    }

    private function aplicarPapagaio(Animal $pa, array $p): void
    {
        /** @var Papagaio $pa */
        $pa->setEspecieAve($p['especie_ave'] ?? '');
        $pa->setCorPlumagem($p['cor_plumagem'] ?? '');
        $pa->setFalaHumana(isset($p['fala_humana']) && $p['fala_humana'] === '1');
    }

    private function aplicarGenerico(Animal $ag, array $p): void
    {
        /** @var AnimalGenerico $ag */
        $ag->setEspecieDescricao($p['especie_descricao'] ?? '');
    }
}
