<?php

class AnimalFactory
{
    /**
     * Cria e popula o objeto Animal correto a partir dos dados de $_POST e $_FILES.
     * Delega o upload ao UploadService quando há foto enviada.
     */
    public static function criarDePost(string $tipo, array $post, array $files = []): Animal
    {
        $animal = match(strtolower($tipo)) {
            'cachorro' => new Cachorro(),
            'gato'     => new Gato(),
            'papagaio' => new Papagaio(),
            default    => new AnimalGenerico(),
        };

        // Campos base comuns a todos
        $animal->setNome($post['nome'] ?? '');
        $animal->setIdadeMeses((int)($post['idade_meses'] ?? 0));
        $animal->setDescricao($post['descricao'] ?? '');
        $animal->setStatus($post['status'] ?? 'disponivel');

        // Upload de foto
        if (!empty($files['foto']) && $files['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
            $upload   = new UploadService(__DIR__ . '/../uploads/animais');
            $fotoPath = $upload->processar($files['foto'], strtolower($tipo));
            $animal->setFotoPath($fotoPath);
        }

        // Campos específicos por espécie
        match(strtolower($tipo)) {
            'cachorro' => self::preencherCachorro($animal, $post),
            'gato'     => self::preencherGato($animal, $post),
            'papagaio' => self::preencherPapagaio($animal, $post),
            default    => self::preencherGenerico($animal, $post),
        };

        return $animal;
    }

    private static function preencherCachorro(Cachorro $c, array $p): void
    {
        $c->setRaca($p['raca'] ?? '');
        $c->setPorte($p['porte'] ?? 'medio');
        $c->setVacinado(isset($p['vacinado']) && $p['vacinado'] === '1');
    }

    private static function preencherGato(Gato $g, array $p): void
    {
        $g->setRaca($p['raca'] ?? '');
        $g->setPelagem($p['pelagem'] ?? 'curto');
        $g->setCastrado(isset($p['castrado']) && $p['castrado'] === '1');
    }

    private static function preencherPapagaio(Papagaio $pa, array $p): void
    {
        $pa->setEspecieAve($p['especie_ave'] ?? '');
        $pa->setCorPlumagem($p['cor_plumagem'] ?? '');
        $pa->setFalaHumana(isset($p['fala_humana']) && $p['fala_humana'] === '1');
    }

    private static function preencherGenerico(AnimalGenerico $ag, array $p): void
    {
        $ag->setEspecieDescricao($p['especie_descricao'] ?? '');
    }
}
