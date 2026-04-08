<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Traits\OwnerAccess;
use App\Models\Activity as ActivityModel;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Activity extends Component
{
    use OwnerAccess;

    public function mount(string $owner = ''): void
    {
        $user = Auth::user();
        $username = strtolower($user->username ?? '');

        // If owner parameter passed, this is for owner panel - reject
        if (!empty($owner)) {
            abort(403, 'Invalid access. Use owner panel instead.');
        }

        // Block owner and worker users from admin panel
        if (in_array($username, ['owner', 'worker'], true)) {
            abort(403, 'Access denied. Use your designated panel.');
        }

        // Only admin can access here
        if ($username !== 'admin') {
            abort(403, 'Unauthorized access.');
        }
    }
    use WithPagination;

    public $filterAction = '';
    public $filterEntity = '';
    public $filterDate = '';
    public $panelFilter = 'admin'; // Default for admin panel

    #[Computed]
    public function activities()
    {
        $query = ActivityModel::with('user')->latest();

        $this->applyPanelFilter($query);

        if ($this->filterAction) {
            $query->where('action', $this->filterAction);
        }

        if ($this->filterEntity) {
            $query->where('entity_type', $this->filterEntity);
        }

        if ($this->filterDate) {
            $query->whereDate('created_at', $this->filterDate);
        }

        return $query->paginate(15);
    }

    private function applyPanelFilter($query)
    {
        if ($this->panelFilter === 'worker') {
            $query->whereHas('user', function($q) {
                $q->where('username', 'worker');
            });
        } elseif ($this->panelFilter === 'admin') {
            $query->whereHas('user', function($q) {
                $q->whereNotIn('username', ['owner', 'worker']);
            });
        }
        // for 'all' (owner), no additional filter
    }

    #[Computed]
    public function totalActivities()
    {
        $query = ActivityModel::query();
        $this->applyPanelFilter($query);
        return $query->count();
    }

    #[Computed]
    public function todayActivities()
    {
        $query = ActivityModel::whereDate('created_at', today());
        $this->applyPanelFilter($query);
        return $query->count();
    }

    #[Computed]
    public function weekActivities()
    {
        $query = ActivityModel::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        $this->applyPanelFilter($query);
        return $query->count();
    }

    #[Computed]
    public function produkActivities()
    {
        $query = ActivityModel::where('entity_type', 'Produk');
        $this->applyPanelFilter($query);
        return $query->count();
    }

    public function resetFilters()
    {
        $this->filterAction = '';
        $this->filterEntity = '';
        $this->filterDate = '';
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.admin.activity');
    }
}