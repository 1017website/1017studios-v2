<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminClientsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['app.key' => 'base64:'.base64_encode(str_repeat('a', 32))]);
        Storage::fake('public');
    }

    private function signInCmsUser(): void
    {
        $this->actingAs(User::factory()->create());
    }

    private function createClient(): Client
    {
        $logo = UploadedFile::fake()->image('original.png', 300, 150)->store('clients', 'public');

        return Client::create(['name' => 'Studio Alpha', 'logo' => $logo, 'order' => 1, 'is_active' => true]);
    }

    public function test_guests_cannot_manage_clients(): void
    {
        $client = $this->createClient();

        $this->get(route('admin.clients.index'))->assertRedirect(route('admin.login'));
        $this->get(route('admin.clients.create'))->assertRedirect(route('admin.login'));
        $this->get(route('admin.clients.edit', $client))->assertRedirect(route('admin.login'));
        $this->post(route('admin.clients.store'), [])->assertRedirect(route('admin.login'));
        $this->put(route('admin.clients.update', $client), [])->assertRedirect(route('admin.login'));
        $this->delete(route('admin.clients.destroy', $client))->assertRedirect(route('admin.login'));
        $this->assertDatabaseHas('clients', ['id' => $client->id]);
        Storage::disk('public')->assertExists($client->logo);
    }

    public function test_cms_has_a_dedicated_menu_and_upload_form(): void
    {
        $this->signInCmsUser();
        $this->get(route('admin.clients.index'))->assertOk()->assertSee('Our Client')->assertSee('Tambah Klien');
        $this->get(route('admin.clients.create'))->assertOk()
            ->assertSee('enctype="multipart/form-data"', false)
            ->assertSee('name="logo"', false)
            ->assertSee('name="_token"', false);
    }

    public function test_cms_explains_how_to_activate_a_missing_clients_table(): void
    {
        $this->signInCmsUser();
        Schema::drop('clients');

        $this->get(route('admin.clients.index'))->assertOk()->assertSee('Buka System Maintenance');
        $this->get(route('admin.clients.create'))->assertRedirect(route('admin.clients.index'));
        $this->post(route('admin.clients.store'), [])->assertRedirect(route('admin.clients.index'));
    }

    public function test_cms_creates_a_client_with_an_uploaded_logo(): void
    {
        $this->signInCmsUser();

        $this->post(route('admin.clients.store'), [
            'name' => 'Studio Alpha', 'order' => 3, 'is_active' => 1,
            'logo' => UploadedFile::fake()->image('alpha.png', 300, 150),
        ])->assertRedirect(route('admin.clients.index'))->assertSessionHas('success');

        $client = Client::firstOrFail();
        $this->assertSame('Studio Alpha', $client->name);
        $this->assertTrue($client->is_active);
        $this->assertSame(3, $client->order);
        Storage::disk('public')->assertExists($client->logo);
    }

    public function test_unchecked_active_is_saved_as_draft(): void
    {
        $this->signInCmsUser();

        $this->post(route('admin.clients.store'), [
            'name' => 'Draft Client', 'order' => 0, 'is_active' => 0,
            'logo' => UploadedFile::fake()->image('draft.png'),
        ])->assertSessionHasNoErrors();

        $this->assertFalse(Client::firstOrFail()->is_active);
    }

    public function test_create_requires_a_name_logo_and_valid_order(): void
    {
        $this->signInCmsUser();

        $this->post(route('admin.clients.store'), ['name' => ' ', 'order' => 65536])
            ->assertSessionHasErrors(['name', 'logo', 'order']);
        $this->assertDatabaseCount('clients', 0);
    }

    public function test_svg_and_nonimage_uploads_are_rejected(): void
    {
        $this->signInCmsUser();
        $this->post(route('admin.clients.store'), [
            'name' => 'Invalid', 'order' => 0,
            'logo' => UploadedFile::fake()->createWithContent('logo.svg', '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>'),
        ])->assertSessionHasErrors('logo');
        $this->post(route('admin.clients.store'), [
            'name' => 'Invalid', 'order' => 0,
            'logo' => UploadedFile::fake()->create('logo.php', 10, 'application/x-php'),
        ])->assertSessionHasErrors('logo');
        $this->assertDatabaseCount('clients', 0);
    }

    public function test_oversized_logos_are_rejected(): void
    {
        $this->signInCmsUser();
        $this->post(route('admin.clients.store'), [
            'name' => 'Too Large', 'order' => 0,
            'logo' => UploadedFile::fake()->image('large.png')->size(2049),
        ])->assertSessionHasErrors('logo');
        $this->assertDatabaseCount('clients', 0);
    }

    public function test_edit_without_new_upload_keeps_logo_and_can_hide_client(): void
    {
        $this->signInCmsUser();
        $client = $this->createClient();
        $oldLogo = $client->logo;

        $this->put(route('admin.clients.update', $client), ['name' => 'Updated', 'order' => 5, 'is_active' => 0])
            ->assertRedirect(route('admin.clients.index'))->assertSessionHas('success');

        $client->refresh();
        $this->assertSame($oldLogo, $client->logo);
        $this->assertSame('Updated', $client->name);
        $this->assertFalse($client->is_active);
        Storage::disk('public')->assertExists($oldLogo);
    }

    public function test_replacing_a_logo_removes_the_previous_file(): void
    {
        $this->signInCmsUser();
        $client = $this->createClient();
        $oldLogo = $client->logo;

        $this->put(route('admin.clients.update', $client), [
            'name' => $client->name, 'order' => 1, 'is_active' => 1,
            'logo' => UploadedFile::fake()->image('new.png'),
        ])->assertSessionHas('success');

        $client->refresh();
        $this->assertNotSame($oldLogo, $client->logo);
        Storage::disk('public')->assertExists($client->logo);
        Storage::disk('public')->assertMissing($oldLogo);
    }

    public function test_invalid_replacement_preserves_existing_logo(): void
    {
        $this->signInCmsUser();
        $client = $this->createClient();
        $oldLogo = $client->logo;

        $this->put(route('admin.clients.update', $client), [
            'name' => $client->name, 'order' => 1,
            'logo' => UploadedFile::fake()->create('wrong.txt', 10, 'text/plain'),
        ])->assertSessionHasErrors('logo');

        $this->assertSame($oldLogo, $client->fresh()->logo);
        Storage::disk('public')->assertExists($oldLogo);
    }

    public function test_deleting_a_client_removes_only_its_logo(): void
    {
        $this->signInCmsUser();
        $client = $this->createClient();
        $other = $this->createClient();

        $this->delete(route('admin.clients.destroy', $client))->assertSessionHas('success');

        $this->assertDatabaseMissing('clients', ['id' => $client->id]);
        Storage::disk('public')->assertMissing($client->logo);
        Storage::disk('public')->assertExists($other->logo);
    }
}
