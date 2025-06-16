<?php
class ProjetoTeste extends TRecord
{
    const TABLENAME  = 'projeto_teste';
    const PRIMARYKEY = 'id_projeto';
    const IDPOLICY   = 'serial'; // ou 'identity' se for SQL Server

    // Relacionamentos
    private $usuario;

    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);

        parent::addAttribute('nm_projeto');    // Nome do projeto
        parent::addAttribute('ds_projeto');    // Descrição
        parent::addAttribute('nu_versao');     // Versão
        parent::addAttribute('id_usuario');    // FK para usuário
        parent::addAttribute('dt_criacao');    // Data de criação
    }

    /**
     * Retorna o usuário associado (Lazy Load)
     */
    public function get_usuario()
    {
        if (empty($this->usuario) && !empty($this->id_usuario))
        {
            $this->usuario = new SystemUsers($this->id_usuario);
        }
        return $this->usuario;
    }

    public function onBeforeDelete()
    {
        if(PlanoTeste::where('id_projeto', '=', $this->id_projeto)->count() > 0 )
        {
            throw new Exception('Não é possível excluir este projeto, pois ele está vinculado a um plano de teste.');
        }
    }
}
