<?php

class UploadService
{
    private string $diretorioBase;
    private array  $tiposPermitidos = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    private int    $tamanhoMaximo   = 5_000_000;

    public function __construct(string $diretorioBase)
    {
        $this->diretorioBase = rtrim($diretorioBase, '/\\');
    }

    public function processar(array $fileEntry, string $prefixo = 'animal'): string
    {
        if ($fileEntry['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException("Erro no upload: código {$fileEntry['error']}");
        }

        if (!in_array($fileEntry['type'], $this->tiposPermitidos, true)) {
            throw new InvalidArgumentException("Tipo de arquivo não permitido: {$fileEntry['type']}");
        }

        if ($fileEntry['size'] > $this->tamanhoMaximo) {
            throw new InvalidArgumentException('Arquivo excede o tamanho máximo de 5 MB.');
        }

        $ext      = strtolower(pathinfo($fileEntry['name'], PATHINFO_EXTENSION));
        $nomeUnico = $prefixo . '_' . uniqid() . '.' . $ext;
        $destino  = $this->diretorioBase . '/' . $nomeUnico;

        if (!move_uploaded_file($fileEntry['tmp_name'], $destino)) {
            throw new RuntimeException('Falha ao mover o arquivo enviado.');
        }

        return 'uploads/animais/' . $nomeUnico;
    }

    public function temArquivo(array $fileEntry): bool
    {
        return isset($fileEntry['error']) && $fileEntry['error'] !== UPLOAD_ERR_NO_FILE;
    }

    public function remover(string $caminhoRelativo): void
    {
        $absoluto = __DIR__ . '/../' . $caminhoRelativo;
        if (is_file($absoluto)) {
            unlink($absoluto);
        }
    }
}
