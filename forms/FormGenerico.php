<?php

// Classe base abstrata para todos os formulários do sistema.
//
// Implementa o padrão Template Method: o construtor chama definirCampos(),
// que é abstrato e forçadamente implementado por cada subclasse. O pai define
// o esqueleto do algoritmo (construir → definir campos → pronto para usar),
// enquanto a subclasse preenche apenas a parte variável (quais campos existem).
//
// renderizar() e validar() são concretos e funcionam de forma genérica para
// qualquer subclasse: iteram $campos chamando $campo->renderizar() e
// $campo->validar() polimorficamente, sem saber o tipo concreto de cada campo.
abstract class FormGenerico
{
    protected array  $campos = [];
    protected string $action;
    protected string $method = 'POST';

    abstract public function definirCampos(): void;

    public function __construct(string $action)
    {
        $this->action = $action;
        $this->definirCampos();
    }

    // Gera o HTML completo do formulário iterando $campos polimorficamente.
    // Cada Campo sabe renderizar a si mesmo — FormGenerico não conhece os tipos concretos.
    // Detecta automaticamente se há CampoArquivo para adicionar enctype multipart.
    public function renderizar(): string
    {
        $enctype = $this->temCampoArquivo() ? ' enctype="multipart/form-data"' : '';
        $html    = "<form action=\"{$this->action}\" method=\"{$this->method}\"{$enctype}>";

        foreach ($this->campos as $campo) {
            $html .= '<div class="campo-wrapper mb-3">';
            $html .= $campo->renderizar();
            foreach ($campo->getErros() as $erro) {
                $html .= "<span class=\"erro text-danger d-block small\">{$erro}</span>";
            }
            $html .= '</div>';
        }

        $html .= '<button type="submit" class="btn btn-primary">Salvar</button>';
        $html .= '</form>';
        return $html;
    }

    // Valida os dados de $_POST iterando $campos polimorficamente.
    // CampoArquivo é pulado aqui porque $_FILES tem estrutura diferente de $_POST —
    // sua validação é feita separadamente no controller via validarArquivo().
    public function validar(array $dados): bool
    {
        $valido = true;
        foreach ($this->campos as $campo) {
            if ($campo instanceof CampoArquivo) {
                continue;
            }
            $valor = $dados[$campo->getNome()] ?? null;
            if (!$campo->validar($valor)) {
                $valido = false;
            }
        }
        return $valido;
    }

    // Repopula o formulário com os dados enviados para reexibir com erros inline.
    public function preencherValores(array $dados): void
    {
        foreach ($this->campos as $campo) {
            $nome = $campo->getNome();
            if (array_key_exists($nome, $dados)) {
                $campo->setValor($dados[$nome]);
            }
        }
    }

    public function getCampos(): array { return $this->campos; }

    public function getCampoArquivo(): ?CampoArquivo
    {
        foreach ($this->campos as $campo) {
            if ($campo instanceof CampoArquivo) return $campo;
        }
        return null;
    }

    protected function adicionarCampo(Campo $campo): void
    {
        $this->campos[$campo->getNome()] = $campo;
    }

    private function temCampoArquivo(): bool
    {
        foreach ($this->campos as $campo) {
            if ($campo instanceof CampoArquivo) return true;
        }
        return false;
    }
}
