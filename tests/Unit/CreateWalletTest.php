<?php

namespace Tests\Unit;

use App\Exceptions\CustomErrors\AppException;
use App\Models\User;
use App\Models\Wallet;
use App\Repositories\Finances\WalletRepository;
use App\Repositories\Users\UserRepository;
use App\Services\Finances\CreateWalletService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateWalletTest extends TestCase
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
     * Criação de uma carteira
     *
     * @throws AppException
     */
    public function testCreateWallet()
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
            "user_id" => $user->id,
        );

        $createWalletService = new CreateWalletService($this->walletRepository, $this->userRepository);

        $this->assertInstanceOf(Wallet::class, $createWalletService->execute($data));
    }

    /**
     * Verifica se o id do usuário foi fornecido
     *
     */
    public function testWalletRequiredUserId()
    {
        $data = array();

        $createWalletService = new CreateWalletService($this->walletRepository, $this->userRepository);

        try {
            $createWalletService->execute($data);
        } catch (\Exception $e) {
            $this->assertEquals(422, $e->getCode());
            $this->assertEquals('user_id field is required.', $e->getMessage());
            $this->assertInstanceOf(AppException::class, $e);
        }
    }

    /**
     * Verifica se o usuário fornecido é existente
     *
     */
    public function testUserNotExists()
    {
        $data = array(
            'user_id' => '123'
        );

        $createWalletService = new CreateWalletService($this->walletRepository, $this->userRepository);

        try {
            $createWalletService->execute($data);
        } catch (\Exception $e) {
            $this->assertEquals(401, $e->getCode());
            $this->assertEquals('User does not exist.', $e->getMessage());
            $this->assertInstanceOf(AppException::class, $e);
        }
    }


}
