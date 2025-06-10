<?php
namespace TribCalc;

class CalculadoraTributaria2025
{
    private float $valorProduto;
    private string $ufOrigem; 
    private string $ufDestino;
    private float $aliquotaRedBcIcms;
    private float $mvaAjustada;
    private float $aliquotaIpi;
    private float $aliquotaPis;
    private float $aliquotaCofins;
    private float $aliquotaIbs;
    private float $aliquotaIva;
    private float $aliquotaCbs;
    private float $aliquotaFcp;
    private float $valorDesonerado;
    private int $motivoDesoneracao;
    private int $regime_tributario;
    private string $cst_icms;
    private string $cst_ipi;
    private string $cst_pis;
    private string $cst_cofins;
    private string $tipo_devolucao;
    private string $cfop_devolucao;

    public function __construct(
        float $valorProduto,
        string $ufOrigem,
        string $ufDestino,
        float $aliquotaRedBcIcms,
        float $mvaAjustada,
        float $aliquotaIpi,
        float $aliquotaPis,
        float $aliquotaCofins,
        float $aliquotaIbs,
        float $aliquotaIva,
        float $aliquotaCbs,
        float $aliquotaFcp,
        float $valorDesonerado,
        int $motivoDesoneracao,
        int $regime_tributario,
        string $cst_icms = '00',
        string $cst_ipi = '00',
        string $cst_pis = '01',
        string $cst_cofins = '01',
        string $tipo_devolucao = '',
        string $cfop_devolucao = ''
    ) {
        $this->valorProduto = $valorProduto;
        $this->ufOrigem = strtoupper($ufOrigem);
        $this->ufDestino = strtoupper($ufDestino);
        $this->aliquotaRedBcIcms = $aliquotaRedBcIcms;
        $this->mvaAjustada = $mvaAjustada;
        $this->aliquotaIpi = $aliquotaIpi;
        $this->aliquotaPis = $aliquotaPis;
        $this->aliquotaCofins = $aliquotaCofins;
        $this->aliquotaIbs = $aliquotaIbs;
        $this->aliquotaIva = $aliquotaIva;
        $this->aliquotaCbs = $aliquotaCbs;
        $this->aliquotaFcp = $aliquotaFcp;
        $this->valorDesonerado = $valorDesonerado;
        $this->motivoDesoneracao = $motivoDesoneracao;
        $this->regime_tributario = $regime_tributario;
        $this->cst_icms = $cst_icms;
        $this->cst_ipi = $cst_ipi;
        $this->cst_pis = $cst_pis;
        $this->cst_cofins = $cst_cofins;
        $this->tipo_devolucao = $tipo_devolucao;
        $this->cfop_devolucao = $cfop_devolucao;
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

    private function calcularBaseDesoneracao(): float
    {
        if ($this->valorDesonerado <= 0) {
            return 0;
        }
        return $this->valorProduto - $this->valorDesonerado;
    }

    private function calcularIcms(): array
    {
        $base = $this->calcularBaseDesoneracao();
        if ($base <= 0) {
            $base = $this->valorProduto;
        }
        
        if ($this->aliquotaRedBcIcms > 0) {
            $base *= (1 - $this->aliquotaRedBcIcms / 100);
        }
        $aliquota = $this->getAliquotaIcms();
        $valor = $base * ($aliquota / 100);
        return [
            'base_calculo' => round($base, 2),
            'aliquota' => $aliquota,
            'valor' => round($valor, 2),
            'cst' => $this->cst_icms
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
        $base = $this->valorProduto;
        $valor = $base * ($this->aliquotaIpi / 100);
        return [
            'base_calculo' => round($base, 2),
            'aliquota' => $this->aliquotaIpi,
            'valor' => round($valor, 2),
            'cst' => $this->cst_ipi
        ];
    }

    private function calcularPIS(): array
    {
        $base = $this->valorProduto;
        $valor = $base * ($this->aliquotaPis / 100);
        return [
            'base_calculo' => round($base, 2),
            'aliquota' => $this->aliquotaPis,
            'valor' => round($valor, 2),
            'cst' => $this->cst_pis
        ];
    }

    private function calcularCOFINS(): array
    {
        $base = $this->valorProduto;
        $valor = $base * ($this->aliquotaCofins / 100);
        return [
            'base_calculo' => round($base, 2),
            'aliquota' => $this->aliquotaCofins,
            'valor' => round($valor, 2),
            'cst' => $this->cst_cofins
        ];
    }

    private function calcularIBS(): array
    {
        $base = $this->valorProduto;
        $valor = $base * ($this->aliquotaIbs / 100);
        return [
            'base_calculo' => round($base, 2),
            'aliquota' => $this->aliquotaIbs,
            'valor' => round($valor, 2)
        ];
    }

    private function calcularIVA(): array
    {
        $base = $this->valorProduto;
        $valor = $base * ($this->aliquotaIva / 100);
        return [
            'base_calculo' => round($base, 2),
            'aliquota' => $this->aliquotaIva,
            'valor' => round($valor, 2)
        ];
    }

    private function calcularCBS(): array
    {
        $base = $this->valorProduto;
        $valor = $base * ($this->aliquotaCbs / 100);
        return [
            'base_calculo' => round($base, 2),
            'aliquota' => $this->aliquotaCbs,
            'valor' => round($valor, 2)
        ];
    }

    private function calcularFCP(): array
    {
        $base = $this->valorProduto;
        $valor = $base * ($this->aliquotaFcp / 100);
        return ['valor' => round($valor, 2)];
    }

    private function calcularDifal(): array
    {
        if ($this->ufOrigem === $this->ufDestino) {
            return [
                'valor_uf_destino' => 0,
                'valor_uf_remetente' => 0
            ];
        }
        
        $aliquotaOrigem = $this->getAliquotaIcms();
        $aliquotaDestino = $this->getAliquotaIcmsDestino();
        $base = $this->valorProduto;
        
        if ($this->aliquotaRedBcIcms > 0) {
            $base *= (1 - $this->aliquotaRedBcIcms / 100);
        }
        
        $difal = $base * (($aliquotaDestino - $aliquotaOrigem) / 100);
        return [
            'valor_uf_destino' => round($difal * 0.8, 2),
            'valor_uf_remetente' => round($difal * 0.2, 2)
        ];
    }

    private function getAliquotaIcmsDestino(): float
    {
        $aliquotas = [
            'AC'=>17,'AL'=>17,'AM'=>18,'AP'=>18,'BA'=>18,'CE'=>18,'DF'=>18,'ES'=>17,'GO'=>17,
            'MA'=>18,'MG'=>18,'MS'=>17,'MT'=>17,'PA'=>17,'PB'=>18,'PE'=>18,'PI'=>18,'PR'=>18,
            'RJ'=>20,'RN'=>18,'RO'=>17.5,'RR'=>17,'RS'=>17,'SC'=>17,'SE'=>18,'SP'=>18,'TO'=>18
        ];
        return $aliquotas[$this->ufDestino];
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
        $icms = $this->calcularIcms();
        $icmsSt = $this->calcularIcmsST();
        $ipi = $this->calcularIPI();
        $pis = $this->calcularPIS();
        $cofins = $this->calcularCOFINS();
        $ibs = $this->calcularIBS();
        $iva = $this->calcularIVA();
        $cbs = $this->calcularCBS();
        $fcp = $this->calcularFCP();
        $difal = $this->calcularDifal();

        return [
            'regime_tributario' => $this->getRegimeTributario(),
            'valor_produto' => $this->valorProduto,
            'icms' => [
                'base_calculo' => $icms['base_calculo'],
                'aliquota' => $icms['aliquota'],
                'valor' => $icms['valor'],
                'cst' => $icms['cst']
            ],
            'icms_st' => [
                'base_calculo' => $icmsSt['base_calculo'],
                'valor' => $icmsSt['valor']
            ],
            'ipi' => [
                'base_calculo' => $ipi['base_calculo'],
                'aliquota' => $ipi['aliquota'],
                'valor' => $ipi['valor'],
                'cst' => $ipi['cst']
            ],
            'pis' => [
                'base_calculo' => $pis['base_calculo'],
                'aliquota' => $pis['aliquota'],
                'valor' => $pis['valor'],
                'cst' => $pis['cst']
            ],
            'cofins' => [
                'base_calculo' => $cofins['base_calculo'],
                'aliquota' => $cofins['aliquota'],
                'valor' => $cofins['valor'],
                'cst' => $cofins['cst']
            ],
            'ibs' => [
                'base_calculo' => $ibs['base_calculo'],
                'aliquota' => $ibs['aliquota'],
                'valor' => $ibs['valor']
            ],
            'iva' => [
                'base_calculo' => $iva['base_calculo'],
                'aliquota' => $iva['aliquota'],
                'valor' => $iva['valor']
            ],
            'cbs' => [
                'base_calculo' => $cbs['base_calculo'],
                'aliquota' => $cbs['aliquota'],
                'valor' => $cbs['valor']
            ],
            'fcp' => $fcp['valor'],
            'difal' => [
                'valor_uf_destino' => $difal['valor_uf_destino'],
                'valor_uf_remetente' => $difal['valor_uf_remetente']
            ],
            'devolucao' => [
                'tipo' => $this->tipo_devolucao,
                'cfop' => $this->cfop_devolucao
            ]
        ];
    }
}