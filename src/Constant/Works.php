<?php

namespace App\Constant;

class Works
{
    const PROGRAMMER = 'Programmer';
    const TESTER = 'Tester';
    const DB_ADMINISTRATOR = 'DB Administrator';

    /**
     * @return string[]
     */
    public static function getAll(): array
    {
        return [
            self::PROGRAMMER,
            self::TESTER,
            self::DB_ADMINISTRATOR,
        ];
    }
}