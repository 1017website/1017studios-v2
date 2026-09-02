<?php

namespace Tests\Feature;

use App\Http\Controllers\HomeController;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class HomeClientsTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_uses_active_cms_clients_in_display_order(): void
    {
        Client::create(['name' => 'Studio Beta', 'logo' => 'clients/beta.png', 'order' => 2, 'is_active' => true]);
        Client::create(['name' => 'Studio Alpha', 'logo' => 'clients/alpha.png', 'order' => 1, 'is_active' => true]);
        Client::create(['name' => 'Hidden Client', 'logo' => 'clients/hidden.png', 'order' => 0, 'is_active' => false]);

        $data = app(HomeController::class)->index()->getData();

        $this->assertSame(['Studio Alpha', 'Studio Beta'], $data['clients']->pluck('name')->all());
    }

    public function test_homepage_supplies_an_empty_collection_without_clients(): void
    {
        $data = app(HomeController::class)->index()->getData();

        $this->assertTrue($data['clients']->isEmpty());
    }

    public function test_homepage_is_available_before_the_clients_migration(): void
    {
        Schema::drop('clients');

        $data = app(HomeController::class)->index()->getData();

        $this->assertTrue($data['clients']->isEmpty());
    }

    public function test_client_section_renders_two_rows_with_logos_and_escaped_names(): void
    {
        $this->view('home.partials.clients', [
            'clients' => collect([
                new Client(['name' => 'Studio Alpha', 'logo' => 'clients/alpha.png']),
                new Client(['name' => '<script>alert(1)</script>', 'logo' => 'clients/beta.png']),
            ]),
        ])
            ->assertSee('id="clients-title"', false)
            ->assertSee('Klien dan Mitra Kami')
            ->assertSee('Studio Alpha')
            ->assertSee('storage/clients/alpha.png')
            ->assertSee('clients-row--reverse')
            ->assertSee('clients-group--copy')
            ->assertSee('aria-hidden="true"', false)
            ->assertSee('data-client-toggle')
            ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false)
            ->assertDontSee('<script>alert(1)</script>', false);
    }

    public function test_empty_section_invites_collaboration_without_placeholder_clients(): void
    {
        $this->view('home.partials.clients', ['clients' => collect()])
            ->assertSee('id="clients"', false)
            ->assertSee('Setiap kolaborasi dimulai dari percakapan.')
            ->assertSee('Mulai Kolaborasi')
            ->assertSee('href="'.route('contact').'"', false)
            ->assertDontSee('id="clients-logos"', false);
    }

    public function test_section_stays_above_services(): void
    {
        config(['app.key' => 'base64:'.base64_encode(str_repeat('a', 32))]);

        $this->get('/')->assertOk()->assertSeeInOrder(['id="clients"', 'id="services"'], false);
    }
}
