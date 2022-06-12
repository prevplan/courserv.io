<?php

namespace App\Http\Livewire;

use App\Events\CourseCancelled;
use App\Events\CourseCreated;
use App\Events\CourseRegisterRequired;
use App\Events\QsehCourseUpdated;
use App\Http\Livewire\DataTable\WithCachedRows;
use App\Http\Livewire\DataTable\WithPerPagePagination;
use App\Http\Livewire\DataTable\WithSorting;
use App\Models\Course as CourseModel;
use App\Models\CourseDay;
use App\Models\CourseType as CourseTypeModel;
use App\Models\Price as PriceModel;
use App\Models\Team as TeamModel;
use App\Models\TrainerDay;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

/**
 * @property mixed $rows
 * @property mixed $rowsQuery
 * @property mixed $teamsRows
 */
class Course extends Component
{
    use WithPerPagePagination;
    use WithSorting;
    use WithCachedRows;
    use AuthorizesRequests;

    protected $queryString = ['sorts'];

    public array $courseTypeCategories;

    public array $start_options;

    public array $end_options;

    public bool $showEditModal = false;

    public bool $showCancelModal = false;

    public bool $registerCourse = false;

    public bool $courseRegistered = false;

    public bool $showRegisterCourse = false;

    public CourseModel $editing;

    public Collection $courseTypes;

    public Collection $prices;

    public string $date_range;

    public string $time_start;

    public string $time_end;

    public array $courseDays = [];

    public array $priceIds = [];

    public array $filters = [
        'search' => '',
        'courseType' => '',
        'team' => '',
        'amount-min' => null,
        'amount-max' => null,
        'date-min' => null,
        'date-max' => null,
        'showCancelled' => null,
    ];

    public bool $showFilters = false;

    public array $options = [
        'minDate' => 'today',
        'dateFormat' => 'd.m.Y H:i',
        'weekNumbers' => true,
        'enableTime' => true,
        'time_24hr' => true,
        'locale' => 'de',
    ];

    public array $search_options = [
        'dateFormat' => 'd.m.Y',
        'weekNumbers' => true,
        'enableTime' => false,
        'locale' => 'de',
    ];

    public array $day_options = [
        'dateFormat' => 'd.m.Y',
        'weekNumbers' => true,
        'enableTime' => false,
        'time_24hr' => true,
        'locale' => 'de',
    ];

    public array $time_options = [
        'enableTime' => true,
        'time_24hr' => true,
        'noCalendar' => true,
        'dateFormat' => 'H:i',
    ];

    public array $start_time_options;

    public array $end_time_options;

    public array $trainer;

    protected function rules(): array
    {
        return [
            'editing.team_id' => 'required',
            'editing.course_type_id' => 'required',
            'editing.seminar_location' => 'required',
            'editing.start' => 'required',
            'editing.end' => 'required',
            'editing.street' => 'required',
            'editing.zipcode' => 'required',
            'editing.location' => 'required',
            'editing.seats' => 'required',
            'editing.registration_number' => 'sometimes',
            'editing.public_bookable' => 'sometimes',
            'date_range' => 'sometimes',
            'time_start' => 'sometimes',
            'time_end' => 'sometimes',
            'trainer' => 'sometimes',
        ];
    }

    public function setStartTimeOptions(array $start_time_options): void
    {
        $this->start_time_options = array_merge($this->start_time_options, $start_time_options);
    }

    public function setEndTimeOptions(array $end_time_options): void
    {
        $this->end_time_options = array_merge($this->end_time_options, $end_time_options);
    }

    private function setStartOptions(array $start_options): void
    {
        $this->start_options = array_merge($this->start_options, $start_options);
    }

    private function setEndOptions(array $end_options): void
    {
        $this->end_options = array_merge($this->end_options, $end_options);
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset('filters');
    }

    public function mount()
    {
        if (! Auth::check()) {
            return $this->redirect(route('login'));
        }

        $this->editing = $this->makeBlankCourse();
    }

    public function updatedDateRange()
    {
        $range = explode(' ', $this->date_range);

        $this->editing->start = Carbon::createFromFormat('d.m.y H:i', $range[0].' '.$this->time_start);
        $this->editing->end = Carbon::createFromFormat('d.m.y H:i', $range[array_key_last($range)].' '.$this->time_end);

        $this->checkCourseLength();
        $this->setDayOptions();
    }

