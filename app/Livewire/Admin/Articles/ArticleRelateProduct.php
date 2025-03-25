<?php

namespace App\Livewire\Admin\Articles;

use App\Models\Article;
use App\Models\Product;
use App\Models\ArticleProduct;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class ArticleRelateProduct extends Component
{
    use WithPagination;

    public $article_id;
    public $product_id;
    public $search = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $filters = [
        'category' => '',
    ];
    
    // Form properties
    public $editMode = false;
    public $relationId = null;
    
    protected $rules = [
        'article_id' => 'required|exists:articles,id',
        'product_id' => 'required|exists:products,id',
    ];
    
    protected $listeners = ['refreshRelations' => '$refresh'];
    
    public function mount($articleId = null)
    {
        if ($articleId) {
            $this->article_id = $articleId;
        }
    }
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingPerPage()
    {
        $this->resetPage();
    }
    
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        
        $this->sortField = $field;
    }
    
    public function create()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->dispatchBrowserEvent('open-modal', ['modal' => 'relation-form']);
    }
    
    public function edit($id)
    {
        $this->resetForm();
        $this->editMode = true;
        $this->relationId = $id;
        
        $relation = ArticleProduct::findOrFail($id);
        $this->article_id = $relation->article_id;
        $this->product_id = $relation->product_id;
        
        $this->dispatchBrowserEvent('open-modal', ['modal' => 'relation-form']);
    }
    
    public function save()
    {
        $this->validate();
        
        // Check if relation already exists
        $exists = ArticleProduct::where('article_id', $this->article_id)
            ->where('product_id', $this->product_id)
            ->exists();
            
        if ($exists && !$this->editMode) {
            $this->addError('product_id', 'This product is already related to the article.');
            return;
        }
        
        if ($this->editMode) {
            $relation = ArticleProduct::findOrFail($this->relationId);
            $relation->update([
                'article_id' => $this->article_id,
                'product_id' => $this->product_id,
            ]);
            
            $this->dispatchBrowserEvent('close-modal', ['modal' => 'relation-form']);
            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => 'Relation updated successfully!'
            ]);
        } else {
            ArticleProduct::create([
                'article_id' => $this->article_id,
                'product_id' => $this->product_id,
            ]);
            
            $this->dispatchBrowserEvent('close-modal', ['modal' => 'relation-form']);
            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => 'Relation created successfully!'
            ]);
        }
        
        $this->resetForm();
    }
    
    public function delete($id)
    {
        $relation = ArticleProduct::findOrFail($id);
        $relation->delete();
        
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => 'Relation deleted successfully!'
        ]);
    }
    
    public function resetForm()
    {
        $this->reset(['product_id', 'relationId']);
        $this->resetErrorBag();
    }
    
    public function getProductsProperty()
    {
        return Product::where('name', 'like', '%' . $this->search . '%')
            ->when($this->filters['category'], function($query, $category) {
                return $query->where('category_id', $category);
            })
            ->orderBy('name')
            ->get();
    }
    
    public function getRelationsQuery()
    {
        return ArticleProduct::query()
            ->join('products', 'article_products.product_id', '=', 'products.id')
            ->join('articles', 'article_products.article_id', '=', 'articles.id')
            ->select('article_products.*', 'products.name as product_name', 'articles.title as article_title')
            ->when($this->article_id, function($query, $articleId) {
                return $query->where('article_products.article_id', $articleId);
            })
            ->when($this->search, function($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('products.name', 'like', '%' . $search . '%')
                      ->orWhere('articles.title', 'like', '%' . $search . '%');
                });
            })
            ->when($this->filters['category'], function($query, $category) {
                return $query->whereHas('product', function($q) use ($category) {
                    $q->where('category_id', $category);
                });
            })
            ->orderBy($this->sortField, $this->sortDirection);
    }
    
    public function render()
    {
        $categories = DB::table('categories')->orderBy('name')->get();
        $relations = $this->getRelationsQuery()->paginate($this->perPage);
        
        return view('livewire.admin.articles.article-relate-product', [
            'relations' => $relations,
            'products' => $this->products,
            'categories' => $categories,
            'articles' => Article::orderBy('title')->get(),
        ]);
    }
}
