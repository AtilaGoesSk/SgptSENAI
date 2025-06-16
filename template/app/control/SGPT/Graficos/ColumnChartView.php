<?php

class ColumnChartView extends TPage
{
    function __construct($show_breadcrumb = true)
    {
        parent::__construct();

        $html = new THtmlRenderer('app/resources/google_chart_base.html');

        $data = [];
        $data[] = ['Day', 'Value 1', 'Value 2', 'Value 3'];
        $data[] = ['Day 1', 100, 120, 140];
        $data[] = ['Day 2', 120, 140, 160];
        $data[] = ['Day 3', 140, 160, 180];

        $html->enableSection('main', [
            'data'       => json_encode($data),
            'width'      => '100%',
            'height'     => '300px',
            'title'      => 'Accesses by day',
            'ytitle'     => 'Accesses',
            'xtitle'     => 'Day',
            'uniqid'     => uniqid(),
            'chart_type' => 'ColumnChart', // tipo de gráfico do Google Charts
            'options'    => "seriesType: 'bars'" // opções extras do gráfico
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
