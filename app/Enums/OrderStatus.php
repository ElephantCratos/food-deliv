<?php

namespace App\Enums;

enum OrderStatus: int
{
    case IN_PROGRESS = 1;
    case IN_KITCHEN = 2;
    case WAITING_FOR_COURIER = 3;
    case COURIER_ON_THE_WAY = 4;
    case COMPLETED = 5;
    case DECLINED = 6;

    public function label(): string
    {
        return match($this) {
            self::IN_PROGRESS => 'In Progress',
            self::IN_KITCHEN => 'In Kitchen',
            self::WAITING_FOR_COURIER => 'Waiting for Courier',
            self::COURIER_ON_THE_WAY => 'Courier on the Way',
            self::COMPLETED => 'Completed',
            self::DECLINED => 'Declined',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}