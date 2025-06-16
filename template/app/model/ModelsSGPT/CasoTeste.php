<?php

class CasoTeste extends TRecord
{
    const TABLENAME  = 'caso_teste';
    const PRIMARYKEY = 'id_caso_teste';
    const IDPOLICY   =  'serial';

    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('id_plano_teste');
        parent::addAttribute('nm_caso_teste');
        parent::addAttribute('ds_caso_teste');
        parent::addAttribute('tp_categoria');
        parent::addAttribute('ds_resultado_esperado');
        parent::addAttribute('tp_status');
        parent::addAttribute('dt_criacao');
    }
    public function get_qtd_casos()
    {
        return CasoTeste::where('id_plano_teste', '=', $this->id_plano_teste)->count();
    }
}
