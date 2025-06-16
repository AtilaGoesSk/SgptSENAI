<?php

class PieChartView extends TPage
{
    function __construct($show_breadcrumb = true)
    {
        parent::__construct();

        $html = new THtmlRenderer('app/resources/google_pie_chart.html');

        $data = [];
        // Cabeçalho do gráfico
        $data[] = ['Usuário', 'Projetos'];

        try {
            TTransaction::open('SGPT_DB');

            $conn = TTransaction::get();

            // Query que retorna a quantidade de projetos por usuário
            $result = $conn->query("
                SELECT 
                    u.name as usuario,
                    COUNT(p.id_projeto) as total_projetos
                FROM projeto_teste p
                INNER JOIN system_users u ON u.id = p.id_usuario
                GROUP BY u.name
                ORDER BY total_projetos DESC
            ");

            foreach ($result as $row) {
                $data[] = [
                    $row['usuario'],
                    (int) $row['total_projetos']
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
            'title'  => 'Projetos por Usuário',
            'ytitle' => '',
            'xtitle' => '',
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
