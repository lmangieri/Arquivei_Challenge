<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class InvoicesTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_create_user()
    {
        \App\User::create([
            'name'=>'Admin User',
            'email'=>'admin@admin.com',
            'password'=>bcrypt(123456)
        ]);

        $this->assertDatabaseHas('users',['name'=>'Admin User']);
    }

    public function test_create_invoice() {
        \App\Invoice::create([
            'access_key' => '12345',
            'xml' => 'PHhtbD48ZGl2IGlkPTUyPlRleHQxMjM8L2Rpdj48L3htbD4'
        ]);

        $this->assertDatabaseHas('invoices',['access_key'=>'12345']);

        $response = $this->get('/api/invoices/getAccessKeys');

        $response
            ->assertStatus(200);
        $content = json_decode($response->getContent());

        echo '<pre>';
        print_r($content);
        echo '</pre>';

        $this->assertTrue(sizeOf($content) == 1);
        $this->assertTrue($content[0]->access_key == '12345');

    }
}
