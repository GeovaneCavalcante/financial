<?php

namespace App\Helpers;

use App\Exceptions\CustomErrors\AppException;

class ValidateFields
{
    /**
     * Método que verifica obrigatoriedade de um valor em um array
     *
     * @param array $fieldsRequired
     * @param       $data
     *
     * @throws AppException
     */
    public static function required($fieldsRequired = [], $data)
    {
        foreach ($fieldsRequired as $field) {
            if (!isset($data[$field]) || !$data[$field]) {
                throw new AppException("$field field is required.", 422);
            }
        }
    }

}
