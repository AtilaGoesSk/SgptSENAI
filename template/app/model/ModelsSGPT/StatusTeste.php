<?php
class StatusTeste extends TRecord
{
    const TABLENAME  = 'status_teste';
    const PRIMARYKEY = 'id_status_teste';
    const IDPOLICY   = 'serial';

    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('id_analise_registro');
        parent::addAttribute('tp_status');
        parent::addAttribute('dt_atualizacao');
    }
}
