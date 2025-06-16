<?php

class ColumnChartView extends TPage
{
    function __construct($show_breadcrumb = true)
    {
        parent::__construct();

        $html = new THtmlRenderer('app/resources/google_column_chart.html');

        $data = [];
        // Cabeçalho do gráfico
        $data[] = ['Projeto', 'Planos de Teste'];

        try {
            TTransaction::open('SGPT_DB');

            $conn = TTransaction::get();

            // Consulta que traz a quantidade de planos de teste por projeto
            $result = $conn->query("
                SELECT 
                    p.nm_projeto AS projeto,
                    COUNT(pt.id_plano_teste) AS total_planos
                FROM projeto_teste p
                LEFT JOIN plano_teste pt ON pt.id_projeto = p.id_projeto
                GROUP BY p.nm_projeto
                ORDER BY total_planos DESC
            ");

            foreach ($result as $row) {
                $data[] = [
                    $row['projeto'],
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
            'title'  => 'Planos de Teste por Projeto',
            'ytitle' => 'Quantidade',
            'xtitle' => 'Projeto',
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
