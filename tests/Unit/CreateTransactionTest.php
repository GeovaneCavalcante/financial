<?php

namespace Tests\Unit;

use App\Exceptions\CustomErrors\AppException;
use App\Models\Transaction;
use App\Models\User;
use App\Repositories\Finances\TransactionRepository;
use App\Repositories\Users\UserRepository;
use App\Services\Finances\CreateTransactionService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


class CreateTransactionTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use DatabaseMigrations;

    private $userRepository;
    private $transactionRepository;

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
    }

    /**
     * Criação de uma transação
     *
     * @throws AppException
     */
    public function testCreateTransaction()
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

        $data = array(
            "sender_id"   => $payer->id,
            "receiver_id" => $payee->id,
            "value"       => '500.00',
        );

        $createTransactionService = new CreateTransactionService($this->transactionRepository);

        $this->assertInstanceOf(Transaction::class, $createTransactionService->execute($data));
    }

    /**
     * Verifica se foi passado o id do pagador
     */
    public function testTransactionRequiredSenderId()
    {
        $data = array(
            "full_name"  => "Geovane Feitosa",
            "email"      => "teasta2@outlook.com",
            "cpf"        => "61a41432q",
            "password"   => "teste",
            "profile_id" => "516d2501-72ab-49a7-bd62-0a8949929014"
        );

        $payee = $this->userRepository->save($data);

        $data                     = array(
            "receiver_id" => $payee->id,
            "value"       => '500.00',
        );
        $createTransactionService = new CreateTransactionService($this->transactionRepository);

        try {
            $createTransactionService->execute($data);
        } catch (\Exception $e) {
            $this->assertEquals(422, $e->getCode());
            $this->assertEquals('sender_id field is required.', $e->getMessage());
            $this->assertInstanceOf(AppException::class, $e);
        }
    }

    /**
     * Verifica se foi passado o id do recebedor
     *
     */
    public function testTransactionRequiredReceiverId()
    {
        $data = array(
            "full_name"  => "Geovane Feitosa",
            "email"      => "teastae@outlook.com",
            "cpf"        => "61a41a32q",
            "password"   => "teste",
            "profile_id" => "df13adc5-1d01-4113-8119-a6e3f7836fc0"
        );

        $payer = $this->userRepository->save($data);

        $data                     = array(
            "sender_id" => $payer->id,
            "value"     => '500.00',
        );
        $createTransactionService = new CreateTransactionService($this->transactionRepository);

        try {
            $createTransactionService->execute($data);
        } catch (\Exception $e) {
            $this->assertEquals(422, $e->getCode());
            $this->assertEquals('receiver_id field is required.', $e->getMessage());
            $this->assertInstanceOf(AppException::class, $e);
        }
    }

    /**
     * Verifica se o valor foi passado
     *
     */
    public function testTransactionRequiredValue()
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

        $data = array(
            "sender_id"   => $payer->id,
            "receiver_id" => $payee->id,
        );

        $createTransactionService = new CreateTransactionService($this->transactionRepository);

        try {
            $createTransactionService->execute($data);
        } catch (\Exception $e) {
            $this->assertEquals(422, $e->getCode());
            $this->assertEquals('value field is required.', $e->getMessage());
            $this->assertInstanceOf(AppException::class, $e);
        }
    }

}
