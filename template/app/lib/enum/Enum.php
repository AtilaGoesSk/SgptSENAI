<?php

class Enum
{

    const TP_STATUS =
    [
        '1' => 'Pendente',
        '2' => 'Aprovado',
        '3' => 'Reprovado',
        '4' => 'Cancelado',
        '5' => 'Em Análise',
        '6' => 'Finalizado',
    ];

    const TP_CARGO =
    [
        1 => 'QA',
        2 => 'Programador',
        3 => 'Gerente de Projetos',
    ];
    
    const TP_CATEGORIA =
    [
        '1' => 'Funcional',
        '2' => 'Não Funcional',
        '3' => 'Requisitos',
        '4' => 'Interface',
        '5' => 'Performance',
        '6' => 'Segurança',
        '7' => 'Usabilidade',
        '8' => 'Compatibilidade',
    ];
}