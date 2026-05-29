<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers    = User::count();
        $totalMovies   = Movie::count();
        $watchedCount  = Movie::where('status', 'Watched')->count();
        $favoriteCount = Movie::where('is_favorite', true)->count();

        $genreData = Movie::selectRaw('genre, count(*) as count')
            ->groupBy('genre')->pluck('count', 'genre');

        $moviesPerYear = Movie::selectRaw('year, count(*) as count')
            ->groupBy('year')->orderBy('year')->pluck('count', 'year');

        $statusData = Movie::selectRaw('status, count(*) as count')
            ->groupBy('status')->pluck('count', 'status');

        return view('dashboard', compact(
            'totalUsers','totalMovies','watchedCount','favoriteCount',
            'genreData','moviesPerYear','statusData'
        ));
    }
}
