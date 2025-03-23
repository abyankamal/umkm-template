<?php

namespace App\Livewire\Admin\Articles;

use Livewire\Component;
use App\Models\Article; // Ensure to import the Article model
use Livewire\Attributes\Rule;

class ArticleList extends Component
{
    public $articles = []; // To hold articles
    public $searchTerm;
    
    #[Rule('required|string|max:255|unique:articles,title')]
    public $title = ''; // For the article title
    #[Rule('required|string')]
    public $content = ''; // For the article content
    public $articleId = null; // To track the article being edited
    
    // Add these properties for modals
    public $showModal = false;
    public $showDeleteModal = false;
    public $deleteId = null;

    public function mount()
    {
        $this->searchTerm = '';
        $this->articles = $this->searchArticles(); // Fetch all articles
    }

    public function createArticle()
    {
        $this->validate();
        
        Article::create(['title' => $this->title, 'content' => $this->content]);
        $this->resetFields();
        $this->showModal = false;
        $this->dispatch('article-saved', 'Artikel berhasil disimpan!');
        $this->articles = $this->searchArticles(); // Refresh the articles list
    }

    public function editArticle($id)
    {
        $article = Article::find($id);
        $this->articleId = $article->id;
        $this->title = $article->title;
        $this->content = $article->content;
        $this->showModal = true;
    }

    public function updateArticle()
    {
        $this->validate([
            'title' => 'required|string|max:255|unique:articles,title,'.$this->articleId,
            'content' => 'required|string',
        ]);
        
        $article = Article::find($this->articleId);
        $article->update(['title' => $this->title, 'content' => $this->content]);
        $this->resetFields();
        $this->showModal = false;
        $this->dispatch('article-saved', 'Artikel berhasil diperbarui!');
        $this->articles = $this->searchArticles(); // Refresh the articles list
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function deleteConfirmed()
    {
        Article::destroy($this->deleteId);
        $this->showDeleteModal = false;
        $this->dispatch('article-deleted', 'Artikel berhasil dihapus!');
        $this->articles = $this->searchArticles(); // Refresh the articles list
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->deleteId = null;
    }

    public function resetFields()
    {
        $this->title = '';
        $this->content = '';
        $this->articleId = null;
        $this->showModal = true; // Show modal when adding new article
    }

    public function searchArticles()
    {
        return Article::where('title', 'like', "%{$this->searchTerm}%")
                       ->orWhere('content', 'like', "%{$this->searchTerm}%")
                       ->get();
    }

    public function render()
    {
        $this->articles = $this->searchArticles();
        return view('livewire.admin.articles.articles-list');
    }
}
