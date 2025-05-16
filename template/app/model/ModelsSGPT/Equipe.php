<?php
class Equipe extends TRecord
{
    const TABLENAME  = 'equipe';
    const PRIMARYKEY = 'id_equipe';
    const IDPOLICY   = 'serial';

    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nm_equipe');
        parent::addAttribute('dt_criacao');
    }
}
