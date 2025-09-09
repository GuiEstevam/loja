<?php

if (!function_exists('translatePaymentMethod')) {
    /**
     * Traduz o método de pagamento para português
     */
    function translatePaymentMethod($method)
    {
        $translations = [
            'credit_card' => 'Cartão de Crédito',
            'debit_card' => 'Cartão de Débito',
            'pix' => 'PIX',
            'boleto' => 'Boleto Bancário',
            'paypal' => 'PayPal',
            'bank_transfer' => 'Transferência Bancária',
            'cash' => 'Dinheiro',
            'check' => 'Cheque',
        ];

        return $translations[$method] ?? ucfirst(str_replace('_', ' ', $method));
    }
}

if (!function_exists('translateOrderStatus')) {
    /**
     * Traduz o status do pedido para português
     */
    function translateOrderStatus($status)
    {
        $translations = [
            'pending' => 'Pendente',
            'paid' => 'Pago',
            'processing' => 'Processando',
            'shipped' => 'Enviado',
            'delivered' => 'Entregue',
            'cancelled' => 'Cancelado',
            'refunded' => 'Reembolsado',
        ];

        return $translations[$status] ?? ucfirst($status);
    }
}
