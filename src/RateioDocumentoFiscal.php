<?php

namespace TribCalc;

class RateioDocumentoFiscal
{
    public static function calcularRateio(array $itens, float $valor_frete = 0, float $valor_seguro = 0, float $valor_desconto = 0, float $valor_outros = 0)
    {
        $total_itens = 0;
        $itens_calculados = [];

        // Verifica se há itens e calcula o total
        if (empty($itens)) {
            return [];
        }

        // Calcula o total dos itens
        foreach ($itens as $item) {
            if (!isset($item->valor_total) || !is_numeric($item->valor_total)) {
                throw new \InvalidArgumentException('Todos os itens devem ter a propriedade valor_total numérica');
            }
            $total_itens += $item->valor_total;
        }

        if ($total_itens <= 0) {
            throw new \InvalidArgumentException('O valor total dos itens deve ser maior que zero');
        }

        // Calcula o rateio para cada item
        foreach ($itens as $item) {
            $item_calculado = clone $item;
            $percentual = $item->valor_total / $total_itens;

            $item_calculado->valor_frete = round($valor_frete * $percentual, 2);
            $item_calculado->valor_seguro = round($valor_seguro * $percentual, 2);
            $item_calculado->valor_desconto = round($valor_desconto * $percentual, 2);
            $item_calculado->valor_outros = round($valor_outros * $percentual, 2);

            $itens_calculados[] = $item_calculado;
        }

        // Ajusta eventuais diferenças de arredondamento no primeiro item
        $total_frete = array_sum(array_column($itens_calculados, 'valor_frete'));
        $total_seguro = array_sum(array_column($itens_calculados, 'valor_seguro'));
        $total_desconto = array_sum(array_column($itens_calculados, 'valor_desconto'));
        $total_outros = array_sum(array_column($itens_calculados, 'valor_outros'));

        $itens_calculados[0]->valor_frete += $valor_frete - $total_frete;
        $itens_calculados[0]->valor_seguro += $valor_seguro - $total_seguro;
        $itens_calculados[0]->valor_desconto += $valor_desconto - $total_desconto;
        $itens_calculados[0]->valor_outros += $valor_outros - $total_outros;

        return $itens_calculados;
    }
}