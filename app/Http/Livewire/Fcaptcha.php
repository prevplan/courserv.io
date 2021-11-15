<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Fcaptcha extends Component
{
    public $solved = false;

    protected $listeners = ['captchaSolved', 'removeCaptcha'];

    public function removeCaptcha()
    {
        if (config('services.fcaptcha.secret') && config('services.fcaptcha.sitekey')) {
            return;
        }

        $this->emit('destroyCaptcha');
        $this->emitUp('captchaSolved');
        $this->solved = true;
    }

    public function captchaSolved($solution)
    {
        $response = Http::post('https://api.friendlycaptcha.com/api/v1/siteverify', [
            'solution' => $solution,
            'secret' => config('services.fcaptcha.secret'),
            'sitekey' => config('services.fcaptcha.sitekey'),
        ]);

        if (!$response->json('success')) {
            foreach ($response->json('errors') as $error) {
                $this->addError('validation', $error); //ddd($response->json('errors'));
            }
            $this->emit('resetCaptcha');
            return;
        }

        $this->emitUp('captchaSolved');
        $this->solved = true;
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.fcaptcha');
    }
}
