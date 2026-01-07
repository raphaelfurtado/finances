<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;

class ManageCategories extends Component
{
    public $categories;
    public $showModal = false;
    public $editingId = null;
    public $formMode = 'create'; // create, create_sub, edit
    public $name = '';
    public $description = '';
    public $icon = 'circle';
    public $color = '#3B82F6';
    public $parent_id = null;
    public $isSubcategory = false;

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

    public function updatedParentId($value)
    {
        if ($value) {
            $parent = Category::find($value);
            if ($parent) {
                // Only inherit style if creating new sub
                if (!$this->editingId) {
                    $this->icon = $parent->icon;
                    $this->color = $parent->color;
                }
            }
        }
    }

    public function loadCategories()
    {
        // Load with children for display
        $this->categories = Category::where('user_id', auth()->id())
            ->whereNull('parent_id') // Root categories
            ->with('children')
            ->orderBy('name')
            ->get();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->formMode = 'create';
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function updatedIsSubcategory($value)
    {
        if (!$value) {
            $this->parent_id = null;
        } else {
            // Reset to defaults when switching to sub
            $this->icon = 'circle';
            $this->color = '#3B82F6';
        }
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->formMode = 'create';
        $this->name = '';
        $this->description = '';
        $this->icon = 'circle';
        $this->color = '#3B82F6';
        $this->parent_id = null;
        $this->isSubcategory = false;
        $this->resetErrorBag();
    }

    public function edit($id)
    {
        $category = Category::where('user_id', auth()->id())->findOrFail($id);
        $this->editingId = $category->id;
        $this->formMode = 'edit';
        $this->name = $category->name;
        $this->description = $category->description;
        $this->icon = $category->icon;
        $this->color = $category->color;
        $this->parent_id = $category->parent_id;
        $this->isSubcategory = !is_null($category->parent_id);
        $this->showModal = true;
    }

    public function addSubcategory($parentId)
    {
        $this->resetForm();
        $this->formMode = 'create_sub';
        $this->parent_id = $parentId;
        $this->isSubcategory = true;

        $parent = Category::find($parentId);
        if ($parent) {
            $this->icon = $parent->icon;
            $this->color = $parent->color;
        }

        $this->showModal = true;
    }

    public function save()
    {
        // Force parent_id null if not subcategory
        if (!$this->isSubcategory) {
            $this->parent_id = null;
        }

        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'icon' => 'required|string',
            'color' => 'required|string',
            'parent_id' => $this->isSubcategory ? 'required|exists:categories,id' : 'nullable',
        ], [
            'name.required' => 'O nome da categoria é obrigatório',
            'parent_id.required' => 'Selecione a categoria pai',
        ]);

        if ($this->editingId) {
            // Update existing category
            $category = Category::where('user_id', auth()->id())->findOrFail($this->editingId);

            // Prevent self-parenting
            if ($this->parent_id == $this->editingId) {
                $this->addError('parent_id', 'Uma categoria não pode ser subcategoria dela mesma.');
                return;
            }

            $category->update([
                'name' => $this->name,
                'description' => $this->description,
                'icon' => $this->icon,
                'color' => $this->color,
                'parent_id' => $this->parent_id,
            ]);
        } else {
            // Create new category
            Category::create([
                'user_id' => auth()->id(),
                'name' => $this->name,
                'description' => $this->description,
                'icon' => $this->icon,
                'color' => $this->color,
                'parent_id' => $this->parent_id,
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
        // Get potential parents (only root categories to avoid deep nesting issues for now)
        $potentialParents = Category::where('user_id', auth()->id())
            ->whereNull('parent_id')
            ->where('id', '!=', $this->editingId) // Exclude self
            ->orderBy('name')
            ->get();

        return view('livewire.manage-categories', [
            'parents' => $potentialParents
        ]);
    }
}
