<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Throwable;

class ClientController extends Controller
{
    public function index()
    {
        $clientsReady = Schema::hasTable('clients');
        $clients = $clientsReady ? Client::orderBy('order')->orderBy('id')->paginate(20) : null;
        $pageTitle = 'Our Client';

        return view('admin.clients.index', compact('clients', 'clientsReady', 'pageTitle'));
    }

    public function create()
    {
        if (!Schema::hasTable('clients')) {
            return redirect()->route('admin.clients.index');
        }

        return view('admin.clients.form', ['pageTitle' => 'Tambah Klien']);
    }

    public function store(Request $request)
    {
        if (!Schema::hasTable('clients')) {
            return redirect()->route('admin.clients.index');
        }

        $data = $this->validateClient($request, true);
        $logo = $this->storeLogo($request);
        $data['logo'] = $logo;

        try {
            Client::create($data);
        } catch (Throwable $exception) {
            Storage::disk('public')->delete($logo);
            throw $exception;
        }

        return redirect()->route('admin.clients.index')->with('success', 'Klien berhasil ditambahkan.');
    }

    public function edit(Client $client)
    {
        return view('admin.clients.form', ['client' => $client, 'pageTitle' => 'Edit Klien']);
    }

    public function update(Request $request, Client $client)
    {
        $data = $this->validateClient($request, false);
        $oldLogo = $client->logo;
        $newLogo = $request->hasFile('logo') ? $this->storeLogo($request) : null;

        if ($newLogo !== null) {
            $data['logo'] = $newLogo;
        }

        try {
            $client->update($data);
        } catch (Throwable $exception) {
            if ($newLogo !== null) {
                Storage::disk('public')->delete($newLogo);
            }
            throw $exception;
        }

        // Keep the previous logo until its replacement has been saved successfully.
        if ($newLogo !== null && $oldLogo && !Storage::disk('public')->delete($oldLogo)) {
            return redirect()->route('admin.clients.index')->with('error', 'Perubahan klien tersimpan, tetapi file logo lama belum dapat dihapus. Minta pengelola server memeriksa penyimpanan.');
        }

        return redirect()->route('admin.clients.index')->with('success', 'Klien berhasil diperbarui.');
    }

    public function destroy(Client $client)
    {
        $logo = $client->logo;
        $client->delete();
        if (!Storage::disk('public')->delete($logo)) {
            return redirect()->route('admin.clients.index')->with('error', 'Data klien dihapus, tetapi file logo belum dapat dihapus. Minta pengelola server memeriksa penyimpanan.');
        }

        return redirect()->route('admin.clients.index')->with('success', 'Klien dan file logonya berhasil dihapus.');
    }

    private function validateClient(Request $request, bool $creating): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'logo' => [$creating ? 'required' : 'nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048', 'dimensions:max_width=4000,max_height=4000'],
            'order' => ['required', 'integer', 'min:0', 'max:65535'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        return [
            'name' => $validated['name'],
            'order' => $validated['order'],
            'is_active' => $request->boolean('is_active'),
        ];
    }

    private function storeLogo(Request $request): string
    {
        $path = $request->file('logo')->store('clients', 'public');

        if ($path === false) {
            throw ValidationException::withMessages(['logo' => 'Logo gagal disimpan. Periksa izin penyimpanan server dan coba lagi.']);
        }

        return $path;
    }
}
