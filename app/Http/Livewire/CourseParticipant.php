<?php

namespace App\Http\Livewire;

use App\Models\Course;
use App\Models\Participant;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Vinkla\Hashids\Facades\Hashids;

class CourseParticipant extends Component
{
    use AuthorizesRequests;

    public bool $can_update = false;
    public bool $can_view = false;
    public string $course;
    public array $select;
    public Participant $editing;

    protected function rules(): array
    {
        return [
//            'editing.name' => 'required',
//            'editing.email' => 'required|email:rfc|unique:users,email,'.$this->editing->id,
            'editing.active' => 'bool|nullable',
        ];
    }

    public function mount()
    {
        if (! Auth::check()) {
            return $this->redirect(route('login'));
        }

        $this->select = [];
        $this->editing = $this->makeBlankParticipant();
    }

    public function participate(Participant $participant)
    {
        $this->auth('update', $participant);

        $this->editing = $participant;

        $this->editing->participated ? $this->editing->participated = 0 : $this->editing->participated = 1;

        $this->validate();
        $this->editing->save();
    }

    public function pay(Participant $participant)
    {
        $this->auth('update', $participant);

        $this->editing = $participant;

        $this->editing->payed ? $this->editing->payed = 0 : $this->editing->payed = 1;

        $this->validate();
        $this->editing->save();
    }

    public function render()
    {
        $this->auth();

        $participants = Participant::whereCourseId(Hashids::decode($this->course))->get();

        return view('livewire.course-participant', [
            'participants' => $participants,
        ])
            ->layout('layouts.app', [
                'metaTitle' => _i('participants'),
                'active' => 'participants',
            ]);
        ;
    }

    protected function makeBlankParticipant(): Participant
    {
        return new Participant();
    }

    // viewing is authorized if the user is trainer at this course and end of the course less than a week ago
    // or is authorized by the role
    protected function auth($ability = 'viewAny', $participant = Participant::class)
    {
        $user = auth()->id();
        $course = Course::whereId(Hashids::decode($this->course))
            ->where('end', '>', Carbon::now()->subWeek())
            ->with('trainer')
                ->whereHas('trainer', function (Builder $query) use ($user) {
                    $query->where('user_id', '=', $user)
                        ->where('confirmed', '=', 1);
                })
            ->get();

        $this->can_view = auth()->user()->isAbleTo('participant.*');

        // is trainer
        if (count($course)) {
            $this->can_update = true;

            return;
        }

        // else check the role authorizations
        $this->can_update = auth()->user()->isAbleTo('participant.update');
        $this->authorize($ability, $participant);
    }
}
