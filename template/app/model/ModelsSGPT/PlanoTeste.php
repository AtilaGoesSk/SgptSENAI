<?php
class PlanoTeste extends TRecord
{
    const TABLENAME  = 'plano_teste';
    const PRIMARYKEY = 'id_plano_teste';
    const IDPOLICY   = 'serial';

    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nm_titulo');
        parent::addAttribute('ds_plano');
        parent::addAttribute('id_usuario');
        parent::addAttribute('dt_criacao');
    }
}
