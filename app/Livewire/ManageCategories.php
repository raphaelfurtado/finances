<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;

class ManageCategories extends Component
{
    public $categories;
    public $showModal = false;
    public $editingId = null;
    public $name = '';
    public $description = '';
    public $icon = 'circle';
    public $color = '#3B82F6';

    // Available icons
    public $availableIcons = [
        'utensils',
        'bus',
        'home',
        'gamepad-2',
        'heart-pulse',
        'shopping-cart',
        'car',
        'plane',
        'coffee',
        'book',
        'music',
        'film',
        'gift',
        'briefcase',
        'smartphone'
    ];

    // Available colors
    public $availableColors = [
        '#FB923C',
        '#60A5FA',
        '#C084FC',
        '#F472B6',
        '#34D399',
        '#FBBF24',
        '#EF4444',
        '#8B5CF6',
        '#EC4899',
        '#10B981'
    ];

    public function mount()
    {
        $this->loadCategories();
    }

    public function loadCategories()
    {
        $this->categories = Category::where('user_id', auth()->id())->get();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->name = '';
        $this->description = '';
        $this->icon = 'circle';
        $this->color = '#3B82F6';
        $this->resetErrorBag();
    }

    public function edit($id)
    {
        $category = Category::where('user_id', auth()->id())->findOrFail($id);
        $this->editingId = $category->id;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->icon = $category->icon;
        $this->color = $category->color;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'icon' => 'required|string',
            'color' => 'required|string',
        ], [
            'name.required' => 'O nome da categoria é obrigatório',
        ]);

        if ($this->editingId) {
            // Update existing category
            $category = Category::where('user_id', auth()->id())->findOrFail($this->editingId);
            $category->update([
                'name' => $this->name,
                'description' => $this->description,
                'icon' => $this->icon,
                'color' => $this->color,
            ]);
        } else {
            // Create new category
            Category::create([
                'user_id' => auth()->id(),
                'name' => $this->name,
                'description' => $this->description,
                'icon' => $this->icon,
                'color' => $this->color,
            ]);
        }

        $this->loadCategories();
        $this->closeModal();
        session()->flash('message', 'Categoria salva com sucesso!');
    }

    public function delete($id)
    {
        Category::where('user_id', auth()->id())->findOrFail($id)->delete();
        $this->loadCategories();
        session()->flash('message', 'Categoria excluída com sucesso!');
    }

    public function render()
    {
        return view('livewire.manage-categories');
    }
}
