<?php

namespace App\Http\Livewire;

use App\Models\Coordinates;
use App\Models\Course;
use App\Models\CourseType;
use Carbon\Carbon;
use Livewire\Component;
use Vinkla\Hashids\Facades\Hashids;

class BookingOverview extends Component
{
    public bool $showPriceModal = false;
    public string $courseType;
    public string $slug;
    public string $location;
    public string $location2;
    public string $lat;
    public string $lon;
    public Course $actual;

    public array $searchLocations = [];

    public function mount()
    {
        $courseType = CourseType::whereSlug($this->slug)->firstOrFail();
        $this->courseType = $courseType->name;

        if (isset($this->location2) && is_numeric($this->location) && is_numeric($this->location2)) {
            $this->lat = $this->location;
            $this->lon = $this->location2;
        }

        if (! isset($this->lat) || ! isset($this->lon)) { // no coordinates set, yet
            if (isset($this->location2)) {
                $this->location = $this->location . '/' . $this->location2;
            }

            $coordinates = Coordinates::whereLocation($this->location)->firstOrFail();

            $this->lat = $coordinates->lat;
            $this->lon = $coordinates->lon;
        }

        $this->actual = new Course();
    }

    public function showPrice(Course $actual)
    {
        $this->actual = $actual;
        $this->showPriceModal = true;
    }

    public function selectCourse($course, $price)
    {
        return redirect()->route('booking', ['course' => Hashids::encode($course), 'price' => Hashids::encode($price)]);
    }

    public function render()
    {
        $perimeter_locations = $this->perimeterSearch($this->lat, $this->lon, 50); // all locations in the perimeter

        // all locations in the perimeter with bookable courses
        $possible_locations = Course::where('public_bookable', 1)
            ->where('start', '>', Carbon::now())
            ->whereIn('location', $perimeter_locations->pluck('location'))
            ->get();

        // remove the locations without course from the perimeter result
        $i = 0;
        foreach ($perimeter_locations as $perimeter) {
            if (! in_array($perimeter['location'], $possible_locations->pluck('location')->toArray())) {
                unset($perimeter_locations[$i]);
            }
            $i++;
        }

        $this->searchLocations = array_filter($this->searchLocations);

        if (empty($this->searchLocations) && $perimeter_locations->isNotEmpty()) { // no course location selected

            $found = false;
            foreach ($perimeter_locations as $location) {
                if ($location->distance <= 15) { // select courses < 15 km
                    $this->searchLocations[$location->location] = $location->location;
                    $found = true;
                }
            }

            if (! $found) { // no course < 15 km
                foreach ($perimeter_locations as $location) {
                    if ($location->distance <= 25) { // select courses < 25 km
                        $this->searchLocations[$location->location] = $location->location;
                        $found = true;
                    }
                }
            }

            if (! $found) { // no course < 25 km
                $this->searchLocations[$perimeter_locations->first()->location] = $perimeter_locations->first()->location; // select the first result
            }
        }

        $courses = Course::where('public_bookable', 1)
            ->where('start', '>', Carbon::now())
            ->where('cancelled', '=', null)
            ->whereIn('location', $this->searchLocations)
            ->with('prices')
            ->with('type')
            ->withCount(['participants' => fn ($query) => $query->where('cancelled', 0)])
            ->get();

        return view('livewire.booking-overview', [
            'perimeter_locations' => $perimeter_locations,
            'courses' => $courses,
        ])
            ->layout('layouts.booking', [
                'metaTitle' => $this->courseType . ' ' . (is_numeric($this->location) ? _i('near you') : _i('in %s and surroundings', $this->location)),
                'index' => true,
            ]);
    }

    public function perimeterSearch($lat, $lon, $max = 25)
    {
        $max = $max * 1000; // distance in km

        return Coordinates::selectRaw("`location`, ST_Distance_Sphere(
                    Point($lon, $lat),
                    Point(lon, lat)
                ) * ? as distance", [.001])
            ->whereRaw("ST_Distance_Sphere(
                    Point($lon, $lat),
                    Point(lon, lat)
                 ) <  ? ", $max)
            ->orderBy('distance')
            ->get();
    }
}
