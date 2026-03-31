<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use App\Models\Activity as ActivityModel;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Activity extends Component
{
    use WithPagination;

    public $filterAction = '';
    public $filterEntity = '';
    public $filterDate = '';

    #[Computed]
    public function activities()
    {
        $query = ActivityModel::with('user')->latest();

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

    public function resetFilters()
    {
        $this->filterAction = '';
        $this->filterEntity = '';
        $this->filterDate = '';
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.activity');
    }
}