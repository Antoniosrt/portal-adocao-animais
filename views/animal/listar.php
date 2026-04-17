<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Animais para Adoção</h1>
    <div class="dropdown">
        <button class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">+ Cadastrar</button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="index.php?acao=criar&tipo=cachorro">Cachorro</a></li>
            <li><a class="dropdown-item" href="index.php?acao=criar&tipo=gato">Gato</a></li>
            <li><a class="dropdown-item" href="index.php?acao=criar&tipo=papagaio">Papagaio</a></li>
            <li><a class="dropdown-item" href="index.php?acao=criar&tipo=generico">Outro Animal</a></li>
        </ul>
    </div>
</div>

<?php if (empty($animais)): ?>
    <div class="alert alert-info">Nenhum animal cadastrado ainda.</div>
<?php else: ?>
<div class="row row-cols-1 row-cols-md-3 g-4">
    <?php foreach ($animais as $animal): ?>
    <div class="col">
        <div class="card h-100 card-animal shadow-sm">
            <?php if ($animal->getFotoPath()): ?>
                <img src="<?= htmlspecialchars($animal->getFotoPath()) ?>" alt="Foto de <?= htmlspecialchars($animal->getNome()) ?>">
            <?php else: ?>
                <div class="bg-light d-flex align-items-center justify-content-center" style="height:200px;color:#aaa;">Sem foto</div>
            <?php endif; ?>
            <div class="card-body">
                <?= $animal->renderizarBadge() /* polimorfismo */ ?>
                <h5 class="card-title mt-2"><?= htmlspecialchars($animal->getNome()) ?></h5>
                <p class="card-text text-muted small"><?= $animal->getIdadeMeses() ?> meses</p>
                <?php if ($animal->getStatus() === 'adotado'): ?>
                    <span class="badge bg-secondary">Adotado</span>
                <?php else: ?>
                    <span class="badge bg-success">Disponível</span>
                <?php endif; ?>
            </div>
            <div class="card-footer d-flex gap-2">
                <a href="index.php?acao=detalhe&tipo=<?= strtolower($animal->getEspecie()) ?>&id=<?= $animal->getId() ?>" class="btn btn-sm btn-primary">Ver</a>
                <a href="index.php?acao=editar&tipo=<?= strtolower($animal->getEspecie()) ?>&id=<?= $animal->getId() ?>" class="btn btn-sm btn-outline-secondary">Editar</a>
                <a href="index.php?acao=deletar&tipo=<?= strtolower($animal->getEspecie()) ?>&id=<?= $animal->getId() ?>"
                   class="btn btn-sm btn-outline-danger"
                   onclick="return confirm('Excluir <?= htmlspecialchars($animal->getNome()) ?>?')">Excluir</a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
