<?php

class FormatarCampos
{
    //função do setTransformer para formatar campos de valores monetários
    public static function formatarValor($value)
    {
        return 'R$ ' . number_format($value, 2, ',', '.');
    }

    //formatar cpf
    public static function formatarCpf($value)
    {
        if (strlen($value) === 11) {
            return substr($value, 0, 3) . '.' .
                   substr($value, 3, 3) . '.' .
                   substr($value, 6, 3) . '-' .
                   substr($value, 9, 2);
        }
        return $value;
    }

    //formartar data
    public static function formatarData($value)
    {
        if (strlen($value) === 10) {
            return substr($value, 8, 2) . '/' .
                   substr($value, 5, 2) . '/' .
                   substr($value, 0, 4);
        }
        return $value;
    }

}