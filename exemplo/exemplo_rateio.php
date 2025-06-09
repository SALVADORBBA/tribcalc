<?php
require_once __DIR__ . '/../src/RateioDocumentoFiscal.php';

use TribCalc\RateioDocumentoFiscal;

// Criando alguns itens de exemplo
$itens = [];

// Item 1
$item1 = new stdClass();
$item1->id = 1;
$item1->descricao = 'Produto A';
$item1->quantidade = 2;
$item1->valor_unitario = 100.00;
$item1->valor_total = 200.00;

// Item 2
$item2 = new stdClass();
$item2->id = 2;
$item2->descricao = 'Produto B';
$item2->quantidade = 1;
$item2->valor_unitario = 150.00;
$item2->valor_total = 150.00;

// Item 3
$item3 = new stdClass();
$item3->id = 3;
$item3->descricao = 'Produto C';
$item3->quantidade = 3;
$item3->valor_unitario = 50.00;
$item3->valor_total = 150.00;

$itens[] = $item1;
$itens[] = $item2;
$itens[] = $item3;

// Valores a serem rateados
$valor_frete = 100.00;
$valor_seguro = 50.00;
$valor_desconto = 75.00;
$valor_outras_despesas = 25.00;

// Calculando o rateio
$itens_rateados = RateioDocumentoFiscal::calcularRateio(
    $itens,
    $valor_frete,
    $valor_seguro,
    $valor_desconto,
    $valor_outras_despesas
);

// Exibindo os resultados
echo "Resultados do Rateio:\n\n";

foreach ($itens_rateados as $item) {
    echo "Item {$item->id} - {$item->descricao}\n";
    echo "Valor Total: R$ " . number_format($item->valor_total, 2, ',', '.') . "\n";
    echo "Frete Rateado: R$ " . number_format($item->frete_rateado, 2, ',', '.') . "\n";
    echo "Seguro Rateado: R$ " . number_format($item->seguro_rateado, 2, ',', '.') . "\n";
    echo "Desconto Rateado: R$ " . number_format($item->desconto_rateado, 2, ',', '.') . "\n";
    echo "Outras Despesas Rateadas: R$ " . number_format($item->outras_despesas_rateado, 2, ',', '.') . "\n";
    echo "----------------------------------------\n";
}