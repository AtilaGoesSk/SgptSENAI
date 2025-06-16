<?php

class LineChartView extends TPage
{
    function __construct($show_breadcrumb = true)
    {
        parent::__construct();

        $html = new THtmlRenderer('app/resources/google_line_chart.html');

        $data = [];
        // Cabeçalho do gráfico
        $data[] = ['Mês', 'Planos de Teste'];

        try {
            TTransaction::open('SGPT_DB');

            $conn = TTransaction::get();

            // Consulta que traz quantidade de planos de teste agrupados por mês
            $result = $conn->query("
                SELECT 
                    TO_CHAR(pt.dt_criacao, 'YYYY-MM') AS mes,
                    COUNT(pt.id_plano_teste) AS total_planos
                FROM plano_teste pt
                GROUP BY mes
                ORDER BY mes
            ");

            foreach ($result as $row) {
                $data[] = [
                    $row['mes'],
                    (int) $row['total_planos']
                ];
            }

            TTransaction::close();
        }
        catch (Exception $e) {
            new TMessage('error', 'Erro ao carregar dados: ' . $e->getMessage());
        }

        $html->enableSection('main', [
            'data'   => json_encode($data),
            'width'  => '100%',
            'height' => '400px',
            'title'  => 'Evolução dos Planos de Teste',
            'ytitle' => 'Quantidade',
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
