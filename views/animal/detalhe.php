<div class="row">
    <!-- Coluna principal: foto + dados -->
    <div class="col-md-5 mb-4">
        <?php if ($animal->getFotoPath()): ?>
            <img src="<?= htmlspecialchars($animal->getFotoPath()) ?>" alt="Foto" class="foto-animal mb-3">
        <?php else: ?>
            <div class="bg-light d-flex align-items-center justify-content-center rounded mb-3" style="height:300px;color:#aaa;">Sem foto</div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-body">
                <?= $animal->renderizarBadge() ?>
                <h2 class="h4 mt-2"><?= htmlspecialchars($animal->getNome()) ?></h2>
                <p><strong>Idade:</strong> <?= $animal->getIdadeMeses() ?> meses</p>
                <p><strong>Status:</strong>
                    <?= $animal->getStatus() === 'adotado'
                        ? '<span class="badge bg-secondary">Adotado</span>'
                        : '<span class="badge bg-success">Disponível</span>' ?>
                </p>
                <?php if ($animal->getDescricao()): ?>
                    <hr>
                    <p><?= nl2br(htmlspecialchars($animal->getDescricao())) ?></p>
                <?php endif; ?>

                <!-- Atributos específicos da espécie -->
                <?php $esp = $animal->getAtributosEspecificos(); ?>
                <?php if (!empty($esp)): ?>
                <hr>
                <ul class="list-unstyled mb-0">
                    <?php foreach ($esp as $chave => $valor): ?>
                        <li><strong><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $chave))) ?>:</strong>
                            <?= is_bool($valor) || $valor === 0 || $valor === 1
                                ? ($valor ? 'Sim' : 'Não')
                                : htmlspecialchars((string)$valor) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </div>
            <div class="card-footer d-flex gap-2">
                <a href="index.php?acao=editar&tipo=<?= strtolower($animal->getEspecie()) ?>&id=<?= $animal->getId() ?>" class="btn btn-sm btn-outline-secondary">Editar</a>
                <a href="index.php?acao=deletar&tipo=<?= strtolower($animal->getEspecie()) ?>&id=<?= $animal->getId() ?>"
                   class="btn btn-sm btn-outline-danger"
                   onclick="return confirm('Excluir este animal?')">Excluir</a>
                <a href="index.php?acao=listar" class="btn btn-sm btn-link">← Voltar</a>
            </div>
        </div>
    </div>

    <!-- Coluna lateral: histórico + comentários -->
    <div class="col-md-7">

        <!-- Histórico de Saúde -->
        <div class="card shadow-sm mb-4" id="historico">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Histórico de Saúde</h5>
                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#formHistorico">+ Registrar</button>
            </div>
            <div class="collapse" id="formHistorico">
                <div class="card-body border-bottom">
                    <?= $formHist->renderizar() ?>
                </div>
            </div>
            <ul class="list-group list-group-flush">
                <?php if (empty($historico)): ?>
                    <li class="list-group-item text-muted">Nenhum registro de saúde.</li>
                <?php else: ?>
                    <?php foreach ($historico as $reg): ?>
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <span><strong><?= htmlspecialchars($reg->getTipo()) ?></strong> — <?= htmlspecialchars($reg->getDataEvento()) ?></span>
                            <a href="index.php?acao=historico_deletar&id=<?= $reg->getId() ?>&animal_id=<?= $animal->getId() ?>"
                               class="text-danger small" onclick="return confirm('Remover registro?')">✕</a>
                        </div>
                        <p class="mb-1 small"><?= nl2br(htmlspecialchars($reg->getDescricao())) ?></p>
                        <?php if ($reg->getVeterinario()): ?>
                            <small class="text-muted">Vet: <?= htmlspecialchars($reg->getVeterinario()) ?></small>
                        <?php endif; ?>
                    </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Comentários -->
        <div class="card shadow-sm" id="comentarios">
            <div class="card-header">
                <h5 class="mb-0">Comentários</h5>
            </div>
            <div class="card-body border-bottom">
                <?= $formComent->renderizar() ?>
            </div>
            <ul class="list-group list-group-flush">
                <?php if (empty($comentarios)): ?>
                    <li class="list-group-item text-muted">Nenhum comentário ainda.</li>
                <?php else: ?>
                    <?php foreach ($comentarios as $com): ?>
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <strong><?= htmlspecialchars($com->getAutor()) ?></strong>
                            <span class="d-flex align-items-center gap-2">
                                <small class="text-muted"><?= htmlspecialchars($com->getCriadoEm()) ?></small>
                                <a href="index.php?acao=comentario_deletar&id=<?= $com->getId() ?>&animal_id=<?= $animal->getId() ?>"
                                   class="text-danger small" onclick="return confirm('Excluir comentário?')">✕</a>
                            </span>
                        </div>
                        <p class="mb-0"><?= nl2br(htmlspecialchars($com->getTexto())) ?></p>
                    </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>

    </div>
</div>
