<?php

namespace App\Http\Controllers\Abstracts;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * Class AbstractHelper
 * @author Michal Mendel <mendel.michal096@gmail.com>
 */
abstract class AbstractHelper
{
    /**
     * @return mixed
     */
    abstract public function getList();

    /**
     * @param string $id
     * @param string $errorBag
     * @return mixed
     */
    abstract public function getItem(string $id, string $errorBag);

    /**
     * Secure from special chars (attacks)
     * @param string $value
     * @return string|null
     */
    public function secureValue(string $value)
    {
        return empty($value) && !is_numeric($value) ? NULL : trim(strip_tags($value));
    }

    /**
     * @param string $field
     * @param string $message
     * @param string $errorBag
     * @throws ValidationException
     */
    public function throwCustomErrorBag(string $field, string $message, string $errorBag)
    {
        /* Validator with empty data and rules */
        $validator = Validator::make([], []);
        $validator->errors()->add($field, $message);

        throw new ValidationException($validator, null, $errorBag);
    }
}
