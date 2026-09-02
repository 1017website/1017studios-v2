<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ViewErrorBag;
use Mockery;
use RuntimeException;
use Symfony\Component\Console\Output\BufferedOutput;
use Tests\TestCase;

class AdminMaintenanceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'app.key' => 'base64:'.base64_encode(str_repeat('a', 32)),
            'cache.default' => 'array',
            'session.driver' => 'array',
        ]);
    }

    private function signInCmsUser(): void
    {
        $this->actingAs((new User)->forceFill([
            'id' => 123,
            'name' => 'CMS Admin',
            'email' => 'cms@example.test',
            'password' => Hash::make('test-password'),
        ]));
    }

    private function confirmation(): array
    {
        return ['password' => 'test-password', 'confirmed' => '1'];
    }

    public function test_guests_cannot_run_either_command(): void
    {
        Artisan::shouldReceive('call')->never();

        foreach (['migrate', 'optimize-clear'] as $action) {
            $this->post(route('admin.maintenance.'.$action), $this->confirmation())
                ->assertRedirect(route('admin.login'));
        }
    }

    public function test_commands_cannot_be_triggered_by_get_requests(): void
    {
        $this->signInCmsUser();
        Artisan::shouldReceive('call')->never();

        foreach (['migrate', 'optimize-clear'] as $action) {
            $this->get(route('admin.maintenance.'.$action))->assertStatus(405);
        }
    }

    public function test_csrf_token_is_required_even_for_a_signed_in_cms_user(): void
    {
        $this->signInCmsUser();
        Artisan::shouldReceive('call')->never();
        // CSRF middleware normally skips verification in the testing environment.
        $this->app->instance('env', 'production');

        foreach (['migrate', 'optimize-clear'] as $action) {
            $this->post(route('admin.maintenance.'.$action), $this->confirmation())->assertStatus(419);
        }
    }

    public function test_confirmation_and_correct_password_are_required(): void
    {
        $this->signInCmsUser();
        Artisan::shouldReceive('call')->never();

        $this->from(route('admin.settings'))
            ->post(route('admin.maintenance.migrate'), ['password' => 'test-password'])
            ->assertSessionHasErrorsIn('maintenance', ['confirmed']);

        $this->from(route('admin.settings'))
            ->post(route('admin.maintenance.optimize-clear'), ['password' => 'incorrect', 'confirmed' => '1'])
            ->assertSessionHasErrorsIn('maintenance', ['password'])
            ->assertSessionMissing('_old_input.password');
    }

    public function test_migrate_uses_fixed_options_and_ignores_submitted_commands(): void
    {
        $this->signInCmsUser();
        Artisan::shouldReceive('call')->once()->with('migrate', [
            '--force' => true,
            '--no-interaction' => true,
            '--no-ansi' => true,
        ], Mockery::type(BufferedOutput::class))->andReturn(0);

        $this->post(route('admin.maintenance.migrate'), $this->confirmation() + [
            'command' => 'migrate:fresh', '--seed' => true,
        ])
            ->assertRedirect(route('admin.settings'))
            ->assertSessionHas('success', 'Migrate berhasil dijalankan.');
    }

    public function test_optimize_clear_runs_the_expected_command(): void
    {
        $this->signInCmsUser();
        Artisan::shouldReceive('call')->once()->with('optimize:clear', [
            '--no-interaction' => true,
            '--no-ansi' => true,
        ], Mockery::type(BufferedOutput::class))->andReturn(0);

        $this->post(route('admin.maintenance.optimize-clear'), $this->confirmation())
            ->assertRedirect(route('admin.settings'))
            ->assertSessionHas('success', 'Optimize Clear berhasil dijalankan.');
    }

    public function test_nonzero_exit_code_is_not_reported_as_success(): void
    {
        $this->signInCmsUser();
        Artisan::shouldReceive('call')->once()->andReturn(1);

        $this->post(route('admin.maintenance.migrate'), $this->confirmation())
            ->assertSessionHas('error')
            ->assertSessionMissing('success');
    }

    public function test_failed_optimize_subtask_is_not_reported_as_success(): void
    {
        $this->signInCmsUser();
        Artisan::shouldReceive('call')->once()->andReturnUsing(function ($command, $options, BufferedOutput $output) {
            $output->writeln('  cache ........................................ 1ms FAIL');

            return 0;
        });

        $this->post(route('admin.maintenance.optimize-clear'), $this->confirmation())
            ->assertSessionHas('error')
            ->assertSessionMissing('success');
    }

    public function test_command_errors_do_not_expose_internal_details(): void
    {
        $this->signInCmsUser();
        Artisan::shouldReceive('call')->once()->andThrow(new RuntimeException('Sensitive database connection details'));

        $this->post(route('admin.maintenance.migrate'), $this->confirmation())
            ->assertRedirect(route('admin.settings'))
            ->assertSessionHas('error', 'Migrate gagal dijalankan. Minta pengelola server memeriksa log sebelum mencoba lagi.')
            ->assertSessionMissing('success');
    }

    public function test_an_existing_maintenance_lock_prevents_another_command(): void
    {
        $this->signInCmsUser();
        Artisan::shouldReceive('call')->never();
        $lock = fopen(storage_path('framework/cms-maintenance.lock'), 'c');
        $this->assertNotFalse($lock);
        $this->assertTrue(flock($lock, LOCK_EX | LOCK_NB));

        try {
            $this->post(route('admin.maintenance.migrate'), $this->confirmation())
                ->assertSessionHas('error', 'Pemeliharaan lain sedang berjalan. Tunggu hingga selesai sebelum mencoba lagi.')
                ->assertSessionMissing('success');
        } finally {
            fclose($lock);
        }
    }

    public function test_repeated_requests_are_rate_limited(): void
    {
        $this->signInCmsUser();
        Artisan::shouldReceive('call')->never();

        for ($i = 0; $i < 3; $i++) {
            $this->post(route('admin.maintenance.migrate'), [])->assertStatus(302);
        }

        $this->post(route('admin.maintenance.migrate'), [])->assertStatus(429);
    }

    public function test_settings_maintenance_forms_have_csrf_and_separate_actions(): void
    {
        $this->signInCmsUser();

        $this->view('admin.partials.maintenance', ['errors' => new ViewErrorBag])
            ->assertSee('System Maintenance')
            ->assertSee('action="'.route('admin.maintenance.migrate').'"', false)
            ->assertSee('action="'.route('admin.maintenance.optimize-clear').'"', false)
            ->assertSee('name="_token"', false)
            ->assertSee('name="password"', false)
            ->assertSee('name="confirmed"', false);
    }
}
