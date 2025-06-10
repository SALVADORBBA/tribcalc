<?php

require_once __DIR__ . '/../vendor/autoload.php';

use TribCalc\CalculadoraTributaria2025;

// Exemplo de uso da CalculadoraTributaria2025
$calculadora = new CalculadoraTributaria2025();

// Configurando os valores 
$calculadora->setValorProduto(1000.00);
$calculadora->setUfOrigem('SP');
$calculadora->setUfDestino('RJ');

// Configurando alíquotas dos novos impostos
$calculadora->setAliquotaIBS(0.25); // 25%
$calculadora->setAliquotaIVA(0.20); // 20%
$calculadora->setAliquotaCBS(0.10); // 10%

// Configurando regime tributário e CSTs
$calculadora->setRegimeTributario('NORMAL');
$calculadora->setCstIBS('00');
$calculadora->setCstIVA('00');
$calculadora->setCstCBS('01');

// Configurando desoneração se necessário
$calculadora->setValorDesoneracao(100.00);
$calculadora->setMotivoDesoneracao('Incentivo Fiscal');

// Calculando os tributos
$resultados = $calculadora->calcularTributos();

// Exibindo os resultados detalhados
echo "\nResultados dos Cálculos Tributários 2025:\n";
echo "----------------------------------------\n";

$detalhes = $calculadora->exibirResultadosDetalhados();

foreach ($detalhes as $imposto => $valores) {
    echo "\n{$imposto}:\n";
    foreach ($valores as $campo => $valor) {
        if (is_numeric($valor)) {
            echo "  {$campo}: R$ " . number_format($valor, 2, ',', '.') . "\n";
        } else {
            echo "  {$campo}: {$valor}\n";
        }
    }
}