<?php

namespace App\Services\Auth;

use GuzzleHttp\Client;
use App\Exceptions\CustomErrors\AppException;
use App\Services\IBaseService;

class TransactionAuthorizer implements IBaseService
{

    public function __construct()
    {
    }

    /**
     * Verifica uma autorização externa para realizar uma transação
     *
     * @param array $attributes
     *
     * @return bool
     * @throws AppException
     */
    public function execute(array $attributes = []): bool
    {
        $client = new Client();
        try {
            $client->get('https://run.mocky.io/v3/b19f7b9f-9cbf-4fc6-ad22-dc30601aec04');
        } catch (\Throwable $th) {
            throw new AppException('Transaction external authorization failed', 401);
        }

        return true;
    }
}
