<?php
class UsuarioEquipe extends TRecord
{
    const TABLENAME  = 'usuario_equipe';
    const PRIMARYKEY = 'id_usuario_equipe';
    const IDPOLICY   = 'serial';

    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('id_usuario');
        parent::addAttribute('id_equipe');
    }
}
