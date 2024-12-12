<?php

namespace Tests\Feature;

use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactControllerApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_all_contacts()
    {
        Contact::factory()->count(3)->create();

        $response = $this->getJson('/api/contacts');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    /** @test */
    public function it_can_create_a_contact()
    {
        $contactData = [
            'name' => 'John Doe',
            'contact' => '1234567890',
            'email' => 'john@example.com',
        ];

        $response = $this->postJson('/api/contacts', $contactData);

        $response->assertStatus(201)
            ->assertJsonFragment($contactData);

        $this->assertDatabaseHas('contacts', $contactData);
    }

    /** @test */
    public function it_can_show_a_contact()
    {
        $contact = Contact::factory()->create();

        $response = $this->getJson("/api/contacts/{$contact->id}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $contact->id,
                'name' => $contact->name,
            ]);
    }

    /** @test */
    public function it_can_update_a_contact()
    {
        $contact = Contact::factory()->create();

        $updatedData = [
            'name' => 'Updated Name',
            'contact' => '0987654321',
            'email' => 'updated@example.com',
        ];

        $response = $this->putJson("/api/contacts/{$contact->id}", $updatedData);

        $response->assertStatus(200)
            ->assertJsonFragment($updatedData);

        $this->assertDatabaseHas('contacts', $updatedData);
    }

    // /** @test */
    // public function it_can_delete_a_contact()
    // {
    //     $contact = Contact::factory()->create();

    //     $response = $this->deleteJson("/api/contacts/{$contact->id}");

    //     $response->assertStatus(200)
    //         ->assertJsonFragment(['message' => 'Contact deleted successfully']);

    //     $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
    // }
}
