<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Employee;
use App\Models\AccessCard;
use Illuminate\Support\Facades\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CardAccessApiTest extends TestCase
{
    public function testWrongMethodPost() {
        $response = $this->postJson('/api/some/endpoint',['cn','142594708f3a5a3ac2980914a0fc954f']);

        $response->assertStatus(405);    // method not allowed
    }

    public function testWrongMethodPut() {
        $response = $this->putJson('/api/some/endpoint',['cn','142594708f3a5a3ac2980914a0fc954f']);

        $response->assertStatus(405);    // method not allowed
    }

    public function testWrongMethodPatch() {
        $response = $this->patchJson('/api/some/endpoint',['cn','142594708f3a5a3ac2980914a0fc954f']);

        $response->assertStatus(405);    // method not allowed
    }

    public function testWrongMethodDelete() {
        $response = $this->deleteJson('/api/some/endpoint',['cn','142594708f3a5a3ac2980914a0fc954f']);

        $response->assertStatus(405);    // method not allowed
    }

    public function testCnTooShort()
    {
        $query_parameters = http_build_query([
            'cn' => '1234567890123456789012345678901'  // 31 chars
        ]);

        $response = $this->json('get', '/api/some/endpoint?'.$query_parameters);

        $response->assertStatus(400)
        ->assertJson([
            'error' => 'card number must be 32 characters'
        ]);
    }

    public function testCnTooLong()
    {
        $query_parameters = http_build_query([
            'cn' => '123456789012345678901234567890123'  // 33 chars
        ]);

        $response = $this->json('get', '/api/some/endpoint?'.$query_parameters);

        $response->assertStatus(400)
        ->assertJson([
            'error' => 'card number must be 32 characters'
        ]);
    }

    public function testTooFewParameters()
    {
        $response = $this->json('get', '/api/some/endpoint');

        $response->assertStatus(400)
        ->assertJson([
            'error' => 'incorrect number of parameters'
        ]);
    }

    public function testTooManyParameters()
    {
        $query_parameters = http_build_query([
            'cn' => '12345678901234567890123456789012',  // 32 chars
            'cn2' => '12345678901234567890123456789012'   // 32 chars
        ]);

        $response = $this->json('get', '/api/some/endpoint?'.$query_parameters);

        $response->assertStatus(400)
            ->assertJson([
                'error' => 'incorrect number of parameters'
            ]);
    }

    public function testWrongParameterName()
    {
        $query_parameters = http_build_query([
            'card_number' => '12345678901234567890123456789012'  // 32 chars
        ]);

        $response = $this->json('get', '/api/some/endpoint?'.$query_parameters);

        $response->assertStatus(400)
            ->assertJson([
                'error' => 'invalid parameter name'
            ]);
    }

    public function testCardNumberNotFound()
    {
        // from api spec, if cn value = 'not_found', empty fields should be returned
        $query_parameters = http_build_query([
            'cn' => 'not_found'
        ]);

        $response = $this->json('get', '/api/some/endpoint?'.$query_parameters);

        $response
            ->assertStatus(200)
            ->assertJson([
                'full_name' => '',
                'department' => []
            ]);
    }

    public function testCardNumberWithoutMatch()
    {
        $found_unused_cn = false;

        // find a random 32 character string which doesn't match an rfid value in the access_cards table
        while(!$found_unused_cn) {
            $rfid = bin2hex(openssl_random_pseudo_bytes(16));   // random
            $matching_card = AccessCard::where('rfid', $rfid)->first();
            $found_unused_cn = ($matching_card==null);
        }

        $query_parameters = http_build_query([
            'cn' => $rfid  // 32 chars
        ]);

        $response = $this->json('get', '/api/some/endpoint?'.$query_parameters);

        $response
            ->assertStatus(200)
            ->assertJson([
                'full_name' => '',
                'department' => []
            ]);
    }

    public function testCardNumberWithMatch()
    {
        // get a random rfid
        $random_card = AccessCard::inRandomOrder()->first();

        // for the employee who owns the access card, get their full name and departments
        $employee = Employee::find($random_card->employee_id);
        $full_name = $employee->given_names.' '.$employee->family_name;
        $departments = [];
        $employee->departments()->each(
            function($dept) use (&$departments) {
                array_push($departments, $dept->name);
            }
        );

        $query_parameters = http_build_query([
            'cn' => $random_card->rfid
        ]);

        $response = $this->json('get', '/api/some/endpoint?'.$query_parameters);

        $response
            ->assertStatus(200)
            ->assertJson([
                'full_name' => $full_name,
                'department' => $departments
            ]);
    }

    public function testDatabaseContainsTestData()
    {
        $query_parameters = http_build_query([
            'cn' => '142594708f3a5a3ac2980914a0fc954f '
        ]);

        $response = $this->json('get', '/api/some/endpoint?'.$query_parameters);

        $response
            ->assertStatus(200)
            ->assertJson([
                'full_name' => 'Julius Caesar',
                'department' => ['Development', 'Director']
            ]);
    }
}
