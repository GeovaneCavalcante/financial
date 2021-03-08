<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserProfileTest extends TestCase
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
     * Buscar lista de perfis de usuários
     *
     */
    public function testShowListUserProfiles()
    {
        $this->getJson('/api/users-profiles')
            ->assertStatus(200);
    }

}
