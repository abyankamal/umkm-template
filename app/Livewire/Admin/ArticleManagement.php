<?php

namespace App\Livewire\Admin;

use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

#[Layout('livewire.layouts.admin-layout')]
class ArticleManagement extends Component
{
    use WithPagination, WithFileUploads;

    // Form Properties
    public $title;
    public $content;
    public $status = 'draft';
    public $image;
    public $article_id;
    public $categories = [];
    public $selectedCategories = [];

    // UI State
    public $showForm = false;
    public $isEditing = false;
    public $showDeleteModal = false;

    // Filter and Sort
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $statusFilter = '';
    public $categoryFilter = '';

    protected $rules = [
        'title' => 'required|min:3|max:255',
        'content' => 'required',
        'status' => 'in:draft,published',
        'image' => 'nullable|image|max:2048',
        'selectedCategories' => 'array',
    ];

    public function mount()
    {
        $this->categories = ArticleCategory::all();
    }

    public function render()
    {
        $query = Article::with(['author', 'categories'])
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('content', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->categoryFilter, function($query) {
                $query->whereHas('categories', function($q) {
                    $q->where('id', $this->categoryFilter);
                });
            })
            ->orderBy($this->sortField, $this->sortDirection);

        $articles = $query->paginate(10);

        return view('livewire.admin.article-management', [
            'articles' => $articles,
            'categories' => $this->categories
        ]);
    }

    public function create()
    {
        $this->validate();

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('articles', 'public');
        }

        $article = Article::create([
            'title' => $this->title,
            'slug' => Str::slug($this->title),
            'content' => $this->content,
            'status' => $this->status,
            'image' => $imagePath,
            'author_id' => Auth::id(),
        ]);

        if (!empty($this->selectedCategories)) {
            $article->categories()->sync($this->selectedCategories);
        }

        session()->flash('message', 'Article created successfully!');
        $this->reset();
        $this->showForm = false;
    }

    public function edit($id)
    {
        $article = Article::with('categories')->findOrFail($id);
        
        $this->article_id = $id;
        $this->title = $article->title;
        $this->content = $article->content;
        $this->status = $article->status;
        $this->selectedCategories = $article->categories->pluck('id')->toArray();
        
        $this->isEditing = true;
        $this->showForm = true;
    }

    public function update()
    {
        $this->validate();

        $article = Article::findOrFail($this->article_id);

        $imagePath = $article->image;
        if ($this->image) {
            if ($article->image) {
                Storage::disk('public')->delete($article->image);
            }
            $imagePath = $this->image->store('articles', 'public');
        }

        $article->update([
            'title' => $this->title,
            'slug' => Str::slug($this->title),
            'content' => $this->content,
            'status' => $this->status,
            'image' => $imagePath,
        ]);

        if (!empty($this->selectedCategories)) {
            $article->categories()->sync($this->selectedCategories);
        }

        session()->flash('message', 'Article updated successfully!');
        $this->reset();
        $this->showForm = false;
    }

    public function confirmDelete($id)
    {
        $this->article_id = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $article = Article::findOrFail($this->article_id);
        
        if ($article->image) {
            Storage::disk('public')->delete($article->image);
        }
        
        $article->categories()->detach();
        $article->delete();
        
        session()->flash('message', 'Article deleted successfully!');
        $this->showDeleteModal = false;
    }

    public function resetForm()
    {
        $this->reset();
        $this->resetValidation();
    }
}
