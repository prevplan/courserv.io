<?php

namespace App\Http\Livewire\Auth;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Login extends Component
{
    use WithRateLimiting;

    /** @var string */
    public $email = '';

    /** @var string */
    public $password = '';

    /** @var bool */
    public $remember = false;

    public $solved = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required'
    ];

    protected $listeners = ['captchaSolved'];

    public function captchaSolved()
    {
        $this->solved = true;
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updated($propertyName)
    {
        $this->resetErrorBag();
        $this->validateOnly($propertyName);
    }

    public function login()
    {

        $this->validate();

        try {
            $this->rateLimit(3, 120);
        } catch (TooManyRequestsException $exception) {
            $this->addError('captcha', _n('Too many login attempts. Please try again in a second.', 'Too many login attempts. Please try again in %s seconds.', $exception->secondsUntilAvailable, $exception->secondsUntilAvailable));

            return;
        }

        if (!$this->solved) {
            $this->emit('resetCaptcha');
            $this->addError('captcha', _i('Captcha not solved!'));

            return;
        }

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $this->addError('email', trans('auth.failed'));

            return;
        }

        session()->regenerate();

        return redirect()->intended(route('home'));
    }

    public function render()
    {
        return view('livewire.auth.login')
            ->layout('layouts.auth', ['metaTitle' => 'Login']);
    }
}
