# TribCalc

Biblioteca PHP para cálculos tributários brasileiros.

## Instalação

```bash
composer require tribcalc/tribcalc
```

## Uso

A classe `CalculadoraTributaria` pode ser utilizada de duas formas:

### 1. Usando o construtor diretamente

```php
$calculadora = new CalculadoraTributaria(
    1000.00,        // valor do produto
    'SP',           // UF de origem
    'RJ',           // UF de destino
    0.00,           // alíquota de redução da base de cálculo do ICMS
    30.00,          // MVA ajustada
    10.00,          // alíquota do IPI
    1.00,           // alíquota do IBS
    2.00,           // alíquota do IVA
    2.00,           // alíquota do FCP
    100.00,         // valor desonerado
    9,              // motivo da desoneração (9 = Outros)
    3               // regime tributário (3 = Regime Normal)
);

// Obtém os resultados detalhados
$resultados = $calculadora->exibirResultadosDetalhados();
```

### 2. Usando o método factory a partir de um objeto

```php
$dados = (object)[
    'valorProduto' => 1000.00,
    'ufOrigem' => 'SP',
    'ufDestino' => 'RJ',
    'aliquotaRedBcIcms' => 0.00,
    'mvaAjustada' => 30.00,
    'aliquotaIpi' => 10.00,
    'aliquotaIbs' => 1.00,
    'aliquotaIva' => 2.00,
    'aliquotaFcp' => 2.00,
    'valorDesonerado' => 100.00,
    'motivoDesoneracao' => 9,
    'regime_tributario' => 3
];

$calculadora = CalculadoraTributaria::fromObject($dados);

// Obtém os resultados detalhados
$resultados = $calculadora->exibirResultadosDetalhados();
```

### Métodos Disponíveis

- `exibirResultadosDetalhados()`: Retorna um array com todos os detalhes dos cálculos, incluindo bases de cálculo, alíquotas e valores para cada tributo
- `exibirDadosObjeto()`: Retorna um array com os dados de entrada e os resultados dos cálculos
- `calcularTributos()`: Retorna um array com os resultados dos cálculos tributários

### Motivos de Desoneração

- 1: Táxi
- 2: Deficiente Físico
- 3: Produtor Agropecuário
- 4: Frotista/Locadora
- 5: Diplomático/Consular
- 6: Amazônia Ocidental
- 7: SUFRAMA
- 8: Venda a Órgãos Públicos
- 9: Outros

### Regimes Tributários

- 1: Simples Nacional
- 2: SN - Excesso Sublimite
- 3: Regime Normal
- 4: MEI

## Licença

MIT
