<?php

namespace App\Livewire\Settings;

use App\Models\Category;
use App\Models\Format;
use App\Models\SupportType;
use Illuminate\Support\Str;
use Livewire\Component;

class OrderSettings extends Component
{
    public string $activeTab = 'support_types';

    // Support Types
    public bool $showSupportTypeModal = false;
    public ?int $editingSupportTypeId = null;
    public string $supportTypeName = '';
    public bool $supportTypeActive = true;

    // Formats
    public bool $showFormatModal = false;
    public ?int $editingFormatId = null;
    public string $formatName = '';
    public ?int $formatSupportTypeId = null;
    public bool $formatActive = true;

    // Categories
    public bool $showCategoryModal = false;
    public ?int $editingCategoryId = null;
    public string $categoryName = '';
    public bool $categoryActive = true;

    // Delete confirmation
    public bool $showDeleteModal = false;
    public string $deleteType = '';
    public ?int $deleteId = null;

    protected $queryString = [
        'activeTab' => ['except' => 'support_types'],
    ];

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    // ================== Support Types ==================

    public function openSupportTypeModal(?int $id = null): void
    {
        $this->resetSupportTypeForm();

        if ($id) {
            $supportType = SupportType::find($id);
            if ($supportType) {
                $this->editingSupportTypeId = $id;
                $this->supportTypeName = $supportType->name;
                $this->supportTypeActive = $supportType->is_active;
            }
        }

        $this->showSupportTypeModal = true;
    }

    public function saveSupportType(): void
    {
        $this->validate([
            'supportTypeName' => 'required|string|max:255',
        ], [
            'supportTypeName.required' => 'Le nom est obligatoire.',
        ]);

        $data = [
            'name' => $this->supportTypeName,
            'slug' => Str::slug($this->supportTypeName),
            'is_active' => $this->supportTypeActive,
        ];

        if ($this->editingSupportTypeId) {
            $supportType = SupportType::find($this->editingSupportTypeId);
            $supportType->update($data);
            session()->flash('success', 'Type de support modifié avec succès.');
        } else {
            $data['sort_order'] = SupportType::max('sort_order') + 1;
            SupportType::create($data);
            session()->flash('success', 'Type de support créé avec succès.');
        }

        $this->closeSupportTypeModal();
    }

    public function closeSupportTypeModal(): void
    {
        $this->showSupportTypeModal = false;
        $this->resetSupportTypeForm();
    }

    protected function resetSupportTypeForm(): void
    {
        $this->editingSupportTypeId = null;
        $this->supportTypeName = '';
        $this->supportTypeActive = true;
    }

    public function toggleSupportTypeActive(int $id): void
    {
        $supportType = SupportType::find($id);
        if ($supportType) {
            $supportType->update(['is_active' => !$supportType->is_active]);
        }
    }

    // ================== Formats ==================

    public function openFormatModal(?int $id = null): void
    {
        $this->resetFormatForm();

        if ($id) {
            $format = Format::find($id);
            if ($format) {
                $this->editingFormatId = $id;
                $this->formatName = $format->name;
                $this->formatSupportTypeId = $format->support_type_id;
                $this->formatActive = $format->is_active;
            }
        }

        $this->showFormatModal = true;
    }

    public function saveFormat(): void
    {
        $this->validate([
            'formatName' => 'required|string|max:255',
            'formatSupportTypeId' => 'required|exists:support_types,id',
        ], [
            'formatName.required' => 'Le nom est obligatoire.',
            'formatSupportTypeId.required' => 'Le type de support est obligatoire.',
        ]);

        $data = [
            'name' => $this->formatName,
            'slug' => Str::slug($this->formatName),
            'support_type_id' => $this->formatSupportTypeId,
            'is_active' => $this->formatActive,
        ];

        if ($this->editingFormatId) {
            $format = Format::find($this->editingFormatId);
            $format->update($data);
            session()->flash('success', 'Format modifié avec succès.');
        } else {
            $data['sort_order'] = Format::max('sort_order') + 1;
            Format::create($data);
            session()->flash('success', 'Format créé avec succès.');
        }

        $this->closeFormatModal();
    }

    public function closeFormatModal(): void
    {
        $this->showFormatModal = false;
        $this->resetFormatForm();
    }

    protected function resetFormatForm(): void
    {
        $this->editingFormatId = null;
        $this->formatName = '';
        $this->formatSupportTypeId = null;
        $this->formatActive = true;
    }

    public function toggleFormatActive(int $id): void
    {
        $format = Format::find($id);
        if ($format) {
            $format->update(['is_active' => !$format->is_active]);
        }
    }

    // ================== Categories ==================

    public function openCategoryModal(?int $id = null): void
    {
        $this->resetCategoryForm();

        if ($id) {
            $category = Category::find($id);
            if ($category) {
                $this->editingCategoryId = $id;
                $this->categoryName = $category->name;
                $this->categoryActive = $category->is_active;
            }
        }

        $this->showCategoryModal = true;
    }

    public function saveCategory(): void
    {
        $this->validate([
            'categoryName' => 'required|string|max:255',
        ], [
            'categoryName.required' => 'Le nom est obligatoire.',
        ]);

        $data = [
            'name' => $this->categoryName,
            'slug' => Str::slug($this->categoryName),
            'is_active' => $this->categoryActive,
        ];

        if ($this->editingCategoryId) {
            $category = Category::find($this->editingCategoryId);
            $category->update($data);
            session()->flash('success', 'Catégorie modifiée avec succès.');
        } else {
            $data['sort_order'] = Category::max('sort_order') + 1;
            Category::create($data);
            session()->flash('success', 'Catégorie créée avec succès.');
        }

        $this->closeCategoryModal();
    }

    public function closeCategoryModal(): void
    {
        $this->showCategoryModal = false;
        $this->resetCategoryForm();
    }

    protected function resetCategoryForm(): void
    {
        $this->editingCategoryId = null;
        $this->categoryName = '';
        $this->categoryActive = true;
    }

    public function toggleCategoryActive(int $id): void
    {
        $category = Category::find($id);
        if ($category) {
            $category->update(['is_active' => !$category->is_active]);
        }
    }

    // ================== Delete ==================

    public function confirmDelete(string $type, int $id): void
    {
        $this->deleteType = $type;
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if (!$this->deleteId) {
            return;
        }

        $model = match ($this->deleteType) {
            'support_type' => SupportType::find($this->deleteId),
            'format' => Format::find($this->deleteId),
            'category' => Category::find($this->deleteId),
            default => null,
        };

        if ($model) {
            $model->delete();
            session()->flash('success', 'Élément supprimé avec succès.');
        }

        $this->closeDeleteModal();
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deleteType = '';
        $this->deleteId = null;
    }

    public function render()
    {
        return view('livewire.settings.order-settings', [
            'supportTypes' => SupportType::orderBy('sort_order')->with('formats')->get(),
            'formats' => Format::with('supportType')->orderBy('sort_order')->get(),
            'categories' => Category::orderBy('sort_order')->get(),
            'supportTypesForSelect' => SupportType::active()->orderBy('sort_order')->get(),
        ]);
    }
}
