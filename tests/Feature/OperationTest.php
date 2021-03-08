<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OperationTest extends TestCase
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
     * Criação de uma operação
     *
     */
    public function testOperationCreate()
    {
        $payload = [
            'full_name'  => $this->faker->name(),
            'email'      => 'exemple@exemple.com',
            'cpf'        => '99999999999',
            'password'   => '123456',
            'profile_id' => '516d2501-72ab-49a7-bd62-0a8949929014'
        ];

        $content = $this->postJson('/api/users', $payload)->getContent();
        $payer   = json_decode($content);

        $payload = [
            'full_name'  => $this->faker->name(),
            'email'      => 'exemple2@exemple.com',
            'cpf'        => '99999999998',
            'password'   => '1234567',
            'profile_id' => '516d2501-72ab-49a7-bd62-0a8949929014'
        ];

        $content = $this->postJson('/api/users', $payload)->getContent();
        $payee   = json_decode($content);

        $payload = [
            'value' => '100.00',
            'payer' => $payer->id,
            'payee' => $payee->id,
        ];

        $this->postJson('/api/transaction', $payload)
            ->assertStatus(200);
    }

    /**
     * Verifica se o valor foi passado para operação
     *
     */
    public function testOperationRequiredValue()
    {
        $payload = [
            'full_name'  => $this->faker->name(),
            'email'      => 'exemple@exemple.com',
            'cpf'        => '99999999999',
            'password'   => '123456',
            'profile_id' => '516d2501-72ab-49a7-bd62-0a8949929014'
        ];

        $content = $this->postJson('/api/users', $payload)->getContent();
        $payer   = json_decode($content);

        $payload = [
            'full_name'  => $this->faker->name(),
            'email'      => 'exemple2@exemple.com',
            'cpf'        => '99999999998',
            'password'   => '1234567',
            'profile_id' => 'df13adc5-1d01-4113-8119-a6e3f7836fc0'
        ];

        $content = $this->postJson('/api/users', $payload)->getContent();
        $payee   = json_decode($content);

        $payload = [
            'payer' => $payer->id,
            'payee' => $payee->id,
        ];

        $this->postJson('/api/transaction', $payload)
            ->assertStatus(422)
            ->assertJson(
                [
                    'value' => ['The value field is required.'],
                ]
            );
    }

    /**
     * Verifica se o pagador foi passado para operação
     *
     */
    public function testOperationRequiredPayer()
    {
        $payload = [
            'full_name'  => $this->faker->name(),
            'email'      => 'exemple2@exemple.com',
            'cpf'        => '99999999998',
            'password'   => '1234567',
            'profile_id' => 'df13adc5-1d01-4113-8119-a6e3f7836fc0'
        ];

        $content = $this->postJson('/api/users', $payload)->getContent();
        $payee   = json_decode($content);

        $payload = [
            'value' => '100.00',
            'payee' => $payee->id,
        ];

        $this->postJson('/api/transaction', $payload)
            ->assertStatus(422)
            ->assertJson(
                [
                    'payer' => ['The payer field is required.'],
                ]
            );
    }

    /**
     * Verifica se o recebedor foi passado para operação
     *
     */
    public function testOperationRequiredPayee()
    {
        $payload = [
            'full_name'  => $this->faker->name(),
            'email'      => 'exemple2@exemple.com',
            'cpf'        => '99999999998',
            'password'   => '1234567',
            'profile_id' => '516d2501-72ab-49a7-bd62-0a8949929014'
        ];

        $content = $this->postJson('/api/users', $payload)->getContent();
        $payer   = json_decode($content);

        $payload = [
            'value' => '100.00',
            'payer' => $payer->id,
        ];

        $this->postJson('/api/transaction', $payload)
            ->assertStatus(422)
            ->assertJson(
                [
                    'payee' => ['The payee field is required.'],
                ]
            );
    }

    /**
     * Verifica se o pagador não existe
     *
     */
    public function testPayerNotExists()
    {
        $payload = [
            'full_name'  => $this->faker->name(),
            'email'      => 'exemple2@exemple.com',
            'cpf'        => '99999999998',
            'password'   => '1234567',
            'profile_id' => 'df13adc5-1d01-4113-8119-a6e3f7836fc0'
        ];

        $content = $this->postJson('/api/users', $payload)->getContent();
        $payee   = json_decode($content);

        $payload = [
            'value' => '100.00',
            'payer' => '123456',
            'payee' => $payee->id,
        ];

        $this->postJson('/api/transaction', $payload)
            ->assertStatus(422)
            ->assertJson(
                [
                    'payer' => ['The selected payer is invalid.'],
                ]
            );
    }

    /**
     * Verifica se o recebedor não existe
     *
     */
    public function testPayeeNotExists()
    {
        $payload = [
            'full_name'  => $this->faker->name(),
            'email'      => 'exemple2@exemple.com',
            'cpf'        => '99999999998',
            'password'   => '1234567',
            'profile_id' => '516d2501-72ab-49a7-bd62-0a8949929014'
        ];

        $content = $this->postJson('/api/users', $payload)->getContent();
        $payer   = json_decode($content);

        $payload = [
            'value' => '100.00',
            'payee' => '123456',
            'payer' => $payer->id,
        ];

        $this->postJson('/api/transaction', $payload)
            ->assertStatus(422)
            ->assertJson(
                [
                    'payee' => ['The selected payee is invalid.'],
                ]
            );
    }

    /**
     * Verifica se o valor é válido
     *
     */
    public function testValueNonValid()
    {
        $payload = [
            'full_name'  => $this->faker->name(),
            'email'      => 'exemple2@exemple.com',
            'cpf'        => '99999999998',
            'password'   => '1234567',
            'profile_id' => '516d2501-72ab-49a7-bd62-0a8949929014'
        ];

        $content = $this->postJson('/api/users', $payload)->getContent();
        $payer   = json_decode($content);

        $payload = [
            'full_name'  => $this->faker->name(),
            'email'      => 'exemple@exemple.com',
            'cpf'        => '99999999999',
            'password'   => '1234567',
            'profile_id' => 'df13adc5-1d01-4113-8119-a6e3f7836fc0'
        ];

        $content = $this->postJson('/api/users', $payload)->getContent();
        $payee   = json_decode($content);

        $payload = [
            'value' => 'teste',
            'payee' => $payee->id,
            'payer' => $payer->id,
        ];

        $this->postJson('/api/transaction', $payload)
            ->assertStatus(422)
            ->assertJson(
                [
                    'value' => ['It is not a monetary value.'],
                ]
            );
    }

    /**
     * Verifica se o pagador não tem permissão pra fazer uma operação
     *
     */
    public function testUserNotAllowedToPay()
    {
        $payload = [
            'full_name'  => $this->faker->name(),
            'email'      => 'exemple2@exemple.com',
            'cpf'        => '99999999998',
            'password'   => '1234567',
            'profile_id' => 'df13adc5-1d01-4113-8119-a6e3f7836fc0'
        ];

        $content = $this->postJson('/api/users', $payload)->getContent();
        $payer   = json_decode($content);

        $payload = [
            'full_name'  => $this->faker->name(),
            'email'      => 'exemple@exemple.com',
            'cpf'        => '99999999999',
            'password'   => '1234567',
            'profile_id' => 'df13adc5-1d01-4113-8119-a6e3f7836fc0'
        ];

        $content = $this->postJson('/api/users', $payload)->getContent();
        $payee   = json_decode($content);

        $payload = [
            'value' => '100.00',
            'payee' => $payee->id,
            'payer' => $payer->id,
        ];

        $this->postJson('/api/transaction', $payload)
            ->assertStatus(401)
            ->assertJson(
                [
                    'message' => 'User is not allowed to make a transaction.',
                ]
            );
    }

    /**
     * Verifica se o pagador não tem saldo pra fazer uma operação
     *
     */
    public function testUserNotBalanceToPay()
    {
        $payload = [
            'full_name'  => $this->faker->name(),
            'email'      => 'exemple2@exemple.com',
            'cpf'        => '99999999998',
            'password'   => '1234567',
            'profile_id' => '516d2501-72ab-49a7-bd62-0a8949929014'
        ];

        $content = $this->postJson('/api/users', $payload)->getContent();
        $payer   = json_decode($content);

        $payload = [
            'full_name'  => $this->faker->name(),
            'email'      => 'exemple@exemple.com',
            'cpf'        => '99999999999',
            'password'   => '1234567',
            'profile_id' => '516d2501-72ab-49a7-bd62-0a8949929014'
        ];

        $content = $this->postJson('/api/users', $payload)->getContent();
        $payee   = json_decode($content);

        $payload = [
            'value' => '1000.00',
            'payee' => $payee->id,
            'payer' => $payer->id,
        ];

        $this->postJson('/api/transaction', $payload)
            ->assertStatus(401)
            ->assertJson(
                [
                    'message' => 'Insufficient balance to carry out transaction.',
                ]
            );
    }

    /**
     * Revertendo uma operação
     *
     */
    public function testReverseOperation()
    {
        $payload = [
            'full_name'  => $this->faker->name(),
            'email'      => 'exemple@exemple.com',
            'cpf'        => '99999999999',
            'password'   => '123456',
            'profile_id' => '516d2501-72ab-49a7-bd62-0a8949929014'
        ];

        $content = $this->postJson('/api/users', $payload)->getContent();
        $payer   = json_decode($content);

        $payload = [
            'full_name'  => $this->faker->name(),
            'email'      => 'exemple2@exemple.com',
            'cpf'        => '99999999998',
            'password'   => '1234567',
            'profile_id' => '516d2501-72ab-49a7-bd62-0a8949929014'
        ];

        $content = $this->postJson('/api/users', $payload)->getContent();
        $payee   = json_decode($content);

        $payload = [
            'value' => '100.00',
            'payer' => $payer->id,
            'payee' => $payee->id,
        ];

        $content = $this->postJson('/api/transaction', $payload)->getContent();

        $transaction = json_decode($content);

        $this->postJson('/api/transaction/' . $transaction->id . '/reversal')
            ->assertStatus(200);
    }

    /**
     * Verifica se a transação existe
     *
     */
    public function testReverseOperationIdNotExist()
    {
        $id = '123';

        $this->postJson('/api/transaction/' . $id . '/reversal')
            ->assertStatus(422)
            ->assertJson(
                [
                    'id' => ['The selected id is invalid.'],
                ]
            );
    }

}
