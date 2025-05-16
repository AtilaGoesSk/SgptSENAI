<?php
class AnaliseRegistro extends TRecord
{
    const TABLENAME  = 'analise_registro';
    const PRIMARYKEY = 'id_analise_registro';
    const IDPOLICY   = 'serial';

    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('id_registro_teste');
        parent::addAttribute('id_usuario');
        parent::addAttribute('ds_comentario');
        parent::addAttribute('dt_analise');
    }
}
