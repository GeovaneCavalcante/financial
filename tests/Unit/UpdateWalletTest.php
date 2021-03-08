<?php

namespace Tests\Unit;

use App\Exceptions\CustomErrors\AppException;
use App\Models\User;
use App\Models\Wallet;
use App\Repositories\Finances\WalletRepository;
use App\Repositories\Users\UserRepository;
use App\Services\Finances\UpdateWalletService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateWalletTest extends TestCase
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
     * Faz a atualização da carteira de um usuário
     *
     * @throws AppException
     */
    public function testUpdateWallet()
    {
        $data = array(
            "full_name"  => "Geovane Feitosa",
            "email"      => "teastae@outlook.com",
            "cpf"        => "61a41a32q",
            "password"   => "teste",
            "profile_id" => "df13adc5-1d01-4113-8119-a6e3f7836fc0"
        );

        $user = $this->userRepository->save($data);

        $userWallet = array(
            "user_id" => $user->id,
            "balance" => 500,
        );

        $wallet = $this->walletRepository->save($userWallet);

        $data = array(
            "id"      => $wallet->id,
            "balance" => 400,
        );

        $updateWalletService = new UpdateWalletService($this->walletRepository);

        $this->assertIsBool(true, $updateWalletService->execute($data));
    }

    /**
     * Verifica se tem uma carteira existente
     *
     */
    public function testWalletNotExists()
    {
        $data = array(
            "id" => "123",
        );

        $updateWalletService = new UpdateWalletService($this->walletRepository);

        try {
            $updateWalletService->execute($data);
        } catch (\Exception $e) {
            $this->assertEquals(401, $e->getCode());
            $this->assertEquals('Wallet does not exist.', $e->getMessage());
            $this->assertInstanceOf(AppException::class, $e);
        }
    }
}
