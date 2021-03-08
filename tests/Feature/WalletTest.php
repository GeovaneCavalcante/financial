<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WalletTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use DatabaseMigrations;

    /**
     * Rondando os seed da aplicação
     *
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Buscar Carteira de um usuário
     *
     */
    public function testShowWallet()
    {
        $payload = [
            'full_name'  => $this->faker->name(),
            'email'      => 'exemple@exemple.com',
            'cpf'        => '99999999999',
            'password'   => '123456',
            'profile_id' => 'df13adc5-1d01-4113-8119-a6e3f7836fc0'
        ];

        $content = $this->postJson('/api/users', $payload)->getContent();
        $user    = json_decode($content);
        $this->getJson('/api/wallets/' . $user->id)
            ->assertStatus(200);
    }

    /**
     * Verifica se o id do usuário é valido
     *
     */
    public function testCheckUserIdIsIncorrect()
    {
        $id = '123';
        $this->getJson('/api/wallets/' . $id)
            ->assertStatus(422)
            ->assertJson(
                [
                    "id" => ["The selected id is invalid."]
                ]
            );
    }

}