    public function toggleShowFilters()
    {
        $this->useCachedRows();

        $this->showFilters = ! $this->showFilters;
    }

    public function updatedEditingCourseTypeId()
    {
        unset($this->editing->type); // force reload to prevent cache (prevents a bug if the course is already saved)

        if ($this->editing->course_type_id) {
            $this->editing->seats = $this->editing->type->seats;

            if ($this->editing->type->wsdl_id) {
                $this->showRegisterCourse = true;
            } else {
                $this->showRegisterCourse = false;
            }
        } else {
            $this->showRegisterCourse = false;
        }
    }

    public function updated($field, $newValue)
    {
        $split = explode('.', $field);

        // We want the courseDays
        if ($split[0] === 'courseDays') {

            // changed date?
            if ($split[2] === 'date') {

                // if date is empty, before the start or after the end
                if (
                    $newValue == '' ||
                    Carbon::createFromFormat('d.m.Y', $newValue)->lt($this->editing->start) ||
                    Carbon::createFromFormat('d.m.Y', $newValue)->gt($this->editing->end)
                ) {
                    unset($this->courseDays[$split[1]]); // delete it....
                } else {
                    // update order value
                    $this->courseDays[$split[1]]['order'] = Carbon::createFromFormat('d.m.Y', $newValue)->format('Y/m/d');
                }
            }

            // start time of the first day
            if ($split[1] == 0 && $split[2] === 'startTime') {
                // prevent that's not before the start time
                $firstDay = Carbon::createFromFormat('d.m.Y H:i', $this->courseDays[0]['date'].' '.$newValue);
                if ($firstDay->lt($this->editing->start)) {
                    $this->courseDays[0]['startTime'] = $this->editing->start->format('H:i');
                }
            }

            if ($split[2] === 'startTime') {
                // correct if the startTime is after or equal the endTime
                if (Carbon::createFromFormat('H:i', $this->courseDays[$split[1]]['startTime'])->gte(Carbon::createFromFormat('H:i', $this->courseDays[$split[1]]['endTime']))) {
                    $this->courseDays[$split[1]]['startTime'] = Carbon::createFromFormat('H:i', $this->courseDays[$split[1]]['endTime'])->subMinutes(45)->format('H:i');
                }
            }

            // end time of the last day
            if ($split[1] == array_key_last($this->courseDays) && $split[2] === 'endTime') {
                // prevent that's not after the end time
                $lastDay = Carbon::createFromFormat('d.m.Y H:i', $this->courseDays[array_key_last($this->courseDays)]['date'].' '.$newValue);
                if ($lastDay->gt($this->editing->end)) {
                    $this->courseDays[array_key_last($this->courseDays)]['endTime'] = $this->editing->end->format('H:i');
                }
            }

            if ($split[2] === 'endTime') {
                // correct if the endTime is before or equal the startTime
                if (Carbon::createFromFormat('H:i', $this->courseDays[$split[1]]['endTime'])->lte(Carbon::createFromFormat('H:i', $this->courseDays[$split[1]]['startTime']))) {
                    $this->courseDays[$split[1]]['endTime'] = Carbon::createFromFormat('H:i', $this->courseDays[$split[1]]['startTime'])->addMinutes(45)->format('H:i');
                }
            }

            // remove duplicate dates
            $tempArr = array_unique(array_column($this->courseDays, 'order'));
            $this->courseDays = array_intersect_key($this->courseDays, $tempArr);

            // sorts array by order
            $keys = array_column($this->courseDays, 'order');
            array_multisort($keys, SORT_ASC, $this->courseDays);

            // is there still a date on the first day?
            if (! isset($this->courseDays[0]) || $this->courseDays[0]['date'] != $this->editing->start->format('d.m.Y')) {

                // no? so, create it again, at the start of our array...
                array_unshift($this->courseDays, [
                    'date' => $this->editing->start->format('d.m.Y'),
                    'startTime' => $this->editing->start->format('H:i'),
                    'endTime' => $this->editing->end->format('H:i'),
                    'order' => $this->editing->start->format('Y/m/d'),
                ]);
            }

            // is there still a date on the last day?
            if ($this->courseDays[array_key_last($this->courseDays)]['date'] != $this->editing->end->format('d.m.Y')) {

                // no? so, create it again...
                $this->courseDays[] = [
                    'date' => $this->editing->end->format('d.m.Y'),
                    'startTime' => $this->editing->start->format('H:i'),
                    'endTime' => $this->editing->end->format('H:i'),
                    'order' => $this->editing->end->format('Y/m/d'),
                ];
            }

            foreach ($this->courseDays as $index => $foo) {
                if (! isset($this->trainer[$index])) {
                    $this->trainer[$index] = [0];
                }
            }
        }
    }

