<?php
namespace TribCalc;

class RateioDocumentoFiscal
{
    /**
     * Método para calcular a distribuição proporcional de frete, seguro, desconto e outras despesas
     * entre os itens de uma lista.
     *
     * @param array $itens Array de objetos stdClass representando os itens com valor_total.
     * @param float $valor_frete Valor total do frete a ser distribuído.
     * @param float $valor_seguro Valor total do seguro a ser distribuído.
     * @param float $valor_desconto Valor total do desconto a ser distribuído.
     * @param float $valor_outras_despesas Valor total de outras despesas a ser distribuído.
     * @return array Array de objetos contendo os itens com os valores rateados adicionados.
     */
    public static function calcularRateio($itens, $valor_frete = 0, $valor_seguro = 0, $valor_desconto = 0, $valor_outras_despesas = 0)
    {
        // Inicializa o array para armazenar os itens com valores rateados
        $itens_rateados = [];

        // Calcula o valor total de todos os itens
        $valor_total_itens = 0;
        foreach ($itens as $item) {
            if (!isset($item->valor_total)) {
                throw new InvalidArgumentException('Todos os itens devem ter a propriedade valor_total definida');
            }
            $valor_total_itens += $item->valor_total;
        }

        // Inicializa as variáveis para a soma dos rateios
        $soma_frete = 0;
        $soma_seguro = 0;
        $soma_desconto = 0;
        $soma_outras_despesas = 0;

        // Percorre cada item para calcular e distribuir os valores rateados
        foreach ($itens as $item) {
            // Calcula a porcentagem do valor total que representa o item
            $percentual_item = $valor_total_itens > 0 ? $item->valor_total / $valor_total_itens : 0;

            // Calcula o rateio específico para cada item
            $frete_rateado = $valor_frete * $percentual_item;
            $seguro_rateado = $valor_seguro * $percentual_item;
            $desconto_rateado = $valor_desconto * $percentual_item;
            $outras_despesas_rateado = $valor_outras_despesas * $percentual_item;

            // Cria um novo objeto stdClass para armazenar os dados do item com o rateio
            $item_rateado = clone $item;

            // Adiciona os valores rateados ao objeto
            $item_rateado->frete_rateado = round($frete_rateado, 2);
            $item_rateado->seguro_rateado = round($seguro_rateado, 2);
            $item_rateado->desconto_rateado = round($desconto_rateado, 2);
            $item_rateado->outras_despesas_rateado = round($outras_despesas_rateado, 2);

            // Adiciona as somas dos rateios
            $soma_frete += $item_rateado->frete_rateado;
            $soma_seguro += $item_rateado->seguro_rateado;
            $soma_desconto += $item_rateado->desconto_rateado;
            $soma_outras_despesas += $item_rateado->outras_despesas_rateado;

            // Adiciona o objeto ao array de resultados
            $itens_rateados[] = $item_rateado;
        }

        // Verifica e ajusta o primeiro item com as diferenças de arredondamento
        $diferenca_frete = round($valor_frete - $soma_frete, 2);
        $diferenca_seguro = round($valor_seguro - $soma_seguro, 2);
        $diferenca_desconto = round($valor_desconto - $soma_desconto, 2);
        $diferenca_outras_despesas = round($valor_outras_despesas - $soma_outras_despesas, 2);

        // Ajusta o primeiro item com as diferenças, se necessário
        if (count($itens_rateados) > 0) {
            $itens_rateados[0]->frete_rateado += $diferenca_frete;
            $itens_rateados[0]->seguro_rateado += $diferenca_seguro;
            $itens_rateados[0]->desconto_rateado += $diferenca_desconto;
            $itens_rateados[0]->outras_despesas_rateado += $diferenca_outras_despesas;
        }

        return $itens_rateados;
    }
}