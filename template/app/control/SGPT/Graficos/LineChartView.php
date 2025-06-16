<?php

class LineChartView extends TPage
{
    function __construct( $show_breadcrumb = true )
    {
        parent::__construct();

        $html = new THtmlRenderer('app/resources/google_line_chart.html');

        $data = [];
        $data[] = ['Mês', 'Planos de Teste']; // Cabeçalho do gráfico

        TTransaction::open('SGPT_DB'); // Troque pelo nome da conexão

        $conn = TTransaction::get();
        $result = $conn->query("
            SELECT 
                TO_CHAR(dt_criacao, 'YYYY-MM') AS mes,
                SUM(CASE WHEN tp_status = '2' THEN 1 ELSE 0 END) AS aprovados,
                SUM(CASE WHEN tp_status = '3' THEN 1 ELSE 0 END) AS reprovados
            FROM caso_teste
            GROUP BY mes
            ORDER BY mes
        ");

        $data = [];
        $data[] = ['Mês', 'Aprovados', 'Reprovados'];

        foreach ($result as $row) {
            $data[] = [ $row['mes'], (int) $row['aprovados'], (int) $row['reprovados'] ];
        }

        TTransaction::close();

        // Renderização do gráfico
        $html->enableSection('main', [
            'data'   => json_encode($data),
            'width'  => '100%',
            'height' => '400px',
            'title'  => 'Evolução dos Planos de Teste por Mês',
            'ytitle' => 'Planos',
            'xtitle' => 'Mês',
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
