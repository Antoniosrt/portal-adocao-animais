<?php

class CampoArquivo extends Campo
{
    private array $tiposPermitidos;
    private int   $tamanhoMaxBytes;

    public function __construct(
        string $nome,
        string $rotulo,
        bool   $obrigatorio     = false,
        array  $tiposPermitidos = ['image/jpeg', 'image/png', 'image/webp'],
        int    $tamanhoMaxBytes = 5_000_000
    ) {
        parent::__construct($nome, $rotulo, $obrigatorio);
        $this->tiposPermitidos = $tiposPermitidos;
        $this->tamanhoMaxBytes = $tamanhoMaxBytes;
    }

    public function renderizar(): string
    {
        $req = $this->obrigatorio ? ' required' : '';
        return $this->renderizarRotulo()
            . "<input type=\"file\" class=\"form-control\" id=\"{$this->nome}\""
            . " name=\"{$this->nome}\" accept=\"image/*\"{$req}>";
    }

    // Valida um entry de $_FILES em vez de um valor escalar de $_POST.
    // Separado de validar() porque $_FILES tem estrutura própria (error, type, size, tmp_name)
    // que não é compatível com o contrato genérico de Campo::validar(mixed $valor).
    // O controller chama este método explicitamente após FormGenerico::validar($_POST).
    public function validarArquivo(array $fileEntry): bool
    {
        $this->erros = [];

        if ($fileEntry['error'] === UPLOAD_ERR_NO_FILE) {
            if ($this->obrigatorio) {
                $this->erros[] = "{$this->rotulo} é obrigatória.";
                return false;
            }
            return true;
        }

        if ($fileEntry['error'] !== UPLOAD_ERR_OK) {
            $this->erros[] = "Erro no upload da {$this->rotulo}: código {$fileEntry['error']}.";
            return false;
        }

        if (!in_array($fileEntry['type'], $this->tiposPermitidos, true)) {
            $this->erros[] = "Tipo de arquivo inválido. Use JPG, PNG ou WebP.";
            return false;
        }

        if ($fileEntry['size'] > $this->tamanhoMaxBytes) {
            $mb = round($this->tamanhoMaxBytes / 1_000_000, 1);
            $this->erros[] = "Arquivo excede o tamanho máximo de {$mb} MB.";
            return false;
        }

        return true;
    }

    // CampoArquivo não participa do loop genérico de FormGenerico::validar()
    public function validar(mixed $valor): bool
    {
        return true;
    }
}
