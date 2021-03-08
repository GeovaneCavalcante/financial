<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


class UserTest extends TestCase
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
     * Criação de um usuário
     *
     */
    public function testUserCreate()
    {
        $payload = [
            'full_name'  => $this->faker->name(),
            'email'      => 'exemple@exemple.com',
            'cpf'        => '99999999999',
            'password'   => '123456',
            'profile_id' => 'df13adc5-1d01-4113-8119-a6e3f7836fc0'
        ];

        $this->postJson('/api/users', $payload)
            ->assertStatus(200);
    }

    /**
     * Verifica se está com perfil válido
     *
     */
    public function testUserProfileIsIncorrect()
    {
        $payload = [
            'full_name'  => $this->faker->name(),
            'email'      => 'exemple1@exemple.com',
            'cpf'        => '99999999998',
            'password'   => '123456',
            'profile_id' => '0000'
        ];

        $this->postJson('/api/users', $payload)
            ->assertStatus(422)
            ->assertJson(
                [
                    'profile_id' => ['The selected profile id is invalid.'],
                ]
            );
    }

    /**
     * Verifica se o nome completo foi passado
     *
     */
    public function testUserRequiredFullName()
    {
        $payload = [
            'email'      => 'exemple1@exemple.com',
            'cpf'        => '99999999998',
            'password'   => '123456',
            'profile_id' => '0000'
        ];

        $this->postJson('/api/users', $payload)
            ->assertStatus(422)
            ->assertJson(
                [
                    'full_name' => ['The full name field is required.'],
                ]
            );
    }

    /**
     * Verifica se o CPF foi passado
     *
     */
    public function testUserRequiredCpf()
    {
        $payload = [
            'full_name'  => $this->faker->name(),
            'email'      => 'exemple1@exemple.com',
            'password'   => '123456',
            'profile_id' => '0000'
        ];

        $this->postJson('/api/users', $payload)
            ->assertStatus(422)
            ->assertJson(
                [
                    'cpf' => ['The cpf field is required.'],
                ]
            );
    }

    /**
     * Verifica se o Email foi passado
     *
     */
    public function testUserRequiredEmail()
    {
        $payload = [
            'full_name'  => $this->faker->name(),
            'cpf'        => '99999999998',
            'password'   => '123456',
            'profile_id' => '0000'
        ];

        $this->postJson('/api/users', $payload)
            ->assertStatus(422)
            ->assertJson(
                [
                    'email' => ['The email field is required.'],
                ]
            );
    }

    /**
     * Verifica se a senha foi passada
     *
     */
    public function testUserRequiredPassword()
    {
        $payload = [
            'full_name'  => $this->faker->name(),
            'email'      => 'exemple2@exemple.com',
            'cpf'        => '99999999998',
            'profile_id' => '0000'
        ];

        $this->postJson('/api/users', $payload)
            ->assertStatus(422)
            ->assertJson(
                [
                    'password' => ['The password field is required.'],
                ]
            );
    }

    /**
     * Verifica se o perfil foi passado
     *
     */
    public function testUserRequiredProfile()
    {
        $payload = [
            'full_name' => $this->faker->name(),
            'email'     => 'exemple2@exemple.com',
            'cpf'       => '99999999998',
            'password'  => '123456',
        ];

        $this->postJson('/api/users', $payload)
            ->assertStatus(422)
            ->assertJson(
                [
                    'profile_id' => ['The profile id field is required.'],
                ]
            );
    }

    /**
     * Verifica se já existe usuário com mesmo Cpf
     *
     */
    public function testUserAlreadyExistsCpf()
    {
        $payload = [
            'full_name'  => $this->faker->name(),
            'email'      => 'exemple2@exemple.com',
            'cpf'        => '99999999998',
            'password'   => '123456',
            'profile_id' => 'df13adc5-1d01-4113-8119-a6e3f7836fc0'
        ];

        $this->postJson('/api/users', $payload);
        $this->postJson('/api/users', $payload)
            ->assertStatus(422)->assertJson(
                [
                    'cpf' => ['The cpf has already been taken.'],
                ]
            );
    }

    /**
     * Verifica se já existe usuário com mesmo Email
     *
     */
    public function testUserAlreadyExistsEmail()
    {
        $payload = [
            'full_name'  => $this->faker->name(),
            'email'      => 'exemple3@exemple.com',
            'cpf'        => '99999999996',
            'password'   => '123456',
            'profile_id' => 'df13adc5-1d01-4113-8119-a6e3f7836fc0'
        ];
        $this->postJson('/api/users', $payload);
        $this->postJson('/api/users', $payload)
            ->assertStatus(422)->assertJson(
                [
                    'email' => ['The email has already been taken.'],
                ]
            );
    }
}