    public function updatedEditingTeamId()
    {
        unset($this->editing->team); // force reload to prevent cache (prevents a bug if the course is already saved)

        // reset all trainer
        $this->trainer = [];
        $this->trainer['general'] = [0];

        foreach ($this->courseDays as $index => $foo) {
            if (! isset($this->trainer[$index])) {
                $this->trainer[$index] = [0];
            }
        }
    }

    public function updatedTrainer()
    {
        foreach ($this->trainer as $item => $value) {
            $array = array_filter($value); // remove empty values
            $array = array_values($array); // order new
            $this->trainer[$item] = $array;
        }
    }

    public function addTrainer($x)
    {
        $this->trainer[$x][] = [];
    }

    public function updatedTimeStart()
    {
        if (isset($this->editing->start)) {
            $this->editing->start = Carbon::createFromFormat('d.m.Y H:i', $this->editing->start->format('d.m.Y').' '.$this->time_start);
            $this->setStartTimeOptions([
                'defaultHour' => $this->editing->start->format('H'),
                'defaultMinute' => $this->editing->start->format('i'),
            ]);
            $this->checkCourseLength();
        }
    }

    public function updatedTimeEnd()
    {
        if (isset($this->editing->end)) {
            $this->editing->end = Carbon::createFromFormat('d.m.Y H:i', $this->editing->end->format('d.m.Y').' '.$this->time_end);
            $this->setEndTimeOptions([
                'defaultHour' => $this->editing->end->format('H'),
                'defaultMinute' => $this->editing->end->format('i'),
            ]);
            $this->checkCourseLength();
        }
    }

    public function setDayOptions()
    {
        if (isset($this->editing->start)) {
            $this->day_options['minDate'] = $this->editing->start->format('d.m.Y');
        }

        if (isset($this->editing->end)) {
            $this->day_options['maxDate'] = $this->editing->end->format('d.m.Y');
        }
    }

    public function checkCourseLength()
    {
        if (! $this->editing->course_type_id) {
            $this->editing->course_type_id = CourseTypeModel::first()->id;
        }

        if ($this->editing->type->units_per_day) {
            $unit_length = (int)$this->editing->type->units_per_day * 45;
        } else {
            $unit_length = 9 * 45;
        }

        $breaks = $this->editing->type->breaks;
        $length = (int)$unit_length + (int)$breaks;

        // no end before start date, please
        if (isset($this->editing->start) && ! isset($this->editing->end) || $this->editing->end->subMinutes($length)->lt($this->editing->start)) {
            $this->editing->end = $this->editing->start->addMinutes($length);
            $this->time_end = $this->editing->end->format('H:i');
            $this->setEndTimeOptions([
                'defaultHour' => $this->editing->end->format('H'),
                'defaultMinute' => $this->editing->end->format('i'),
            ]);
        }

        // longer then one day?
        if (isset($this->editing->start) && ! $this->editing->start->isSameDay($this->editing->end)) {

            // if only first & last day or less is set, set / update it
            if (count($this->courseDays) <= 2) {
                $this->courseDays[0]['date'] = $this->editing->start->format('d.m.Y');
                $this->courseDays[0]['startTime'] = $this->editing->start->format('H:i');
                $this->courseDays[0]['endTime'] = $this->editing->end->format('H:i');
                $this->courseDays[0]['order'] = $this->editing->start->format('Y/m/d');

                $this->courseDays[1]['date'] = $this->editing->end->format('d.m.Y');
                $this->courseDays[1]['startTime'] = $this->editing->start->format('H:i');
                $this->courseDays[1]['endTime'] = $this->editing->end->format('H:i');
                $this->courseDays[1]['order'] = $this->editing->end->format('Y/m/d');

                $this->trainer[0] = [];
                $this->trainer[1] = [];
            }
        } else {
            $this->courseDays = [];
        }
    }

