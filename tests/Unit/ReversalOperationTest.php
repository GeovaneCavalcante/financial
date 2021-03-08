<?php

namespace Tests\Unit;

use App\Exceptions\CustomErrors\AppException;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Repositories\Finances\TransactionRepository;
use App\Repositories\Finances\WalletRepository;
use App\Repositories\Users\UserRepository;
use App\Services\Finances\CreateTransactionService;
use App\Services\Finances\ReversalOperationService;
use App\Services\Finances\ShowUserWalletService;
use App\Services\Finances\UpdateWalletService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReversalOperationTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use DatabaseMigrations;

    private $userRepository;
    private $transactionRepository;
    private $walletRepository;

    /**
     * Configurações e criações de dependencias
     *
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->userRepository        = new UserRepository(new User());
        $this->transactionRepository = new TransactionRepository(new Transaction());
        $this->walletRepository      = new WalletRepository(new Wallet());
    }


    /**
     * Faz a reversão de uma operação
     *
     * @throws AppException
     */
    public function testRevertTransaction()
    {
        $data = array(
            "full_name"  => "Geovane Feitosa",
            "email"      => "teastae@outlook.com",
            "cpf"        => "61a41a32q",
            "password"   => "teste",
            "profile_id" => "df13adc5-1d01-4113-8119-a6e3f7836fc0"
        );

        $payer = $this->userRepository->save($data);

        $data = array(
            "full_name"  => "Geovane Feitosa",
            "email"      => "teasta2@outlook.com",
            "cpf"        => "61a41432q",
            "password"   => "teste",
            "profile_id" => "516d2501-72ab-49a7-bd62-0a8949929014"
        );

        $payee = $this->userRepository->save($data);

        $payerWallet = array(
            "user_id" => $payee->id,
            "balance" => 500,
        );

        $this->walletRepository->save($payerWallet);

        $payeeWallet = array(
            "user_id" => $payee->id,
            "balance" => 500,
        );

        $this->walletRepository->save($payeeWallet);

        $data = array(
            "sender_id"   => $payer->id,
            "receiver_id" => $payee->id,
            "value"       => '500.00',
        );

        $createTransactionService = new CreateTransactionService($this->transactionRepository);

        $transaction = $createTransactionService->execute($data);

        $showUserWalletService    = new ShowUserWalletService($this->userRepository);
        $updateWalletService      = new UpdateWalletService($this->walletRepository);
        $reversalOperationService = new ReversalOperationService(
            $this->transactionRepository,
            $showUserWalletService,
            $updateWalletService
        );

        $this->assertInstanceOf(Transaction::class, $reversalOperationService->execute($transaction->toArray()));
    }

}
