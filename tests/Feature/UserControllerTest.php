<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $defaultRole;

    protected function setUp(): void
    {
        parent::setUp();

        $this->defaultRole = Role::create([
            'name' => 'Admin',
            'key' => 'admin',
        ]);

        Passport::actingAs(User::factory()->create());
    }

    private function createUserWithRole(): User
    {
        return User::factory()->create(['role_id' => $this->defaultRole->id]);
    }

    public function test_index_returns_users(): void
    {
        User::factory()->count(5)->create();

        $response = $this->getJson(route('users.index'));

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => [
                '*' => ['id', 'name', 'last_name', 'role', 'status', 'email', 'createdAt']
            ]]);
    }

    public function test_store_creates_user(): void
    {
        $userData = [
            'name' => 'Juan',
            'last_name' => 'Pérez',
            'email' => 'juan.perez@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role_id' => $this->defaultRole->id,
        ];

        $response = $this->postJson(route('users.store'), $userData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => ['id', 'name', 'last_name', 'role', 'status', 'email', 'createdAt']
            ]);

        $this->assertDatabaseHas('users', ['email' => 'juan.perez@example.com']);
    }

    public function test_show_returns_user(): void
    {
        $user = $this->createUserWithRole();

        $response = $this->getJson(route('users.show', ['user' => $user->uuid]));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['id', 'name', 'last_name', 'role', 'status', 'email', 'metadata', 'createdAt']
            ]);
    }

    public function test_update_updates_user(): void
    {
        $user = $this->createUserWithRole();

        $updatedData = [
            'name' => 'Juan Actualizado',
            'last_name' => 'Pérez Actualizado',
            'email' => 'juan.updated@example.com',
            'role_id' => $this->defaultRole->id,
        ];

        $response = $this->putJson(route('users.update', ['user' => $user->uuid]), $updatedData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['id', 'name', 'last_name', 'role', 'status', 'email', 'createdAt']
            ]);

        $this->assertDatabaseHas('users', ['email' => 'juan.updated@example.com']);
    }

    public function test_update_status(): void
    {
        $user = $this->createUserWithRole();

        $response = $this->patchJson(route('users.update_status', ['user' => $user->uuid]));

        $response->assertStatus(200);

        $user->refresh();

        $this->assertNotNull($user->status);
    }

    public function test_destroy_deletes_user(): void
    {
        $user = $this->createUserWithRole();

        $response = $this->deleteJson(route('users.destroy', ['user' => $user->uuid]));

        $response->assertStatus(200);

        $this->assertDatabaseMissing('users', ['uuid' => $user->uuid]);
    }

    public function test_store_validation(): void
    {
        $response = $this->postJson(route('users.store'), [
            'last_name' => 'Pérez',
            'email' => 'juan.perez@example.com',
            'password' => 'password123',
            'role_id' => $this->defaultRole->id,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('name');
    }

    public function test_update_validation(): void
    {
        $user = $this->createUserWithRole();

        $response = $this->putJson(route('users.update', ['user' => $user->uuid]), [
            'last_name' => 'Pérez',
            'email' => 'juan.perez@example.com',
            'password' => 'password123',
            'role_id' => $this->defaultRole->id,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('name');
    }

    public function test_store_fails_with_invalid_email()
    {

        $response = $this->postJson(route('users.store'), [
            'name' => 'Juan',
            'last_name' => 'Pérez',
            'email' => 'no-es-un-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role_id' => $this->defaultRole->id,
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors('email');
    }

    public function test_store_fails_with_short_password()
    {
        $response = $this->postJson(route('users.store'), [
            'name' => 'Juan',
            'last_name' => 'Pérez',
            'email' => 'juan.short@example.com',
            'password' => '123',
            'password_confirmation' => '123',
            'role_id' => $this->defaultRole->id,
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors('password');
    }

    public function test_store_fails_with_invalid_role()
    {
        $response = $this->postJson(route('users.store'), [
            'name' => 'Juan',
            'last_name' => 'Pérez',
            'email' => 'juan.perez@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role_id' => 9999
        ]);
    
        $response->assertStatus(422)
                 ->assertJsonValidationErrors('role_id');
    }
    public function test_store_fails_without_password_confirmation()
    {
        $response = $this->postJson(route('users.store'), [
            'name' => 'Juan',
            'last_name' => 'Pérez',
            'email' => 'juan.perez@example.com',
            'password' => 'password123',
            'role_id' => $this->defaultRole->id,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('password');
    }
    
}