    public function create()
    {
        $this->authorize('create', CourseModel::class);

        if ($this->editing->getKey()) {
            $this->editing = $this->makeBlankCourse();
        }

        $this->showEditModal = true;
    }

    /**
     * @throws AuthorizationException
     */
    public function edit(CourseModel $course)
    {
        $this->authorize('update', $course);

        $this->registerCourse = false;
        $this->showRegisterCourse = false;

        if ($this->editing->isNot($course)) {
            $this->editing = $course;

            $this->priceIds = [];
            foreach ($this->editing->prices()->pluck('id') as $price) {
                $this->priceIds[] = (string) $price;
            }

            $this->date_range = $this->editing->start->format('d.m.y').' '._i('to').' '.$this->editing->end->format('d.m.y');

            $this->resetFlatpickr();
            $this->setDayOptions();
            $this->setStartOptions(['defaultDate' => [$this->editing->start, $this->editing->end]]);
            $this->time_start = $this->editing->start->format('H:i');
            $this->setStartTimeOptions([
                'defaultHour' => $this->editing->start->format('H'),
                'defaultMinute' => $this->editing->start->format('i'),
            ]);

            $this->time_end = $this->editing->end->format('H:i');
            $this->setEndTimeOptions([
                'defaultHour' => $this->editing->end->format('H'),
                'defaultMinute' => $this->editing->end->format('i'),
            ]);

            // if there are courseDays, bring them to the site.
            $this->courseDays = [];
            $this->trainer = [];
            $i = 0;

            foreach ($this->editing->days->toArray() as $courseDay) {
                $this->courseDays[] = [
                    'date' => Carbon::createFromFormat('Y-m-d', $courseDay['date'])->format('d.m.Y'),
                    'startTime' => Carbon::createFromFormat('H:i:s', $courseDay['startPlan'])->format('H:i'),
                    'endTime' => Carbon::createFromFormat('H:i:s', $courseDay['endPlan'])->format('H:i'),
                    'order' => Carbon::createFromFormat('Y-m-d', $courseDay['date'])->format('Y/m/d'),
                ];

                // add the trainer
                foreach ($this->editing->trainer->where('date', $courseDay['date']) as $trainer) {
                    if (! $trainer->user_id) {
                        if ($trainer->bookable) {
                            for ($x = 1; $x <= $trainer->count; $x++) {
                                $this->trainer[$i][] = 'trainer';
                            }
                        } else {
                            for ($x = 1; $x <= $trainer->count; $x++) {
                                $this->trainer[$i][] = 'later';
                            }
                        }
                    } else {
                        $this->trainer[$i][] = $trainer->user_id;
                    }
                }

                // and an empty one
                if (! isset($this->trainer[$i])) {
                    $this->trainer[$i][] = [];
                }
                $i++;
            }

            // and also the trainer for the whole course
            foreach ($this->editing->trainer->where('date', $this->editing->start->subDay()->format('Y-m-d')) as $trainer) {
                if (! $trainer->user_id) {
                    if ($trainer->bookable) {
                        for ($x = 1; $x <= $trainer->count; $x++) {
                            $this->trainer['general'][] = 'trainer';
                        }
                    } else {
                        for ($x = 1; $x <= $trainer->count; $x++) {
                            $this->trainer['general'][] = 'later';
                        }
                    }
                } else {
                    $this->trainer['general'][] = $trainer->user_id;
                }
            }

            if (! isset($this->trainer['general'])) {
                $this->trainer['general'][] = [];
            }
        }

        if (isset($this->editing->type->wsdl_id)) {
            $this->showRegisterCourse = true;
        }

        if ($this->editing->registration_number) {
            $this->courseRegistered = true;
        }

        $this->showEditModal = true;
    }

    public function cancel()
    {
        $this->authorize('save', $this->editing);

        if (config('qseh.codeNumber') && config('qseh.password')) {
            // it's a QSEH course and already registered
            if ($this->editing->registration_number && $this->editing->registration_number != 'queued' && $this->editing->registration_number != 'failed' && $this->editing->type->wsdl_id) {
                event(new CourseCancelled($this->editing));
            }
        }

        $this->editing->cancelled = now();
        $this->editing->save();

        $this->showEditModal = false;
        $this->showCancelModal = false;
        $this->resetPage();
    }

