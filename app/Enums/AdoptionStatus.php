<?php

namespace App\Enums;

enum AdoptionStatus: string
{
    case PENDIENTE = 'pendiente';
    case APROBADA = 'aprobada';
    case RECHAZADA = 'rechazada';
    case CANCELADA = 'cancelada';


    public function isFinal(): bool
    {
        return in_array($this, [
            self::APROBADA,
            self::RECHAZADA,
            self::CANCELADA,
        ]);
    }
}