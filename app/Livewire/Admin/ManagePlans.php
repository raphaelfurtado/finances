<?php

namespace App\Livewire\Admin;

use App\Models\Plan;
use Livewire\Component;
use Livewire\Attributes\Rule;

class ManagePlans extends Component
{
    public $plans;

    // Form properties
    public $planId;
    #[Rule('required|string|max:255')]
    public $name;
    #[Rule('required|string|max:255')]
    public $slug;
    #[Rule('required|string|max:255')]
    public $stripe_id;
    #[Rule('required|numeric|min:0')]
    public $price; // Input as float (e.g., 9.90), stored as int (990)
    #[Rule('required|in:month,year')]
    public $interval = 'month';
    public $active = true;
    public $recommended = false;

    // Benefits management
    public $benefits = []; // Array of description strings
    public $newBenefit = '';

    public $showModal = false;
    public $isEditing = false;

    public function mount()
    {
        $this->loadPlans();
    }

    public function loadPlans()
    {
        $this->plans = Plan::with('benefits')->orderBy('price')->get();
    }

    public function create()
    {
        $this->reset([
            'planId',
            'name',
            'slug',
            'stripe_id',
            'price',
            'interval',
            'active',
            'recommended',
            'benefits',
            'newBenefit'
        ]);
        $this->active = true;
        $this->interval = 'month';
        $this->benefits = [];
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function edit(Plan $plan)
    {
        $this->planId = $plan->id;
        $this->name = $plan->name;
        $this->slug = $plan->slug;
        $this->stripe_id = $plan->stripe_id;
        $this->price = $plan->price / 100; // Convert cents to main currency
        $this->interval = $plan->interval;
        $this->active = $plan->active;
        $this->recommended = $plan->recommended;
        $this->benefits = $plan->benefits->pluck('description')->toArray();

        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
            'stripe_id' => $this->stripe_id,
            'price' => (int) ($this->price * 100), // Convert to cents
            'interval' => $this->interval,
            'active' => $this->active,
            'recommended' => $this->recommended,
        ];

        if ($this->isEditing) {
            $plan = Plan::find($this->planId);
            $plan->update($data);
        } else {
            $plan = Plan::create($data);
        }

        // Sync benefits
        $plan->benefits()->delete();
        foreach ($this->benefits as $description) {
            if (!empty($description)) {
                $plan->benefits()->create(['description' => $description]);
            }
        }

        $this->showModal = false;
        $this->loadPlans();

        // Notify user (flash message logic can be added here or in view)
        session()->flash('message', 'Plano salvo com sucesso!');
    }

    public function delete(Plan $plan)
    {
        $plan->delete();
        $this->loadPlans();
    }

    public function addBenefit()
    {
        if (!empty($this->newBenefit)) {
            $this->benefits[] = $this->newBenefit;
            $this->newBenefit = '';
        }
    }

    public function removeBenefit($index)
    {
        unset($this->benefits[$index]);
        $this->benefits = array_values($this->benefits);
    }

    public function render()
    {
        return view('livewire.admin.manage-plans');
    }
}