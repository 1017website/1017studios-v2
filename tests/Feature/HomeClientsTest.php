<?php

namespace Tests\Feature;

use App\Http\Controllers\HomeController;
use App\Models\Portfolio;
use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeClientsTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_uses_unique_clients_from_active_content(): void
    {
        foreach ([' Studio Alpha ', 'studio alpha', null, '   ', 'Studio Beta'] as $index => $client) {
            Portfolio::create([
                'title' => 'Project '.$index,
                'client' => $client,
                'order' => $index,
                'is_active' => true,
                'is_featured' => false,
            ]);
        }

        Portfolio::create(['title' => 'Draft', 'client' => 'Hidden Client', 'is_active' => false]);

        foreach (['STUDIO BETA', 'Studio Gamma', null, ''] as $index => $company) {
            Testimonial::create([
                'name' => 'Reviewer '.$index,
                'company' => $company,
                'quote' => 'A great collaboration.',
                'order' => $index,
                'is_active' => true,
            ]);
        }

        Testimonial::create([
            'name' => 'Hidden reviewer',
            'company' => 'Hidden Company',
            'quote' => 'Unpublished.',
            'is_active' => false,
        ]);

        $data = app(HomeController::class)->index()->getData();

        $this->assertSame(['Studio Alpha', 'Studio Beta', 'Studio Gamma'], $data['clients']->all());
        $this->assertTrue($data['portfolios']->isEmpty());
    }

    public function test_homepage_supplies_an_empty_collection_without_clients(): void
    {
        $data = app(HomeController::class)->index()->getData();

        $this->assertTrue($data['clients']->isEmpty());
    }

    public function test_client_section_escapes_names_and_links_to_contact(): void
    {
        $this->view('home.partials.clients', [
            'clients' => collect(['Studio Alpha', '<script>alert(1)</script>']),
        ])
            ->assertSee('id="clients-title"', false)
            ->assertSee('Studio Alpha')
            ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false)
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertSee('href="'.route('contact').'"', false);
    }

    public function test_empty_section_invites_collaboration_without_placeholder_clients(): void
    {
        $this->view('home.partials.clients', ['clients' => collect()])
            ->assertSee('id="clients"', false)
            ->assertSee('Every great collaboration starts with a conversation.')
            ->assertSee('Work With Us')
            ->assertDontSee('class="clients-grid"', false);
    }
}