    /**
     * @throws AuthorizationException
     */
    public function save()
    {
        $this->authorize('save', $this->editing);

        $this->validate();

        $this->editing->start = Carbon::parse($this->editing->start->format('Y-m-d H:i:s'));

        $this->editing->end = Carbon::parse($this->editing->end->format('Y-m-d H:i:s'));

        $this->editing->save();

        $this->editing->prices()->sync($this->priceIds);

        // start to set the courseDays
        $courseDays = [];
        $date = [];

        if (! $this->courseDays) { // it's a one-day course
            $this->courseDays[] = [
                'date' => $this->editing->start->format('d.m.Y'),
                'startTime' => $this->editing->start->format('H:i'),
                'endTime' => $this->editing->end->format('H:i'),
            ];
        }

        foreach ($this->courseDays as $courseDay) {
            $courseDays[] = [
                'course_id' => $this->editing->id,
                'date' => Carbon::createFromFormat('d.m.Y', $courseDay['date']),
                'startPlan' => $courseDay['startTime'],
                'endPlan' => $courseDay['endTime'],
            ];
            $date[] = Carbon::createFromFormat('d.m.Y', $courseDay['date'])->format('Y-m-d');
        }

        // create / update the actual course days
        CourseDay::upsert(
            $courseDays,
            ['course_id', 'date'],
            ['startPlan', 'endPlan']
        );

        // and delete old course days
        CourseDay::where('course_id', $this->editing->id)
            ->whereNotIn('date', $date)
            ->delete();

        $trainerDays = [];

        // save the trainer for the days TODO is this the best way?
        foreach ($this->trainer as $item => $value) {
            if ($item != 'general') {
                $date = Carbon::createFromFormat('d.m.Y', $this->courseDays[$item]['date']);
            } else {
                $date = $this->editing->start->subDay()->format('Y-m-d'); // set day before start as date, if set for all days TODO better ideas?
            }

            $trainer = 0;
            $later = 0;

            foreach ($value as $item => $value) {
                if (is_numeric($value) && $value) { // it's a trainer / user_id
                    $trainerDays[] = [
                        'course_id' => $this->editing->id,
                        'user_id' => $value,
                        'date' => $date,
                        'bookable' => 0,
                        'count' => 0,
                        'confirmed' => 1,
                    ];
                } elseif (! is_numeric($value) && $value) { // it's another option
                    if ($value === 'trainer') {
                        $trainer = $trainer + 1;
                    }

                    if ($value === 'later') {
                        $later = $later + 1;
                    }
                }
            }

            if ($trainer > 0) { // count trainer choose
                $trainerDays[] = [
                    'course_id' => $this->editing->id,
                    'user_id' => 0,
                    'date' => $date,
                    'bookable' => 1,
                    'count' => $trainer,
                    'confirmed' => 0,
                ];
            }

            if ($later > 0) { // count 'select later'
                $trainerDays[] = [
                    'course_id' => $this->editing->id,
                    'user_id' => 0,
                    'date' => $date,
                    'bookable' => 0,
                    'count' => $later,
                    'confirmed' => 0,
                ];
            }
        }

        // create / update the trainer
        TrainerDay::upsert(
            $trainerDays,
            ['user_id', 'course_id', 'date', 'bookable'],
            ['count', 'confirmed']
        );

        // TODO better way to clean up?
        $actual_entries = TrainerDay::whereCourseId($this->editing->id)->orderByDesc('updated_at')->pluck('updated_at');

        // and delete old entries
        TrainerDay::where('course_id', $this->editing->id)
            ->whereNotIn('updated_at', [$actual_entries->first()])
            ->delete();

        $this->showEditModal = false;

        if (! $this->editing->internal_number) { // new course
            $this->editing->internal_number = 'queued';
            $this->editing->save();
            event(new CourseCreated($this->editing));
        }

        if (config('qseh.codeNumber') && config('qseh.password')) {
            if ($this->registerCourse) {
                $this->editing->registration_number = 'queued';
                $this->editing->save();
                event(new CourseRegisterRequired($this->editing));
            }

            // it's a QSEH course and already registered
            if (
                $this->editing->registration_number &&
                $this->editing->registration_number != 'queued' &&
                $this->editing->registration_number != 'failed' &&
                $this->editing->type->wsdl_id
            ) {
                event(new QsehCourseUpdated($this->editing));
            }
        }
    }

