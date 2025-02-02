<?php


namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::with('author')->latest()->paginate(10);
        return view('articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = ArticleCategory::all();
        return view('articles.create', compact('categories'));
    }

    /**
     * Store a newly created resource in Y{storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
            'category_id' => 'required|exists:categories,id'
        ]);

        $article = new Article($validated);

        // Corrected authentication ID access
        $article->author_id = Auth::id(); // Changed from auth()->id()

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('articles', 'public');
            $article->image = $path;
        }

        $article->generateSlug();
        $article->categories()->sync($request->category_id);
        $article->save();

        return redirect()->route('articles.index')->with('success', 'Artikel berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        return view('articles.show', compact('article'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        $categories = ArticleCategory::all();
        return view('articles.edit', compact('article', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date'
        ]);

        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if ($article->image) {
                Storage::disk('public')->delete($article->image);
            }
            $path = $request->file('image')->store('articles', 'public');
            $validated['image'] = $path;
        }

        $article->update($validated);

        if ($article->isDirty('title')) {
            $article->generateSlug();
            $article->save();
        }

        return redirect()->route('articles.index')->with('success', 'Artikel berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        $article->delete();
        return redirect()->route('articles.index')->with('success', 'Artikel berhasil dihapus');
    }
}
