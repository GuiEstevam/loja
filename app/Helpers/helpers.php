<?php

if (!function_exists('order_status_label')) {
    function order_status_label($status)
    {
        $labels = [
            'pending'   => 'Aguardando',
            'paid'      => 'Pago',
            'shipped'   => 'Enviado',
            'delivered' => 'Entregue',
            'canceled'  => 'Cancelado',
        ];
        return $labels[$status] ?? ucfirst($status);
    }
}

// Aqui você pode adicionar outros helpers globais
