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

    public function mount()
    {
        $this->searchTerm = '';
        $this->articles = $this->searchArticles(); // Fetch all articles
    }

    public function createArticle()
    {
        Article::create(['title' => $this->title, 'content' => $this->content]);
        $this->resetFields();
        $this->mount(); // Refresh the articles list
    }

    public function updateArticle()
    {
        $article = Article::find($this->articleId);
        $article->update(['title' => $this->title, 'content' => $this->content]);
        $this->resetFields();
        $this->mount(); // Refresh the articles list
    }

    public function deleteArticle($id)
    {
        Article::destroy($id);
        $this->mount(); // Refresh the articles list
    }

    public function resetFields()
    {
        $this->title = '';
        $this->content = '';
        $this->articleId = null;
    }

    public function searchArticles()
    {
        return Article::where('title', 'like', "%{$this->searchTerm}%")
                       ->orWhere('content', 'like', "%{$this->searchTerm}%")
                       ->get();
    }

    public function render()
    {
        $articles = $this->searchArticles();
        return view('livewire.admin.articles.articles-list', compact('articles'));
    }
}
