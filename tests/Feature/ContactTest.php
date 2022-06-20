<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase; 
use Illuminate\Foundation\Testing\WithFaker;
use Carbon\Carbon;
use Tests\TestCase;

use App\Models\Contact;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    private function get_contact_data()
    {
        return [
            'name' => 'Test Name',
            'email' => 'test@email.com',
            'birthday' => '10/23/1978',
            'company' => 'Test Company Name',
        ];
    }

    public function test_a_contact_can_be_added()
    {
        // Skip Laravel exception handling
        $this->withoutExceptionHandling();

        // Save test contact
        $this->post('/api/contact', $this->get_contact_data());

        // Retrieve test contact
        $contact = Contact::first();

        $this->assertEquals('Test Name', $contact->name);
        $this->assertEquals('test@email.com', $contact->email);
        $this->assertEquals('10/23/1978', Contact::first()->birthday->format('m/d/Y'));
        $this->assertEquals('Test Company Name', $contact->company);
    }

    public function test_fields_are_required()
    {
        // Collection with required fields
        collect(['name','email','birthday','company'])->map(function ($field) {
            // Save test contact with empty field
            $response = $this->post(
                '/api/contact',
                array_merge($this->get_contact_data(),[$field => ''])
            );
            $response->assertSessionHasErrors($field);
            $this->assertCount(0, Contact::all());
        });
    }

    public function test_email_must_be_valid_email()
    {
        // Save test contact with empty email
        $response = $this->post(
            '/api/contact',
            array_merge($this->get_contact_data(),['email' => 'INVALID EMAIL ADDRESS'])
        );
        $response->assertSessionHasErrors('email');
        $this->assertCount(0, Contact::all());
    }

    public function test_birthday_is_properly_stored()
    {
        // Save test contact with empty email
        $response = $this->post(
            '/api/contact',
            $this->get_contact_data()
        );
        $this->assertCount(1, Contact::all());
        $this->assertInstanceOf(Carbon::class, Contact::first()->birthday);
        $this->assertEquals('10-23-1978', Contact::first()->birthday->format('m-d-Y'));
    }

    public function test_a_name_is_required()
    {
        // Save test contact with empty name
        $response = $this->post(
            '/api/contact',
            array_merge($this->get_contact_data(),['name' => ''])
        );
        $response->assertSessionHasErrors('name');
        $this->assertCount(0, Contact::all());
    }

    public function test_an_email_is_required()
    {
        // Save test contact with empty email
        $response = $this->post(
            '/api/contact',
            array_merge($this->get_contact_data(),['email' => ''])
        );
        $response->assertSessionHasErrors('email');
        $this->assertCount(0, Contact::all());
    }
}
