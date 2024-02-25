<?php

namespace Tests\Feature;

use App\Models\Contact;
use Database\Seeders\ContactSeeder;
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
                    'first_name' => [
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

    public function testGetSuccess()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->get('/api/contacts/' . $contact->id, [
            'Authorization' => 'test'
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'first_name' => 'test',
                    'last_name' => 'test',
                    'email' => 'test@gmail.com',
                    'phone' => '081231915158',
                ]
            ]);
    }

    public function testGetNotFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->get('/api/contacts/' . ($contact->id + 1), [
            'Authorization' => 'test'
        ])->assertStatus(404)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'not found'
                    ]
                ]
            ]);
    }
    public function testGetOtherUserContact()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->get('/api/contacts/' . $contact->id, [
            'Authorization' => 'test2'
        ])->assertStatus(404)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'not found'
                    ]
                ]
            ]);
    }

    public function testUpdateSuccess()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->put('/api/contacts/' . $contact->id, [
            'first_name' => 'test3',
            'last_name' => 'test3',
            'email' => 'test3@gmail.com',
            'phone' => '081231915158',
        ], [
            'Authorization' => 'test'
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'first_name' => 'test3',
                    'last_name' => 'test3',
                    'email' => 'test3@gmail.com',
                    'phone' => '081231915158',
                ]
            ]);
    }
    public function testUpdateValidationError()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->put('/api/contacts/' . $contact->id, [
            'first_name' => '',
            'last_name' => 'test3',
            'email' => 'test3@gmail.com',
            'phone' => '081231915158',
        ], [
            'Authorization' => 'test'
        ])->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'first_name' => [
                        'The first name field is required.',
                    ]
                ]
            ]);
    }

    public function testDeleteSuccess()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->delete('/api/contacts/' . $contact->id, [], [
            'Authorization' => 'test'
        ])->assertStatus(200)
            ->assertJson([
                'data' => true
            ]);
    }

    public function testDeleteNotFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->delete('/api/contacts/' . ($contact->id + 1), [], [
            'Authorization' => 'test'
        ])->assertStatus(404)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'not found'
                    ]
                ]
            ]);
    }
}
