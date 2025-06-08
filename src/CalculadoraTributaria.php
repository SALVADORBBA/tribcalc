<?php
declare(strict_types=1);

namespace TribCalc;

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
    )
    {
        $this->valorProduto = (float) $valorProduto;
        $this->ufOrigem = strtoupper($ufOrigem);
        $this->ufDestino = strtoupper($ufDestino);
        $this->aliquotaRedBcIcms = (float) $aliquotaRedBcIcms;
        $this->mvaAjustada = (float) $mvaAjustada;
        $this->aliquotaIpi = (float) $aliquotaIpi;
        $this->aliquotaIbs = (float) $aliquotaIbs;
        $this->aliquotaIva = (float) $aliquotaIva;
        $this->aliquotaFcp = (float) $aliquotaFcp;
        $this->valorDesonerado = (float) $valorDesonerado;
        $this->motivoDesoneracao = (int) $motivoDesoneracao;
        $this->regime_tributario = (int) $regime_tributario;
    }

        public static function fromObject(object $obj): self
        {
            return new self(
                $obj->valorProduto,
                $obj->ufOrigem,
                $obj->ufDestino,
                $obj->aliquotaRedBcIcms,
                $obj->mvaAjustada,
                $obj->aliquotaIpi,
                $obj->aliquotaIbs,
                $obj->aliquotaIva,
                $obj->aliquotaFcp,
                $obj->valorDesonerado,
                $obj->motivoDesoneracao,
                $obj->regime_tributario
            );
        }


    private function getAliquotaIcms(): float
    {
        $aliquotas = [
            'AC'=>17,'AL'=>17,'AM'=>18,'AP'=>18,'BA'=>18,'CE'=>18,'DF'=>18,'ES'=>17,'GO'=>17,
            'MA'=>18,'MG'=>18,'MS'=>17,'MT'=>17,'PA'=>17,'PB'=>18,'PE'=>18,'PI'=>18,'PR'=>18,
            'RJ'=>20,'RN'=>18,'RO'=>17.5,'RR'=>17,'RS'=>17,'SC'=>17,'SE'=>18,'SP'=>18,'TO'=>18
        ];

        if ($this->ufOrigem === $this->ufDestino) {
            return $aliquotas[$this->ufDestino];
        }

        $sulSudeste = ['SP','RJ','MG','ES','RS','SC','PR'];
        $origem = in_array($this->ufOrigem, $sulSudeste);
        $destino = in_array($this->ufDestino, $sulSudeste);

        return ($origem && $destino) ? 12.0 : 7.0;
    }

    private function calcularIcms(): array
    {
        $base = $this->valorProduto;
        if ($this->aliquotaRedBcIcms > 0) {
            $base *= (1 - $this->aliquotaRedBcIcms / 100);
        }
        $aliquota = $this->getAliquotaIcms();
        $valor = $base * ($aliquota / 100);
        return [
            'base_calculo' => round($base, 2),
            'aliquota' => $aliquota,
            'valor' => round($valor, 2)
        ];
    }

    private function calcularIcmsST(): array
    {
        if ($this->mvaAjustada <= 0) {
            return ['base_calculo' => 0, 'valor' => 0];
        }
        $base = $this->valorProduto * (1 + $this->mvaAjustada / 100);
        $valorProprio = $this->calcularIcms()['valor'];
        $aliquota = $this->getAliquotaIcms();
        $valorST = ($base * ($aliquota / 100)) - $valorProprio;
        return [
            'base_calculo' => round($base, 2),
            'valor' => round($valorST, 2)
        ];
    }

    private function calcularIPI(): array
    {
        return ['valor' => round($this->valorProduto * $this->aliquotaIpi / 100, 2)];
    }

    private function calcularIBS(): array
    {
        return ['valor' => round($this->valorProduto * $this->aliquotaIbs / 100, 2)];
    }

    private function calcularIVA(): array
    {
        return ['valor' => round($this->valorProduto * $this->aliquotaIva / 100, 2)];
    }

    private function calcularFCP(): array
    {
        return ['valor' => round($this->valorProduto * $this->aliquotaFcp / 100, 2)];
    }

    private function getJustificativaDesoneracao(): string
    {
        $motivos = [
            1 => 'Táxi', 2 => 'Deficiente Físico', 3 => 'Produtor Agropecuário',
            4 => 'Frotista/Locadora', 5 => 'Diplomático/Consular', 6 => 'Amazônia Ocidental',
            7 => 'SUFRAMA', 8 => 'Venda a Órgãos Públicos', 9 => 'Outros'
        ];
        return $motivos[$this->motivoDesoneracao] ?? 'Não especificado';
    }

    private function getRegimeTributario(): string
    {
        $regimes = [
            1 => 'Simples Nacional',
            2 => 'SN - Excesso Sublimite',
            3 => 'Regime Normal',
            4 => 'MEI'
        ];
        return $regimes[$this->regime_tributario] ?? 'Não especificado';
    }

    public function calcularTributos(): array
    {
        return [
            'regime_tributario' => $this->getRegimeTributario(),
            'valor_produto' => $this->valorProduto,
            'icms' => $this->calcularIcms(),
            'icms_st' => $this->calcularIcmsST(),
            'ipi' => $this->calcularIPI(),
            'ibs' => $this->calcularIBS(),
            'iva' => $this->calcularIVA(),
            'fcp' => $this->calcularFCP(),
            'desoneracao' => [
                'valor' => $this->valorDesonerado,
                'motivo' => $this->getJustificativaDesoneracao()
            ]
        ];
    }
}

 