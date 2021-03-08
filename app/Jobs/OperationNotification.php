<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use GuzzleHttp\Client;
use App\Exceptions\CustomErrors\AppException;

class OperationNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $msg = '')
    {
        $this->user = $user;
    }

    /**
     * @throws AppException
     */
    public function handle()
    {
        $client = new Client();
        try {
            $client->get('https://run.mocky.io/v3/b19f7b9f-9cbf-4fc6-ad22-dc30601aec04');
        } catch (\Throwable $th) {
            throw new AppException('External notification communication failure.', 401);
        }
    }
}
