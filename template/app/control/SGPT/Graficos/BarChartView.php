<?php

class BarChartView extends TPage
{
    function __construct( $show_breadcrumb = true )
    {
        parent::__construct();

        $html = new THtmlRenderer('app/resources/google_bar_chart.html');

        // Dados dinâmicos
        $data = [];
        $data[] = ['Status', 'Quantidade']; // Cabeçalho

        TTransaction::open('SGPT_DB'); // Trocar pelo nome da conexão

        $conn = TTransaction::get();
        $result = $conn->query("
            SELECT tp_status, COUNT(*) as quantidade 
            FROM caso_teste
            GROUP BY tp_status
        ");

        foreach ($result as $row) {
            $label = Enum::TP_STATUS[$row['tp_status']] ?? 'Desconhecido';
            $data[] = [ $label, (int) $row['quantidade'] ];
        }

        TTransaction::close();

        // Renderização do gráfico
        $html->enableSection('main', [
            'data'   => json_encode($data),
            'width'  => '100%',
            'height' => '400px',
            'title'  => 'Casos de Teste por Status',
            'ytitle' => 'Quantidade',
            'xtitle' => 'Status',
            'uniqid' => uniqid()
        ]);

        $container = new TVBox;
        $container->style = 'width: 100%';

        if ($show_breadcrumb) {
            $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        }

        $container->add($html);

        parent::add($container);
    }
}
