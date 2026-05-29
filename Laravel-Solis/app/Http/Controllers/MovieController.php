<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        $query = Movie::where('user_id', Auth::id());

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('genre')) {
            $query->where('genre', $request->genre);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('year_from')) {
            $query->where('year', '>=', $request->year_from);
        }
        if ($request->filled('year_to')) {
            $query->where('year', '<=', $request->year_to);
        }
        if ($request->filled('favorites')) {
            $query->where('is_favorite', true);
        }

        $sort = $request->get('sort', 'latest');
        match($sort) {
            'title'       => $query->orderBy('title'),
            'year_asc'    => $query->orderBy('year'),
            'year_desc'   => $query->orderByDesc('year'),
            'rating_desc' => $query->orderByDesc('rating'),
            'rating_asc'  => $query->orderBy('rating'),
            default       => $query->latest(),
        };

        $movies = $query->paginate(12)->withQueryString();
        $genres = Movie::where('user_id', Auth::id())->distinct()->pluck('genre');

        return view('movies.index', compact('movies', 'genres'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'    => 'required|string|max:255',
            'genre'    => 'required|string|max:100',
            'year'     => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'rating'   => 'nullable|numeric|min:0|max:10',
            'duration' => 'nullable|integer|min:1',
            'poster'   => 'nullable|image|max:3048',
        ]);

        $data = $request->only('title','genre','year','rating','description','director','cast','duration','language','status');
        $data['user_id']     = Auth::id();
        $data['is_favorite'] = false;

        if ($request->hasFile('poster')) {
            $data['poster'] = $this->imageToBase64($request->file('poster'));
        } elseif ($request->filled('poster_url')) {
            $data['poster'] = $request->poster_url;
        }

        Movie::create($data);
        return redirect()->route('movies.index')->with('success', 'Movie added successfully!');
    }

    public function show(Movie $movie)
    {
        abort_if($movie->user_id !== Auth::id(), 403);
        return view('movies.show', compact('movie'));
    }

    public function edit(Movie $movie)
    {
        abort_if($movie->user_id !== Auth::id(), 403);
        return view('movies.edit', compact('movie'));
    }

    public function update(Request $request, Movie $movie)
    {
        abort_if($movie->user_id !== Auth::id(), 403);

        $request->validate([
            'title'    => 'required|string|max:255',
            'genre'    => 'required|string|max:100',
            'year'     => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'rating'   => 'nullable|numeric|min:0|max:10',
            'duration' => 'nullable|integer|min:1',
            'poster'   => 'nullable|image|max:3048',
        ]);

        $data = $request->only('title','genre','year','rating','description','director','cast','duration','language','status');

        if ($request->hasFile('poster')) {
            $data['poster'] = $this->imageToBase64($request->file('poster'));
        } elseif ($request->filled('poster_url')) {
            $data['poster'] = $request->poster_url;
        }

        $movie->update($data);
        return redirect()->route('movies.index')->with('success', 'Movie updated successfully!');
    }

    public function destroy(Movie $movie)
    {
        abort_if($movie->user_id !== Auth::id(), 403);
        $movie->delete();
        return redirect()->route('movies.index')->with('success', 'Movie deleted successfully!');
    }

    public function toggleFavorite(Movie $movie)
    {
        abort_if($movie->user_id !== Auth::id(), 403);
        $movie->update(['is_favorite' => !$movie->is_favorite]);
        return back()->with('success', $movie->is_favorite ? 'Added to favorites!' : 'Removed from favorites.');
    }

    private function imageToBase64($file): string
    {
        $mime    = $file->getMimeType();
        $content = base64_encode(file_get_contents($file->getRealPath()));
        return 'data:' . $mime . ';base64,' . $content;
    }
}
