<?php
class ExecucaoTeste extends TRecord
{
    const TABLENAME  = 'execucao_teste';
    const PRIMARYKEY = 'id_execucao_teste';
    const IDPOLICY   = 'serial';

    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('id_caso_teste');
        parent::addAttribute('id_usuario');
        parent::addAttribute('tp_execucao');
        parent::addAttribute('dt_execucao');
    }
}
