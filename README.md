# TribCalc - Calculadora de Tributos

**TribCalc** é uma biblioteca PHP para cálculos tributários no Brasil, com foco em ICMS, ICMS-ST, IPI, FCP, IBS, IVA e desoneração.

## 📦 Instalação

Use o Composer para instalar a biblioteca:

```bash
composer require salvadorbba/tribcalc
```

## ✅ Como Usar

```php
use TribCalc\CalculadoraTributaria;

$calculadora = new CalculadoraTributaria( 
    valorProduto: 1000.00,
    ufOrigem: 'SP',
    ufDestino: 'RJ',
    aliquotaRedBcIcms: 0.00,
    mvaAjustada: 40.00,
    aliquotaIpi: 5.00,
    aliquotaIbs: 2.00,
    aliquotaIva: 3.00,
    aliquotaFcp: 2.00,
    valorDesonerado: 0.00,
    motivoDesoneracao: 9,
    regime_tributario: 3
);

$resultados = $calculadora->calcularTributos();

print_r($resultados);
```

### 🧾 Exemplo de Saída

```php
[
    'valor_icms'         => 120.00,
    'valor_icms_st'      => 150.00,
    'valor_ipi'          => 50.00,
    'valor_ibs'          => 20.00,
    'valor_iva'          => 30.00,
    'valor_fcp'          => 20.00,
    'valor_desonerado'   => 0.00,
    'motivo_desoneracao' => 9
]
```

## ⚙️ Parâmetros

| Parâmetro             | Tipo    | Descrição                                              |
|----------------------|---------|----------------------------------------------------------|
| `valorProduto`       | float   | Valor total do produto                                  |
| `ufOrigem`           | string  | Unidade Federativa de origem                            |
| `ufDestino`          | string  | Unidade Federativa de destino                           |
| `aliquotaRedBcIcms`  | float   | Percentual de redução da base de cálculo do ICMS        |
| `mvaAjustada`        | float   | Margem de Valor Agregado para ICMS-ST                  |
| `aliquotaIpi`        | float   | Alíquota de IPI aplicada                                |
| `aliquotaIbs`        | float   | Alíquota do imposto IBS                                 |
| `aliquotaIva`        | float   | Alíquota do imposto IVA                                 |
| `aliquotaFcp`        | float   | Alíquota de FCP (Fundo de Combate à Pobreza)           |
| `valorDesonerado`    | float   | Valor desonerado (se aplicável)                         |
| `motivoDesoneracao`  | int     | Código do motivo de desoneração                         |
| `regime_tributario`  | int     | Tipo de regime tributário (1 = Simples, 3 = Normal, etc)|

## 📄 Licença

Este projeto é licenciado sob a [MIT License](LICENSE).
