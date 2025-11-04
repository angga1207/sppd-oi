<?php

namespace App\Livewire\Auth;

use App\Models\Instance;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

#[Layout('components.layouts.guest')]
#[Title('Login - SPPD')]
class Login extends Component
{
    public $username = '1000';
    // public $username = '197502251999031006';
    // public $username = '';
    public $password = '#OganIlirBangkit!!';
    // public $password = '';
    public $remember = false;

    protected $rules = [
        'username' => 'required|string',
        'password' => 'required|min:6',
    ];

    protected $messages = [
        'username.required' => 'NIP harus diisi',
        'username.string' => 'NIP harus berupa teks',
        'password.required' => 'Password harus diisi',
        'password.min' => 'Password minimal 6 karakter',
    ];

    public function login()
    {
        $userRole = User::where('username', $this->username)->first();
        // dd($userRole);
        // if (!$userRole) {
        //     LivewireAlert::title('Peringatan!')
        //         ->text('Pengguna tidak ditemukan. Silakan hubungi administrator.')
        //         ->warning()
        //         ->toast()
        //         ->position('top-end')
        //         ->show();
        //     return;
        // }

        if ($userRole && in_array($userRole->role_id, [2, 3])) {
            $this->validate([
                'username' => 'required|string',
                'password' => 'required|string',
            ], [], [
                'username' => 'NIP',
                'password' => 'Kata Sandi',
            ]);

            Auth::attempt([
                'username' => $this->username,
                'password' => $this->password,
            ], $this->remember);

            if (Auth::check()) {
                return redirect()->route('admin.dashboard');
            } else {
                LivewireAlert::title('Login Gagal')
                    ->text('NIP atau Kata Sandi yang Anda masukkan salah.')
                    ->error()
                    ->position('center')
                    ->show();

                $this->reset(['password']);
            }
        } else {
            $this->validate([
                'username' => 'required|string',
                'password' => 'required|string',
            ], [
                'captcha.required' => 'Captcha tidak boleh kosong',
                'captcha.captcha' => 'Captcha tidak cocok'
            ], [
                'username' => 'NIP',
                'password' => 'Kata Sandi',
            ]);

            $uri = 'https://semesta.oganilirkab.go.id/api/auth-user-evalakip';
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'User-Agent' => 'PostmanRuntime/7.44.1',
            ])->post($uri, [
                'username' => $this->username,
                'password' => $this->password,
            ]);

            if ($response->status() == 200) {
                $data = $response->json();
                $user = User::where('username', $data['atribut_user']['username'])->first();
                if (!$user) {
                    // Create local user if not exists
                    $instance = Instance::where('id_eoffice', $data['atribut_user']['id_skpd'])
                        ->first();

                    $role = 3;
                    if ($data['atribut_user']['jabatan'] == 'Bupati Ogan Ilir' || $data['atribut_user']['jabatan'] == 'Wakil Bupati Ogan Ilir') {
                        $role = 2;
                    } elseif ($data['atribut_user']['jabatan'] == 'Sekretaris Daerah') {
                        $role = 2;
                    } elseif (str_contains($data['atribut_user']['jabatan'], 'Kepala Dinas')) {
                        $role = 2;
                    }

                    $user = User::create([
                        'name' => $data['atribut_user']['fullname'],
                        'email' => $data['atribut_user']['email'] ?? $data['atribut_user']['username'] . '@oganilirkab.go.id',
                        'username' => $data['atribut_user']['username'],
                        // 'image' => '/storage/images/users/default.png',
                        'image' => $data['atribut_user']['foto_pegawai'] ?? '/storage/images/users/default.png',
                        'role_id' => $role, // Default role as Staff
                        'instance_id' => $instance->id ?? null,
                        'jabatan' => $data['atribut_user']['jabatan'] ?? null,
                        'no_hp' => $data['atribut_user']['no_hp'] ?? null,
                        'password' => bcrypt($this->password),
                    ]);
                }
                // Login the user
                Auth::loginUsingId($user->id, $this->remember);

                if (Auth::check()) {
                    return redirect()->route('admin.dashboard');
                } else {
                    LivewireAlert::title('Login Gagal')
                        ->text('NIP atau Kata Sandi yang Anda masukkan salah.')
                        ->error()
                        ->position('center')
                        ->show();

                    $this->reset(['password']);
                }
            } else {
                LivewireAlert::title('Login Gagal')
                    ->text('NIP atau Kata Sandi yang Anda masukkan salah.')
                    ->error()
                    ->position('center')
                    ->show();

                $this->reset(['password']);
            }
        }
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