    /**
     * @return mixed
     */
    public function getRowsQueryProperty(): mixed
    {
        $query = CourseModel::query()
            ->when(
                ! Auth::user()->isAbleTo('team.*'), // can't see all teams
                fn ($query, $user_teams) => $query
                    ->whereIn('team_id', Auth::user()->teams()->pluck('id'))
            )
            ->when($this->filters['courseType'], fn ($query, $courseType) => $query->where('course_type_id', $courseType))
            ->when($this->filters['team'], fn ($query, $team) => $query->where('team_id', $team))
            ->when($this->filters['amount-min'], fn ($query, $amount) => $query->where('amount', '>=', $amount))
            ->when($this->filters['amount-max'], fn ($query, $amount) => $query->where('amount', '<=', $amount))
            ->when($this->filters['date-min'], fn ($query, $date) => $query->where('start', '>=', Carbon::parse($date)))
            ->when($this->filters['date-max'], fn ($query, $date) => $query->where('start', '<=', Carbon::parse($date)))
            ->when(! $this->filters['showCancelled'], fn ($query, $date) => $query->where('cancelled', '=', null))
            ->when($this->filters['showCancelled'], fn ($query, $date) => $query->where('cancelled', '<>', null))
            ->when(
                $this->filters['search'],
                fn ($query, $search) => $query
                ->where('seminar_location', 'like', '%'.$search.'%')
                ->orWhere('street', 'like', '%'.$search.'%')
                ->orWhere('seminar_location', 'like', '%'.$search.'%')
                ->orWhere('internal_number', 'like', '%'.$search.'%')
                ->orWhere('registration_number', 'like', '%'.$search.'%')
            )
            ->with('type')
            ->with('team')
            ->with('days')
            ->with('trainer');

        return $this->applySorting($query);
    }

    public function getRowsProperty(): mixed
    {
        return $this->cache(function () {
            return $this->applyPagination($this->rowsQuery);
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|array
     */
    public function getTeamsRowsProperty(): \Illuminate\Database\Eloquent\Collection|array
    {
        if (Auth::user()->isAbleTo('team.*')) {
            return TeamModel::all();
        }

        return Auth::user()->teams;
    }

    /**
     * @throws AuthorizationException
     */
    public function render()
    {
        $this->authorize('viewAny', CourseModel::class);

        // if no date is manually set, set it to today
        if ($this->filters['date-min'] === null) {
            $this->filters['date-min'] = today()->format('d.m.Y');
        }

        return view('livewire.course', [
            'courses' => $this->rows,
            'teams' => $this->teamsRows,
        ])
            ->layout('layouts.app', [
                'metaTitle' => _i('Courses'),
                'active' => 'courses',
            ]);
    }

    /**
     * @return CourseModel
     */
    protected function makeBlankCourse(): CourseModel
    {
        $this->courseTypes = CourseTypeModel::all()->groupBy('category')->toBase();
        $this->prices = PriceModel::whereActive(1)->get();

        $this->resetFlatpickr();
        $this->registerCourse = false;
        $this->courseRegistered = false;
        $this->showRegisterCourse = false;

        $this->trainer = [];
        $this->trainer['general'][] = [];

        $this->courseDays = [];
        $this->date_range = '';
        $this->priceIds = [];

        return new CourseModel();
    }

    protected function resetFlatpickr(): void
    {
        $this->start_options = [
            'minDate' => 'today',
            'dateFormat' => 'd.m.y',
            'weekNumbers' => true,
            'locale' => app()->getLocale(),
            'mode' => 'range',
        ];

        $this->time_start = '9:00';
        $this->time_end = '16:30';

        $this->start_time_options = $this->time_options;
        $this->setStartTimeOptions([
            'defaultHour' => 9,
            'defaultMinute' => 0,
        ]);
        $this->end_time_options = $this->time_options;
        $this->setEndTimeOptions([
            'defaultHour' => 16,
            'defaultMinute' => 30,
        ]);

        $this->end_options = [
            'minDate' => 'today',
            'defaultHour' => 16,
            'defaultMinute' => 30,
            'dateFormat' => 'd.m.Y H:i',
            'weekNumbers' => true,
            'enableTime' => true,
            'time_24hr' => true,
            'locale' => app()->getLocale(),
        ];
    }
}
