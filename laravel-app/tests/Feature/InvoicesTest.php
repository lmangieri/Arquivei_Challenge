<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;

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

    private function checkIfValueExistsOnArrayOfObjects($arrOfObjects, $value) {
        foreach($arrOfObjects as $obj) {
            if($obj->access_key == $value) {
                return true;
            }
        }
        return false;
    }


    public function test_multiple_invoice() {
        \App\Invoice::create([
            'access_key' => '12345',
            'xml' => 'PHhtbD48ZGl2IGlkPTUyPlRleHQxMjM8L2Rpdj48L3htbD4'
        ]);

        $this->assertDatabaseHas('invoices',['access_key'=>'12345']);

        $response = $this->get('/api/invoices/getAccessKeys');

        $response
            ->assertStatus(200);
        $content = json_decode($response->getContent());

        $this->assertTrue($this->checkIfValueExistsOnArrayOfObjects($content,'12345'));

        $response = $this->call('GET', 'api/invoices/getInvoiceByAccessKey', ['access_key' => '12345','decode' => 'decode'])->decodeResponseJson();

        $this->assertTrue($response['access_key'] == '12345');
        $this->assertTrue($response['xml'] == '<xml><div id=52>Text123</div></xml>');

        $response = $this->call('GET', 'api/invoices/getInvoiceByAccessKey', ['access_key' => '12345','decode' => 'anotherValue'])->decodeResponseJson();

        $this->assertTrue($response['access_key'] == '12345');
        $this->assertTrue($response['xml'] == 'PHhtbD48ZGl2IGlkPTUyPlRleHQxMjM8L2Rpdj48L3htbD4');
    }

    public function test_get_invalid_invoide() {
        $invoice = DB::table('invoices')->where('access_key','invalid_key')->first();
        $this->assertTrue(!$invoice);
    }
}
