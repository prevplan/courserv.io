<?php

namespace App\Http\Livewire;

use App\Models\CourseType;
use App\Models\Location;
use Livewire\Component;

class LocationSearch extends Component
{
    public string $courseType;
    public string $term = '';
    public string $slug;
    public string $redirectUrl;
    public string $error = '';

    protected $listeners = ['located', 'error'];

    public function mount()
    {
        $courseTypes = CourseType::whereSlug($this->slug)->firstOrFail();
        $this->courseType = $courseTypes->name;
        $this->slug = $courseTypes->slug;
        $this->redirectUrl = $courseTypes->iframe_url;
    }

    public function render()
    {
        $locations = Location::search(trim($this->term))->groupBy('location', 'state')->limit(5)->get();

        return view('livewire.location-search', [
            'metaTitle' => $this->courseType . ' - ' . _i('our course dates at a glance'),
            'index' => true,
            'locations' => $locations,
        ])
            ->layout('layouts.booking');
    }

    public function located($lat, $lon)
    {
        return redirect()->to(route('booking.coordinates', [$this->slug, round($lat, 2), round($lon, 2)]));
    }

    public function error($error)
    {
    }
}
