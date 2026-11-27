<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $data = Karyawan::with(['user', 'atasan'])
            ->when($request->q, function ($query, $s) {
                $query->where(fn ($w) => $w->where('nama_lengkap', 'like', "%$s%")->orWhere('nik', 'like', "%$s%"));
            })
            ->orderBy('nama_lengkap')
            ->paginate(10)
            ->withQueryString();

        return view('admin.karyawan.index', compact('data'));
    }

    public function create()
    {
        return view('admin.karyawan.form', ['karyawan' => null, 'manajers' => $this->manajers()]);
    }

    public function store(Request $request)
    {
        $request->merge(['email' => is_string($request->input('email')) ? strtolower(trim($request->input('email'))) : $request->input('email')]);
        $d = $request->validate($this->rules(), [
            'email.email' => 'Masukkan alamat email yang valid.',
            'email.regex' => 'Email wajib berakhiran @gmail.com.',
            'email.unique' => 'Email sudah digunakan oleh akun lain.',
        ]);

        DB::transaction(function () use ($d) {
            $user = User::create([
                'name' => $d['nama_lengkap'],
                'email' => $d['email'],
                'password' => $d['password'],
                'role' => $d['role'],
            ]);

            Karyawan::create([
                'user_id' => $user->id,
                'nik' => $d['nik'],
                'nama_lengkap' => $d['nama_lengkap'],
                'departemen' => $d['departemen'],
                'jabatan' => $d['jabatan'],
                'atasan_id' => $d['atasan_id'] ?? null,
                'tanggal_bergabung' => $d['tanggal_bergabung'],
            ]);
        });

        return redirect()->route('admin.karyawan.index')->with('success', 'Data karyawan berhasil ditambahkan.');
    }

    public function edit(Karyawan $karyawan)
    {
        return view('admin.karyawan.form', ['karyawan' => $karyawan->load('user'), 'manajers' => $this->manajers()]);
    }

    public function update(Request $request, Karyawan $karyawan)
    {
        $request->merge(['email' => is_string($request->input('email')) ? strtolower(trim($request->input('email'))) : $request->input('email')]);
        $d = $request->validate($this->rules($karyawan), [
            'email.email' => 'Masukkan alamat email yang valid.',
            'email.regex' => 'Email wajib berakhiran @gmail.com.',
            'email.unique' => 'Email sudah digunakan oleh akun lain.',
        ]);

        DB::transaction(function () use ($d, $karyawan) {
            $userData = ['name' => $d['nama_lengkap'], 'email' => $d['email'], 'role' => $d['role']];
            if (! empty($d['password'])) {
                $userData['password'] = $d['password'];
            }
            $karyawan->user->update($userData);

            $karyawan->update([
                'nik' => $d['nik'],
                'nama_lengkap' => $d['nama_lengkap'],
                'departemen' => $d['departemen'],
                'jabatan' => $d['jabatan'],
                'atasan_id' => $d['atasan_id'] ?? null,
                'tanggal_bergabung' => $d['tanggal_bergabung'],
            ]);
        });

        return redirect()->route('admin.karyawan.index')->with('success', 'Data karyawan berhasil diperbarui.');
    }

    public function destroy(Karyawan $karyawan)
    {
        $karyawan->user->delete(); // karyawan, saldo, dan pengajuan ikut terhapus (cascade)

        return redirect()->route('admin.karyawan.index')->with('success', 'Data karyawan berhasil dihapus.');
    }

    private function manajers()
    {
        return User::where('role', 'manajer')->orderBy('name')->get();
    }

    private function rules(?Karyawan $k = null): array
    {
        return [
            'nama_lengkap' => 'required|string|max:255',
            'email' => ['required', 'email:rfc', 'regex:/@gmail\.com\z/i', 'max:255', Rule::unique('users', 'email')->ignore($k?->user_id)],
            'password' => [$k ? 'nullable' : 'required', 'string', 'min:6'],
            'role' => ['required', Rule::in(['manajer', 'karyawan'])],
            'nik' => ['required', 'string', 'max:50', Rule::unique('karyawan', 'nik')->ignore($k?->id)],
            'departemen' => 'required|string|max:100',
            'jabatan' => 'required|string|max:100',
            'atasan_id' => ['nullable', Rule::exists('users', 'id')->where('role', 'manajer')],
            'tanggal_bergabung' => 'required|date',
        ];
    }
}
