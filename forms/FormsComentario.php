<?php

class FormsComentario extends FormGenerico
{
    public function definirCampos(): void
    {
        $this->adicionarCampo(new CampoTexto('autor', 'Seu nome', true, ['min' => 2, 'max' => 60]));
        $this->adicionarCampo(new CampoTextarea('texto', 'Comentário', true, ['min' => 3, 'max' => 1000], 3));
    }
}
