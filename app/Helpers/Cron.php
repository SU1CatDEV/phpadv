<?php

namespace App\Helpers;

use App\Exceptions\MalformedCronException;

class Cron {
    public function getCronValues(string $cronString): array
    {

        $cronValues = explode(" ", $cronString);

        $cronValues = array_map(function ($item) {

            return $item === "*" ? null : $item;

        }, $cronValues);

        if (count($cronValues) < 5) {
            throw new MalformedCronException('Cron string must have 5 values.');
        }

        return $cronValues;

    }
}