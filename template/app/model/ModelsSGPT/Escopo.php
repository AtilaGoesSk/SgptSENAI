<?php
class Escopo extends TRecord
{
    const TABLENAME  = 'escopo';
    const PRIMARYKEY = 'id_escopo';
    const IDPOLICY   = 'serial';

    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('ds_escopo');
        parent::addAttribute('id_usuario');
        parent::addAttribute('dt_criacao');
    }
}

