<?php
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
    private float $valorPis;
    private float $basePis;
    private float $valorCofins;
    private float $baseCofins;
    private float $valorFcp;
    private float $valorIcmsUfDest;
    private float $valorIcmsUfRemet;
    private string $cstIcms;
    private string $cstIpi;
    private string $cstPis;
    private string $cstCofins;
    private float $valorIbs;
    private float $baseIbs;
    private float $valorIva;
    private float $baseIva;
     private float $valorCbs;
    private float $baseCbs;
    private float $aliquotaCbs;

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
        int $regime_tributario,
        float $valorPis = 0.0,
        float $basePis = 0.0,
        float $valorCofins = 0.0,
        float $baseCofins = 0.0,
        float $valorFcp = 0.0,
        float $valorIcmsUfDest = 0.0,
        float $valorIcmsUfRemet = 0.0,
        string $cstIcms = '',
        string $cstIpi = '',
        string $cstPis = '',
        string $cstCofins = '',
        float $valorIbs = 0.0,
        float $baseIbs = 0.0,
        float $valorIva = 0.0,
        float $baseIva = 0.0,
        float $valorCbs = 0.0,
        float $baseCbs = 0.0,
        float $aliquotaCbs = 0.0
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
        $this->valorPis = $valorPis;
        $this->basePis = $basePis;
        $this->valorCofins = $valorCofins;
        $this->baseCofins = $baseCofins;
        $this->valorFcp = $valorFcp;
        $this->valorIcmsUfDest = $valorIcmsUfDest;
        $this->valorIcmsUfRemet = $valorIcmsUfRemet;
        $this->cstIcms = $cstIcms;
        $this->cstIpi = $cstIpi;
        $this->cstPis = $cstPis;
        $this->cstCofins = $cstCofins;
        $this->valorIbs = $valorIbs;
        $this->baseIbs = $baseIbs;
        $this->valorIva = $valorIva;
        $this->baseIva = $baseIva;
        $this->valorCbs = $valorCbs;
        $this->baseCbs = $baseCbs;
        $this->aliquotaCbs = $aliquotaCbs;
    }

    public static function fromObject(object $obj): self
    {
        return new self(
            (float) $obj->valorProduto,
            (string) $obj->ufOrigem,
            (string) $obj->ufDestino,
            (float) $obj->aliquotaRedBcIcms,
            (float) $obj->mvaAjustada,
            (float) $obj->aliquotaIpi,
            (float) $obj->aliquotaIbs,
            (float) $obj->aliquotaIva,
            (float) $obj->aliquotaFcp,
            (float) $obj->valorDesonerado,
            (int) $obj->motivoDesoneracao,
            (int) $obj->regime_tributario,
            (float) ($obj->valorPis ?? 0.0),
            (float) ($obj->basePis ?? 0.0),
            (float) ($obj->valorCofins ?? 0.0),
            (float) ($obj->baseCofins ?? 0.0),
            (float) ($obj->valorFcp ?? 0.0),
            (float) ($obj->valorIcmsUfDest ?? 0.0),
            (float) ($obj->valorIcmsUfRemet ?? 0.0),
            (string) ($obj->cstIcms ?? ''),
            (string) ($obj->cstIpi ?? ''),
            (string) ($obj->cstPis ?? ''),
            (string) ($obj->cstCofins ?? ''),
            (float) ($obj->valorIbs ?? 0.0),
            (float) ($obj->baseIbs ?? 0.0),
            (float) ($obj->valorIva ?? 0.0),
            (float) ($obj->baseIva ?? 0.0),
            (float) ($obj->valorCbs ?? 0.0),
            (float) ($obj->baseCbs ?? 0.0),
            (float) ($obj->aliquotaCbs ?? 0.0)
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
            'base_calculo' => round($this->valorDesonerado, 2),
            'base_desoneracao' => round($base, 2),
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

    private function calcularDifal(): array
    {
        if ($this->ufOrigem === $this->ufDestino) {
            return ['valor' => 0];
        }
        
        $aliquotaOrigem = $this->getAliquotaIcms();
        $aliquotaDestino = $this->getAliquotaIcmsDestino();
        $base = $this->valorProduto;
        
        if ($this->aliquotaRedBcIcms > 0) {
            $base *= (1 - $this->aliquotaRedBcIcms / 100);
        }
        
        $difal = $base * (($aliquotaDestino - $aliquotaOrigem) / 100);
        return ['valor' => round($difal, 2)];
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

    private function calcularValorDesoneracao(): float
    {
        if ($this->valorDesonerado <= 0) {
            return 0;
        }
        $aliquota = $this->getAliquotaIcms();
        return round($this->valorDesonerado * ($aliquota / 100), 2);
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
            'difal' => $this->calcularDifal(),
            'desoneracao' => [
                'valor' => $this->calcularValorDesoneracao(),
                'motivo' => $this->getJustificativaDesoneracao()
            ]
        ];
    }

    public function exibirResultadosDetalhados(): array
    {
        $resultado = $this->calcularTributos();
        
        return [
            'regime_tributario' => $resultado['regime_tributario'],
            'valor_produto' => $resultado['valor_produto'],
            'icms' => [
                'base_calculo' => $resultado['icms']['base_calculo'],
                'base_desoneracao' => $resultado['icms']['base_desoneracao'],
                'aliquota' => $resultado['icms']['aliquota'],
                'valor' => $resultado['icms']['valor']
            ],
            'icms_st' => [
                'base_calculo' => $resultado['icms_st']['base_calculo'],
                'valor' => $resultado['icms_st']['valor']
            ],
            'difal' => [
                'base_calculo' => $resultado['valor_produto'],
                'aliquota_origem' => $this->getAliquotaIcms(),
                'aliquota_destino' => $this->getAliquotaIcmsDestino(),
                'valor' => $resultado['difal']['valor']
            ],
            'ipi' => [
                'base_calculo' => $resultado['valor_produto'],
                'aliquota' => $this->aliquotaIpi,
                'valor' => $resultado['ipi']['valor']
            ],
            'ibs' => [
                'base_calculo' => $resultado['valor_produto'],
                'aliquota' => $this->aliquotaIbs,
                'valor' => $resultado['ibs']['valor']
            ],
            'iva' => [
                'base_calculo' => $resultado['valor_produto'],
                'aliquota' => $this->aliquotaIva,
                'valor' => $resultado['iva']['valor']
            ],
            'fcp' => [
                'base_calculo' => $resultado['valor_produto'],
                'aliquota' => $this->aliquotaFcp,
                'valor' => $resultado['fcp']['valor']
            ],
            'desoneracao' => [
                'base_calculo' => $this->valorDesonerado,
                'aliquota' => $this->getAliquotaIcms(),
                'valor' => $resultado['desoneracao']['valor'],
                'motivo' => $resultado['desoneracao']['motivo']
            ]
        ];
    }

    public function exibirDadosObjeto(): array
    {        
        $dados = [
            'dados_entrada' => [
                'valor_produto' => $this->valorProduto,
                'uf_origem' => $this->ufOrigem,
                'uf_destino' => $this->ufDestino,
                'aliquota_red_bc_icms' => $this->aliquotaRedBcIcms,
                'mva_ajustada' => $this->mvaAjustada,
                'aliquota_ipi' => $this->aliquotaIpi,
                'aliquota_ibs' => $this->aliquotaIbs,
                'aliquota_iva' => $this->aliquotaIva,
                'aliquota_fcp' => $this->aliquotaFcp,
                'valor_desonerado' => $this->valorDesonerado,
                'motivo_desoneracao' => $this->getJustificativaDesoneracao(),
                'regime_tributario' => $this->getRegimeTributario()
            ],
            'resultados' => $this->calcularTributos()
        ];

        return $dados;
    }
}

 