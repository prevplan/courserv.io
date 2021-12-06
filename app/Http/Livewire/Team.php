<?php

namespace App\Http\Livewire;

use App\Models\Team as TeamModel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class Team extends Component
{
    use AuthorizesRequests;

    public bool $showEditModal = false;
    public Collection $teams;
    public TeamModel $editing;

    protected function rules(): array
    {
        return [
            'editing.name' => 'required|unique:teams,name,' . optional($this->editing)->id,
            'editing.display_name' => 'required|unique:teams,display_name,' . optional($this->editing)->id,
            'editing.description' => 'nullable',
        ];
    }

    public function mount()
    {
        $this->editing = $this->makeBlankTeam();
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', TeamModel::class);

        if ($this->editing->getKey()) {
            $this->editing = $this->makeBlankTeam();
        }
        $this->showEditModal = true;
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(TeamModel $team)
    {
        $this->authorize('update', $team);

        if ($this->editing->isNot($team)) {
            $this->editing = $team;
        }
        $this->showEditModal = true;
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        $this->authorize('update', $this->editing);

        $this->validate();
        $this->editing->save();
        $this->showEditModal = false;
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function render()
    {
        $this->authorize('viewAny', TeamModel::class);

        if (Auth::user()->isAbleTo('team.view')) {
            $this->teams = TeamModel::all();
        } else {
            $id = Auth::user()->teams()->pluck('id');

            $this->teams = TeamModel::whereIn('id', $id)->get();
        }

        return view('livewire.team')
            ->layout('layouts.app', [
                'metaTitle' => _i('Teams'),
                'active' => 'teams',
            ]);
    }

    protected function makeBlankTeam(): TeamModel
    {
        return new TeamModel();
    }

    protected function prepareForValidation($attributes): array
    {
        $attributes['editing']['name'] = Str::slug($this->editing->name, '-');
        return $attributes;
    }
}
