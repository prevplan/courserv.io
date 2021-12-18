<?php

namespace App\Http\Livewire;

use App\Models\Permission;
use App\Models\Role as RoleModel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Role extends Component
{
    use AuthorizesRequests;

    public bool $showEditModal = false;
    public Collection $roles;
    public Collection $permissions;
    public RoleModel $editing;
    public $permIds = [];

    protected function rules(): array
    {
        return [
            'editing.name' => 'required|unique:roles,name,' . optional($this->editing)->id,
            'editing.display_name' => 'required|unique:roles,display_name,' . optional($this->editing)->id,
            'editing.description' => 'nullable',
        ];
    }

    public function mount()
    {
        if (! Auth::check()) {
            return $this->redirect(route('login'));
        }

        $this->editing = $this->makeBlankModel();
        $this->permissions = Permission::all();
    }

    public function create()
    {
        $this->authorize('create', RoleModel::class);

        if ($this->editing->getKey()) {
            $this->editing = $this->makeBlankModel();
            $this->permIds = [];
        }
        $this->showEditModal = true;
    }

    public function edit(RoleModel $role)
    {
        $this->authorize('update', $role);

        if ($this->editing->isNot($role)) {
            $this->editing = $role;

            $this->permIds = [];
            foreach ($this->editing->permissions->pluck('id') as $perm) {
                $this->permIds[] = (string)$perm;
            }
        }
        $this->showEditModal = true;
    }

    public function save()
    {
        $this->authorize('viewAny', RoleModel::class);

        $this->validate();

        $this->editing->save();
        $this->editing->syncPermissions($this->permIds);
        $this->showEditModal = false;
    }

    public function render()
    {
        $this->authorize('viewAny', RoleModel::class);

        $this->roles = RoleModel::all();

        return view('livewire.role')
            ->layout('layouts.app', [
                'metaTitle' => _i('Roles'),
                'active' => 'roles',
            ]);
    }

    protected function makeBlankModel(): RoleModel
    {
        return new RoleModel();
    }
}
