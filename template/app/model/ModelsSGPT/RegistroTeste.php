<?php
class RegistroTeste extends TRecord
{
    const TABLENAME  = 'registro_teste';
    const PRIMARYKEY = 'id_registro_teste';
    const IDPOLICY   = 'serial';

    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('id_execucao_teste');
        parent::addAttribute('ds_resultado');
        parent::addAttribute('dt_registro');
    }
}
