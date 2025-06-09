# TribCalc

**TribCalc** é uma biblioteca PHP moderna desenvolvida para facilitar o **cálculo de tributos no Brasil**, como ICMS, ICMS-ST, DIFAL, FCP, IPI, IVA, e IBS, de forma prática e extensível. Ideal para desenvolvedores que precisam integrar regras fiscais em sistemas de ERP, emissão de NFe/NFCe, ou backends financeiros.

## 🚀 Instalação

### Via Composer

```bash
composer require salvadorbba/tribcalc:^1.0.2
```

### Uso no Adianti Framework

Para utilizar dentro de projetos Adianti, apenas adicione ao seu `composer.json` e chame a classe normalmente nos controles, formulários ou serviços REST.

---

## 💡 Como Usar

A biblioteca oferece duas classes principais:

### 📊 CalculadoraTributaria

#### ✅ 1. Usando o construtor diretamente

```php
use TribCalc\CalculadoraTributaria;

$calculadora = new CalculadoraTributaria(
    1000.00,      // valorProduto
    'SP',         // ufOrigem
    'RJ',         // ufDestino
    0.00,         // aliquotaRedBcIcms
    30.00,        // mvaAjustada
    10.00,        // aliquotaIpi
    1.00,         // aliquotaIbs
    2.00,         // aliquotaIva
    2.00,         // aliquotaFcp
    100.00,       // valorDesonerado
    9,            // motivoDesoneracao
    3             // regime_tributario
); 

$resultados = $calculadora->exibirResultadosDetalhados();
```

#### ✅ 2. Usando a factory `fromObject()`

```php
use TribCalc\CalculadoraTributaria;

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

$resultados = $calculadora->exibirResultadosDetalhados();
```

### 💰 RateioDocumentoFiscal

Classe para calcular o rateio proporcional de valores (frete, seguro, desconto e outras despesas) entre itens.

```php
use TribCalc\RateioDocumentoFiscal;

// Criando array de itens (cada item deve ter valor_total)
$itens = [
    (object)['id' => 1, 'valor_total' => 200.00],
    (object)['id' => 2, 'valor_total' => 150.00],
    (object)['id' => 3, 'valor_total' => 150.00]
];

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
```

---

## 📌 Métodos Disponíveis

### CalculadoraTributaria

| Método                       | Descrição                                                                 |
|-----------------------------|----------------------------------------------------------------------------|
| `exibirResultadosDetalhados()` | Retorna todos os tributos calculados com base de cálculo, alíquotas e valores |
| `exibirDadosObjeto()`          | Exibe dados de entrada e os resultados finais em formato JSON             |
| `calcularTributos()`          | Executa os cálculos e retorna os tributos calculados como objeto stdClass |

### RateioDocumentoFiscal

| Método                       | Descrição                                                                 |
|-----------------------------|----------------------------------------------------------------------------|
| `calcularRateio()`          | Calcula o rateio proporcional de valores entre os itens                    |

---

## 🔎 Códigos de Referência

### Motivos de Desoneração (`motivoDesoneracao`)

| Código | Descrição              |
|--------|------------------------|
| 1      | Táxi                   |
| 2      | Deficiente Físico      |
| 3      | Produtor Agropecuário  |
| 4      | Frotista/Locadora      |
| 5      | Diplomático/Consular   |
| 6      | Amazônia Ocidental     |
| 7      | SUFRAMA                |
| 8      | Venda a Órgãos Públicos |
| 9      | Outros                 |

### Regimes Tributários (`regime_tributario`)

| Código | Regime Tributário        |
|--------|--------------------------|
| 1      | Simples Nacional         |
| 2      | SN - Excesso Sublimite   |
| 3      | Regime Normal            |
| 4      | MEI                      |

---

## 🤝 Contribuindo

Quer contribuir com melhorias, novas fórmulas ou sugestões de otimização?

Entre em contato com o mantenedor do projeto:

📧 **salvadorbba@gmail.com**

---

## 📄 Licença

Este projeto está licenciado sob a licença MIT. Sinta-se livre para usar, modificar e distribuir conforme necessário.

---

Desenvolvido com ❤️ para facilitar a vida de quem calcula tributos no Brasil.