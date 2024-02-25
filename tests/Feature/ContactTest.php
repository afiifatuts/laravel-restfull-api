<?php

namespace Tests\Feature;

use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ContactTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testCreateSuccess()
    {
        //harus sudah login dan mendapatkan token
        $this->seed([UserSeeder::class]);


        $this->post('/api/contacts', [
            'first_name' => 'Afiifatuts',
            'last_name' => 'Tsaaniyah',
            'email' => 'afiifatuts04@gmail.com',
            'phone' => '081231915158',
        ], [
            'Authorization' => 'test'
        ])->assertStatus(201)
            ->assertJson([
                'data' => [
                    'first_name' => 'Afiifatuts',
                    'last_name' => 'Tsaaniyah',
                    'email' => 'afiifatuts04@gmail.com',
                    'phone' => '081231915158',
                ]
            ]);
    }
    public function testCreateFailed()
    {
        //harus sudah login dan mendapatkan token
        $this->seed([UserSeeder::class]);


        $this->post('/api/contacts', [
            'first_name' => '',
            'last_name' => 'Tsaaniyah',
            'email' => 'afiifatuts04',
            'phone' => '081231915158',
        ], [
            'Authorization' => 'test'
        ])->assertStatus(400)
            ->assertJson([
                'errors' =>  [
                    'first_name' =>[
                        'The first name field is required.',
                    ],
                    'email' =>  [
                    'The email field must be a valid email address.',
                    ],
                ],
            ]);
    }
    public function testCreateUnauthorized()
    {
        //harus sudah login dan mendapatkan token
        $this->seed([UserSeeder::class]);


        $this->post('/api/contacts', [
            'first_name' => 'Afiifatuts',
            'last_name' => 'Tsaaniyah',
            'email' => 'afiifatuts04@gmail.com',
            'phone' => '081231915158',
        ], [
            'Authorization' => 'salah'
        ])->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'unauthorized'
                    ]
                ]
            ]);
    }
}
