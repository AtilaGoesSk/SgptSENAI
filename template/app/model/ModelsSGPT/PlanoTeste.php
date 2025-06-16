<?php

class PlanoTeste extends TRecord
{
    const TABLENAME  = 'plano_teste';
    const PRIMARYKEY = 'id_plano_teste';
    const IDPOLICY   =  'serial';

    private $projeto;

    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('id_projeto');
        parent::addAttribute('nm_plano');
        parent::addAttribute('ds_plano');
        parent::addAttribute('dt_inicio');
        parent::addAttribute('dt_final');
        parent::addAttribute('dt_criacao');
    }

    public function get_projeto()
    {
        if (empty($this->projeto)) {
            $this->projeto = new ProjetoTeste($this->id_projeto);
        }
        return $this->projeto;
    }

    public function onBeforeDelete()
    {
        CasoTeste::where('id_plano_teste', '=', $this->id_plano_teste)->delete();
    }
}
