<?php

class ColumnChartView extends TPage
{
    function __construct( $show_breadcrumb = true )
    {
        parent::__construct();

        $html = new THtmlRenderer('app/resources/google_column_chart.html');

        // Montar o array de dados que vai para o gráfico
        $data = [];
        // Cabeçalho do gráfico: primeira coluna é o eixo X, as outras são séries
        $data[] = ['Mês', 'Planos de Teste', 'Casos Aprovados', 'Casos Reprovados'];

        try {
            TTransaction::open('SGPT_DB'); // Coloque sua conexão aqui

            $conn = TTransaction::get();

            // Consulta que retorna dados agregados por mês, para 3 séries diferentes
            $result = $conn->query("
                SELECT 
                    TO_CHAR(pt.dt_criacao, 'YYYY-MM') AS mes,
                    COUNT(DISTINCT pt.id_plano_teste) AS planos_teste,
                    SUM(CASE WHEN ct.tp_status = '2' THEN 1 ELSE 0 END) AS casos_aprovados,
                    SUM(CASE WHEN ct.tp_status = '3' THEN 1 ELSE 0 END) AS casos_reprovados
                FROM plano_teste pt
                LEFT JOIN caso_teste ct ON ct.id_plano_teste = pt.id_plano_teste
                GROUP BY mes
                ORDER BY mes
            ");

            foreach ($result as $row) {
                $data[] = [
                    $row['mes'],
                    (int) $row['planos_teste'],
                    (int) $row['casos_aprovados'],
                    (int) $row['casos_reprovados']
                ];
            }

            TTransaction::close();
        }
        catch (Exception $e) {
            TTransaction::rollback(); // para garantir que a transação fecha em erro
            new TMessage('error', 'Erro ao carregar dados: ' . $e->getMessage());
        }

        $html->enableSection('main', [
            'data'   => json_encode($data),
            'width'  => '100%',
            'height' => '400px',
            'title'  => 'Indicadores por Mês',
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
