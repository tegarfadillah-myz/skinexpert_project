<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ArticleNews;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->get('category');

        $articles = ArticleNews::when($category, fn($q) => $q->where('category_id', $category))
            ->with('category')
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'data' => $articles,
        ]);
    }

    public function show($id)
    {
        $article = ArticleNews::with('category')->find($id);

        if (!$article) {
            return response()->json([
                'status' => false,
                'message' => 'Artikel tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $article,
        ]);
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => false,
                'message' => 'Autentikasi diperlukan',
            ], 401);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:category_articles,id',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'is_featured' => 'nullable|in:featured,not_featured',
        ]);

        $thumbnailPath = $request->hasFile('thumbnail')
            ? $request->file('thumbnail')->store('thumbnails', 'public')
            : 'thumbnails/gambar-0-alodokter.jpg';

        $article = ArticleNews::create([
            'name' => $validated['name'],
            'content' => $validated['content'],
            'category_id' => $validated['category_id'],
            'thumbnail' => $thumbnailPath,
            'is_featured' => $validated['is_featured'] ?? 'featured',
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Artikel berhasil dibuat',
            'data' => $article,
        ], 201);
    }
}
