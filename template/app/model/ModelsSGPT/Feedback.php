<?php
class Feedback extends TRecord
{
    const TABLENAME  = 'feedback';
    const PRIMARYKEY = 'id_feedback';
    const IDPOLICY   = 'serial';

    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('id_usuario_remetente');
        parent::addAttribute('id_usuario_destinatario');
        parent::addAttribute('ds_mensagem');
        parent::addAttribute('dt_criacao');
    }
}
