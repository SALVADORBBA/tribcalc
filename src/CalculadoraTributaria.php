<?php

namespace TribCalc;

/**
 * @property float $valorProduto
 * @property string $ufOrigem
 * @property string $ufDestino
 * @property float $aliquotaRedBcIcms
 * @property float $mvaAjustada
 * @property float $aliquotaIpi
 * @property float $aliquotaIbs
 * @property float $aliquotaIva
 * @property float $aliquotaFcp
 * @property float $valorDesonerado
 * @property int $motivoDesoneracao
 * @property int $regime_tributario
 */
class CalculadoraTributaria
{
    private float $valorProduto;
    private string $ufOrigem;
    private string $ufDestino;
    private float $aliquotaRedBcIcms;
    private float $mvaAjustada;
    private float $aliquotaIpi;
    private float $aliquotaIbs;
    private float $aliquotaIva;
    private float $aliquotaFcp;
    private float $valorDesonerado;
    private int $motivoDesoneracao;
    private int $regime_tributario;

    public function __construct(
        float $valorProduto,
        string $ufOrigem,
        string $ufDestino,
        float $aliquotaRedBcIcms,
        float $mvaAjustada,
        float $aliquotaIpi,
        float $aliquotaIbs,
        float $aliquotaIva,
        float $aliquotaFcp,
        float $valorDesonerado,
        int $motivoDesoneracao,
        int $regime_tributario
    ) {
        $this->valorProduto = $valorProduto;
        $this->ufOrigem = strtoupper($ufOrigem);
        $this->ufDestino = strtoupper($ufDestino);
        $this->aliquotaRedBcIcms = $aliquotaRedBcIcms;
        $this->mvaAjustada = $mvaAjustada;
        $this->aliquotaIpi = $aliquotaIpi;
        $this->aliquotaIbs = $aliquotaIbs;
        $this->aliquotaIva = $aliquotaIva;
        $this->aliquotaFcp = $aliquotaFcp;
        $this->valorDesonerado = $valorDesonerado;
        $this->motivoDesoneracao = $motivoDesoneracao;
        $this->regime_tributario = $regime_tributario;
    }

    private function getAliquotaIcms(): float
    {
        $aliquotasInternas = [
            'AC' => 17.0, 'AL' => 17.0, 'AM' => 18.0, 'AP' => 18.0,
            'BA' => 18.0, 'CE' => 18.0, 'DF' => 18.0, 'ES' => 17.0,
            'GO' => 17.0, 'MA' => 18.0, 'MG' => 18.0, 'MS' => 17.0,
            'MT' => 17.0, 'PA' => 17.0, 'PB' => 18.0, 'PE' => 18.0,
            'PI' => 18.0, 'PR' => 18.0, 'RJ' => 20.0, 'RN' => 18.0,
            'RO' => 17.5, 'RR' => 17.0, 'RS' => 17.0, 'SC' => 17.0,
            'SE' => 18.0, 'SP' => 18.0, 'TO' => 18.0
        ];

        if ($this->ufOrigem === $this->ufDestino) {
            return $aliquotasInternas[$this->ufDestino];
        }

        // Alíquota interestadual
        $sulSudeste = ['SP', 'RJ', 'MG', 'ES', 'RS', 'SC', 'PR'];
        $origem = in_array($this->ufOrigem, $sulSudeste);
        $destino = in_array($this->ufDestino, $sulSudeste);

        if ($origem && $destino) {
            return 12.0;
        }
        return 7.0;
    }

    private function calcularIcms(): array
    {
        $baseCalculo = $this->valorProduto;
        if ($this->aliquotaRedBcIcms > 0) {
            $baseCalculo *= (1 - ($this->aliquotaRedBcIcms / 100));
        }

        $aliquota = $this->getAliquotaIcms();
        $valor = $baseCalculo * ($aliquota / 100);

        return [
            'base_calculo' => round($baseCalculo, 2),
            'aliquota' => $aliquota,
            'valor' => round($valor, 2)
        ];
    }

    private function calcularIcmsST(): array
    {
        if ($this->mvaAjustada <= 0) {
            return ['base_calculo' => 0, 'valor' => 0];
        }

        $baseCalculo = $this->valorProduto * (1 + ($this->mvaAjustada / 100));
        $aliquotaDestino = $this->getAliquotaIcms();
        $valorIcmsProprio = $this->calcularIcms()['valor'];
        $valorST = ($baseCalculo * ($aliquotaDestino / 100)) - $valorIcmsProprio;

        return [
            'base_calculo' => round($baseCalculo, 2),
            'valor' => round($valorST, 2)
        ];
    }

    private function calcularIPI(): array
    {
        $valor = $this->valorProduto * ($this->aliquotaIpi / 100);
        return ['valor' => round($valor, 2)];
    }

    private function calcularIBS(): array
    {
        $valor = $this->valorProduto * ($this->aliquotaIbs / 100);
        return ['valor' => round($valor, 2)];
    }

    private function calcularIVA(): array
    {
        $valor = $this->valorProduto * ($this->aliquotaIva / 100);
        return ['valor' => round($valor, 2)];
    }

    private function calcularFCP(): array
    {
        $valor = $this->valorProduto * ($this->aliquotaFcp / 100);
        return ['valor' => round($valor, 2)];
    }

    private function getJustificativaDesoneracao(): string
    {
        $justificativas = [
            1 => 'Táxi',
            2 => 'Deficiente Físico',
            3 => 'Produtor Agropecuário',
            4 => 'Frotista/Locadora',
            5 => 'Diplomático/Consular',
            6 => 'Utilitários e Motocicletas da Amazônia Ocidental',
            7 => 'SUFRAMA',
            8 => 'Venda a Órgãos Públicos',
            9 => 'Outros'
        ];

        return $justificativas[$this->motivoDesoneracao] ?? 'Não especificado';
    }

    private function getRegimeTributario(): string
    {
        $regimes = [
            1 => 'Simples Nacional',
            2 => 'Simples Nacional - Excesso de Sublimite',
            3 => 'Regime Normal',
            4 => 'MEI - Microempreendedor Individual'
        ];

        return $regimes[$this->regime_tributario] ?? 'Não especificado';
    }

    public function calcularTributos(): array
    {
        $icms = $this->calcularIcms();
        $icmsST = $this->calcularIcmsST();
        $ipi = $this->calcularIPI();
        $ibs = $this->calcularIBS();
        $iva = $this->calcularIVA();
        $fcp = $this->calcularFCP();

        return [
            'regime_tributario' => $this->getRegimeTributario(),
            'valor_produto' => $this->valorProduto,
            'icms' => [
                'base_calculo' => $icms['base_calculo'],
                'aliquota' => $icms['aliquota'],
                'valor' => $icms['valor']
            ],
            'icms_st' => [
                'base_calculo' => $icmsST['base_calculo'],
                'valor' => $icmsST['valor']
            ],
            'ipi' => $ipi,
            'ibs' => $ibs,
            'iva' => $iva,
            'fcp' => $fcp,
            'desoneracao' => [
                'valor' => $this->valorDesonerado,
                'motivo' => $this->getJustificativaDesoneracao()
            ]
        ];
    }
}