<?php
require_once __DIR__ . '/../src/CalculadoraTributaria.php';

use TribCalc\CalculadoraTributaria;
 
// Exemplo 1: Venda dentro do estado (SP -> SP)
$calculadora1 = new CalculadoraTributaria( 
    valorProduto: 1000.00,        // Valor do produto
    ufOrigem: 'SP',               // Estado de origem
    ufDestino: 'SP',              // Estado de destino
    aliquotaRedBcIcms: 0,         // Sem redução da base de cálculo
    mvaAjustada: 40,              // MVA de 40%
    aliquotaIpi: 5,               // IPI de 5%
    aliquotaIbs: 2,               // IBS de 2%
    aliquotaIva: 3,               // IVA de 3%
    aliquotaFcp: 2,               // FCP de 2%
    valorDesonerado: 0,           // Sem desoneração
    motivoDesoneracao: 0,         // Sem motivo de desoneração
    regime_tributario: 3          // Regime Normal
);

// Exemplo 2: Venda interestadual (SP -> RJ) com redução de base
$calculadora2 = new CalculadoraTributaria(
    valorProduto: 2000.00,        // Valor do produto
    ufOrigem: 'SP',               // Estado de origem
    ufDestino: 'RJ',              // Estado de destino
    aliquotaRedBcIcms: 10,        // Redução de 10% na base de cálculo
    mvaAjustada: 50,              // MVA de 50%
    aliquotaIpi: 10,              // IPI de 10%
    aliquotaIbs: 2,               // IBS de 2%
    aliquotaIva: 3,               // IVA de 3%
    aliquotaFcp: 2,               // FCP de 2%
    valorDesonerado: 0,           // Sem desoneração
    motivoDesoneracao: 0,         // Sem motivo de desoneração
    regime_tributario: 3          // Regime Normal
);

// Exemplo 3: Venda com desoneração (SP -> MG)
$calculadora3 = new CalculadoraTributaria(
    valorProduto: 3000.00,        // Valor do produto
    ufOrigem: 'SP',               // Estado de origem
    ufDestino: 'MG',              // Estado de destino
    aliquotaRedBcIcms: 0,         // Sem redução da base de cálculo
    mvaAjustada: 35,              // MVA de 35%
    aliquotaIpi: 0,               // Sem IPI
    aliquotaIbs: 2,               // IBS de 2%
    aliquotaIva: 3,               // IVA de 3%
    aliquotaFcp: 2,               // FCP de 2%
    valorDesonerado: 1000.00,     // R$ 1000,00 desonerado
    motivoDesoneracao: 2,         // Deficiente Físico
    regime_tributario: 3          // Regime Normal
);

// Exemplo usando objeto
$obj = new stdClass();
$obj->valorProduto = 1500.00;
$obj->ufOrigem = 'RS';
$obj->ufDestino = 'SC';
$obj->aliquotaRedBcIcms = 0;
$obj->mvaAjustada = 45;
$obj->aliquotaIpi = 7;
$obj->aliquotaIbs = 2;
$obj->aliquotaIva = 3;
$obj->aliquotaFcp = 2;
$obj->valorDesonerado = 0;
$obj->motivoDesoneracao = 0;
$obj->regime_tributario = 3;

$calculadora4 = CalculadoraTributaria::fromObject($obj);

// Função para exibir os resultados
function exibirResultados(CalculadoraTributaria $calc, string $titulo) {
    echo "\n{$titulo}\n";
    echo str_repeat('=', strlen($titulo)) . "\n";
    
    $resultados = $calc->calcularTributos();
    
    foreach ($resultados as $tributo => $dados) {
        echo "\n{$tributo}:\n";
        foreach ($dados as $campo => $valor) {
            if (is_numeric($valor)) {
                echo "  {$campo}: R$ " . number_format($valor, 2, ',', '.') . "\n";
            } else {
                echo "  {$campo}: {$valor}\n";
            }
        }
    }
    echo "\n" . str_repeat('-', 50) . "\n";
}

// Exibindo os resultados
exibirResultados($calculadora1, "Exemplo 1: Venda dentro do estado (SP -> SP)");
exibirResultados($calculadora2, "Exemplo 2: Venda interestadual (SP -> RJ) com redução de base");
exibirResultados($calculadora3, "Exemplo 3: Venda com desoneração (SP -> MG)");
exibirResultados($calculadora4, "Exemplo 4: Venda usando objeto (RS -> SC)");