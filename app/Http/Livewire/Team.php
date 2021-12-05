<?php

namespace App\Http\Livewire;

use App\Models\Team as TeamModel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Team extends Component
{
    use AuthorizesRequests;

    public $teams;
    public bool $showCreateModal = false;
    public bool $showEditModal = false;
    public TeamModel $editing;

    protected function rules()
    {
        return [
            'editing.name' => 'required|unique:teams,name,' . optional($this->editing)->id,
            'editing.display_name' => 'required',
            'editing.description' => 'nullable',
        ];
    }

    public function mount()
    {
        $this->editing = $this->makeBlankTeam();
    }

    public function create()
    {
        $this->authorize('create', TeamModel::class);

        if ($this->editing->getKey()) {
            $this->editing = $this->makeBlankTeam();
        }
        $this->showEditModal = true;
    }

    public function edit(TeamModel $team)
    {
        $this->authorize('update', $team);

        if ($this->editing->isNot($team)) {
            $this->editing = $team;
        }
        $this->showEditModal = true;
    }

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

    private function makeBlankTeam()
    {
        return TeamModel::make([]);
    }
}
