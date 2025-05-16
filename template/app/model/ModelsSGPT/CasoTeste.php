<?php
class CasoTeste extends TRecord
{
    const TABLENAME  = 'caso_teste';
    const PRIMARYKEY = 'id_caso_teste';
    const IDPOLICY   = 'serial';

    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('id_plano_teste');
        parent::addAttribute('nm_titulo');
        parent::addAttribute('ds_caso');
        parent::addAttribute('dt_criacao');
    }
}
