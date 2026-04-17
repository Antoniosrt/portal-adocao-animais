<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header">
                <h4 class="mb-0"><?= htmlspecialchars($titulo ?? 'Formulário') ?></h4>
            </div>
            <div class="card-body">
                <?= $form->renderizar() /* FormGenerico renderiza polimorficamente todos os campos */ ?>
            </div>
        </div>
        <a href="index.php?acao=listar" class="btn btn-link mt-2">← Voltar</a>
    </div>
</div>
