<?php

namespace App\Livewire\Admin;

use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ArticleManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $title;
    public $content;
    public $status = 'draft';
    public $image;
    public $article_id;
    public $categories = [];
    public $selectedCategories = [];

    public $isEditing = false;
    public $showDeleteModal = false;

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
        $articles = Article::with('author')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.article-management', [
            'articles' => $articles,
            'categories' => $this->categories
        ]);
    }

    public function create()
    {
        $this->validate();

        $imagePath = $this->image ? $this->image->store('articles', 'public') : null;

        $article = Article::create([
            'author_id' => Auth::id(),
            'title' => $this->title,
            'slug' => Str::slug($this->title),
            'content' => $this->content,
            'status' => $this->status,
            'image' => $imagePath,
            'published_at' => $this->status === 'published' ? now() : null,
        ]);

        // Attach categories
        if (!empty($this->selectedCategories)) {
            $article->articleCategories()->sync($this->selectedCategories);
        }

        $this->reset([
            'title', 'content', 'status', 'image', 'selectedCategories'
        ]);

        session()->flash('message', 'Article successfully added.');
    }

    public function edit($id)
    {
        $this->isEditing = true;
        $this->article_id = $id;
        $article = Article::findOrFail($id);

        $this->title = $article->title;
        $this->content = $article->content;
        $this->status = $article->status;
        $this->selectedCategories = $article->articleCategories->pluck('id')->toArray();
    }

    public function update()
    {
        $this->validate();

        $article = Article::findOrFail($this->article_id);

        // Handle image upload if a new image is provided
        if ($this->image) {
            $imagePath = $this->image->store('articles', 'public');
            $article->image = $imagePath;
        }

        $article->update([
            'title' => $this->title,
            'slug' => Str::slug($this->title),
            'content' => $this->content,
            'status' => $this->status,
            'published_at' => $this->status === 'published' ? now() : null,
        ]);

        // Sync categories
        $article->articleCategories()->sync($this->selectedCategories);

        $this->reset([
            'title', 'content', 'status', 'image', 'selectedCategories', 
            'isEditing', 'article_id'
        ]);

        session()->flash('message', 'Article successfully updated.');
    }

    public function confirmDelete($id)
    {
        $this->article_id = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $article = Article::findOrFail($this->article_id);
        $article->delete();

        $this->reset(['showDeleteModal', 'article_id']);
        session()->flash('message', 'Article successfully deleted.');
    }

    public function cancel()
    {
        $this->reset([
            'title', 'content', 'status', 'image', 'selectedCategories', 
            'isEditing', 'article_id', 'showDeleteModal'
        ]);
    }
}
