<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Symfony\Component\Console\Output\BufferedOutput;
use Throwable;

class MaintenanceController extends Controller
{
    public function migrate(Request $request): RedirectResponse
    {
        return $this->runCommand($request, 'migrate', ['--force' => true], 'Migrate');
    }

    public function optimizeClear(Request $request): RedirectResponse
    {
        return $this->runCommand($request, 'optimize:clear', [], 'Optimize Clear');
    }

    private function runCommand(Request $request, string $command, array $options, string $label): RedirectResponse
    {
        $request->validateWithBag('maintenance', [
            'password' => ['required', 'string', 'current_password'],
            'confirmed' => ['required', 'accepted'],
        ], [
            'password.required' => 'Masukkan password akun CMS untuk melanjutkan.',
            'password.current_password' => 'Password akun CMS tidak sesuai.',
            'confirmed.required' => 'Centang konfirmasi sebelum menjalankan pemeliharaan.',
            'confirmed.accepted' => 'Centang konfirmasi sebelum menjalankan pemeliharaan.',
        ]);

        $lock = null;

        try {
            // A file lock survives optimize:clear, which flushes application cache locks.
            // Keep this file in place; closing the handle releases the lock.
            $lock = @fopen(storage_path('framework/cms-maintenance.lock'), 'c');

            if ($lock === false) {
                throw new RuntimeException('Unable to open the CMS maintenance lock.');
            }

            if (!flock($lock, LOCK_EX | LOCK_NB)) {
                return redirect()->route('admin.settings')->with('error', 'Pemeliharaan lain sedang berjalan. Tunggu hingga selesai sebelum mencoba lagi.');
            }

            // Only the two fixed commands above are allowed; never accept CLI input from a request.
            $output = new BufferedOutput;
            $exitCode = Artisan::call($command, $options + ['--no-interaction' => true, '--no-ansi' => true], $output);
            // Laravel optimize:clear can return 0 while one of its tasks reports FAIL.
            $failedTask = $command === 'optimize:clear' && preg_match('/\bFAIL\s*$/m', $output->fetch()) === 1;

            Log::info('CMS maintenance command finished.', [
                'user_id' => $request->user()->id,
                'command' => $command,
                'exit_code' => $exitCode,
                'failed_task' => $failedTask,
            ]);

            if ($exitCode !== 0 || $failedTask) {
                return redirect()->route('admin.settings')->with('error', $label.' gagal atau belum selesai sepenuhnya. Minta pengelola server memeriksa log sebelum mencoba lagi.');
            }

            return redirect()->route('admin.settings')->with('success', $label.' berhasil dijalankan.');
        } catch (Throwable $exception) {
            report($exception);

            return redirect()->route('admin.settings')->with('error', $label.' gagal dijalankan. Minta pengelola server memeriksa log sebelum mencoba lagi.');
        } finally {
            if (is_resource($lock)) {
                fclose($lock);
            }
        }
    }
}
