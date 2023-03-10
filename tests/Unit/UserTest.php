<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_login_form()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_user_duplication()
    {
        $user1 = User::make([
            'name' => 'John Doe',
            'email' => 'johndoe@gmail.com'
        ]);

        $user2 = User::make([
            'name' => 'Nipun',
            'email' => 'nipun@gmail.com'
        ]);

        $this->assertTrue($user1->name != $user2->name);
    }

    public function test_delete_user()
    {
        User::factory()->count(1)->make();
        $user = User::first();

        if($user) {
            $user->delete();
        }

        $this->assertTrue(true);
    }

    public function test_register_new_users()
    {
        $response = $this->post('/register', [
            'name' => 'Nipun',
            'email' => 'nipun@gmail.com',
            'password' => 'nipun1234',
            'password_confirmation' => 'nipun1234'
        ]);

        $response->assertRedirect('/home');
    }

    public function test_database()
    {
        $this->assertDatabaseHas('users', [
            'name' => 'Nipun'
        ]);
    }

    public function test_database_missing()
    {
        $this->assertDatabaseMissing('users', [
            'email' => 'nipunsarker56@gmail.com'
        ]);
    }

    public function test_if_seeders_works()
    {
        // this command represents -> php artisan db:seed
        $this->seed();  // Seed all seeders in the seeders folder
    }
}
