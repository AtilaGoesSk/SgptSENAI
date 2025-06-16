<?php

class DashboardPlanoTeste extends TPage
{
    function __construct()
    {
        parent::__construct();
        
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        
        $div = new TElement('div');
        $div->class = "row";

        TTransaction::open('SGPT_DB'); // Open a transaction with the database

        // Contadores dinâmicos
        $countPlanoTeste   = PlanoTeste::where('id_projeto', 'is not', null)->count();
        $countCasosTestes  = CasoTeste::where('tp_status', 'is not', null)->count();
        $countProjetoTeste = ProjetoTeste::where('id_usuario', 'is not', null)->count();

        TTransaction::close(); // Close the transaction
        
        // Indicadores
        $indicator1 = new THtmlRenderer('app/resources/info-box.html');
        $indicator2 = new THtmlRenderer('app/resources/info-box.html');
        $indicator3 = new THtmlRenderer('app/resources/info-box.html');
        
        $indicator1->enableSection('main', [
            'title'     => 'Planos de Teste',
            'icon'      => 'clipboard-list',
            'background'=> 'green',
            'value'     => $countPlanoTeste
        ]);

        $indicator2->enableSection('main', [
            'title'     => 'Casos de Teste',
            'icon'      => 'check-circle',
            'background'=> 'blue',
            'value'     => $countCasosTestes
        ]);

        $indicator3->enableSection('main', [
            'title'     => 'Projetos',
            'icon'      => 'project-diagram',
            'background'=> 'orange',
            'value'     => $countProjetoTeste
        ]);

        // Adiciona indicadores no layout
        $div->add($i1 = TElement::tag('div', $indicator1));
        $div->add($i2 = TElement::tag('div', $indicator2));
        $div->add($i3 = TElement::tag('div', $indicator3));

        // Define tamanho das caixas
        $i1->class = 'col-sm-4';
        $i2->class = 'col-sm-4';
        $i3->class = 'col-sm-4';

        // Gráficos
        $div->add($g1 = new BarChartView(false));
        $div->add($g2 = new LineChartView(false));
        $div->add($g3 = new ColumnChartView(false));
        $div->add($g4 = new PieChartView(false));

        // Define tamanho dos gráficos
        $g1->class = 'col-sm-6';
        $g2->class = 'col-sm-6';
        $g3->class = 'col-sm-6';
        $g4->class = 'col-sm-6';

        // Breadcrumb + Layout
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($div);

        parent::add($vbox);
    }
}
