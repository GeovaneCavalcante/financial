<?php

namespace Tests\Unit;


use App\Exceptions\CustomErrors\AppException;
use App\Models\User;
use App\Models\Wallet;
use App\Repositories\Finances\WalletRepository;
use App\Repositories\Users\UserRepository;
use App\Services\Auth\CheckPermissionTransactionService;
use App\Services\Finances\CreateWalletService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PermissionTransactionTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use DatabaseMigrations;

    private $userRepository;
    private $walletRepository;

    /**
     * Configurações e criações de dependencias
     *
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->userRepository   = new UserRepository(new User());
        $this->walletRepository = new WalletRepository(new Wallet());
    }

    /**
     * Verifica se o usuário tem permissão para fazer transação
     *
     * @throws AppException
     */
    public function testUserPermission()
    {
        $data = array(
            "full_name"  => "Geovane Feitosa",
            "email"      => "teastae@outlook.com",
            "cpf"        => "61a41a32q",
            "password"   => "teste",
            "profile_id" => "516d2501-72ab-49a7-bd62-0a8949929014"
        );

        $user = $this->userRepository->save($data);

        $data = array(
            'id'   => $user->id,
            'name' => 'make_transaction'
        );

        $checkPermissionTransactionService = new CheckPermissionTransactionService($this->userRepository);

        $this->assertIsObject($checkPermissionTransactionService->execute($data));
    }

    /**
     * Verifica se o usuário não tem permissão para fazer transação
     *
     * @throws AppException
     */
    public function testUserNotAllowed()
    {
        $data = array(
            "full_name"  => "Geovane Feitosa",
            "email"      => "teastae@outlook.com",
            "cpf"        => "61a41a32q",
            "password"   => "teste",
            "profile_id" => "df13adc5-1d01-4113-8119-a6e3f7836fc0"
        );

        $user = $this->userRepository->save($data);

        $data = array(
            'id'   => $user->id,
            'name' => 'make_transaction'
        );

        $checkPermissionTransactionService = new CheckPermissionTransactionService($this->userRepository);

        $this->assertNull($checkPermissionTransactionService->execute($data));
    }

    /**
     * Verifica se o id do usuário foi fornecido
     *
     */
    public function testRequiredUserId()
    {
        $data = array(
            'name' => 'make_transaction'
        );

        $checkPermissionTransactionService = new CheckPermissionTransactionService($this->userRepository);

        try {
            $checkPermissionTransactionService->execute($data);
        } catch (\Exception $e) {
            $this->assertEquals(422, $e->getCode());
            $this->assertEquals('id field is required.', $e->getMessage());
            $this->assertInstanceOf(AppException::class, $e);
        }
    }
}
