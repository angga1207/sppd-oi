<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class AutoLogin extends Component
{
    #[Layout('components.layouts.guest')]

    #[Url(null, true, true)]
    public $token;

    public function mount($username)
    {
        Auth::logout(); // Ensure any existing user is logged out
        if ($this->token === '4Pp-SpPd-0I') {
            $user = User::where('username', $username)->first();
            if ($user) {
                Auth::login($user);
                return redirect()->route('admin.dashboard');
            } else {
                LivewireAlert::title('Error!')
                    ->text('Pengguna tidak ditemukan.')
                    ->error()
                    ->timer(0)
                    ->withConfirmButton('Kembali Ke Halaman Login')
                    ->onConfirm('goLogin')
                    ->show();
                return;
            }
        } else {
            LivewireAlert::title('Error!')
                ->text('Invalid token.')
                ->error()
                ->timer(0)
                ->withConfirmButton('Kembali Ke Halaman Login')
                ->onConfirm('goLogin')
                ->show();
            return;
        }
    }

    public function goLogin()
    {
        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.auth.auto-login');
    }
}
