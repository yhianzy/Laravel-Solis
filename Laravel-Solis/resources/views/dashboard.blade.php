@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#6366f1,#4f46e5)">
            <div class="stat-value">{{ $totalUsers }}</div>
            <div class="stat-label">Total Users</div>
            <i class="bi bi-people stat-icon"></i>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#06b6d4,#0891b2)">
            <div class="stat-value">{{ $totalMovies }}</div>
            <div class="stat-label">Total Movies</div>
            <i class="bi bi-camera-video stat-icon"></i>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#10b981,#059669)">
            <div class="stat-value">{{ $watchedCount }}</div>
            <div class="stat-label">Watched</div>
            <i class="bi bi-check-circle stat-icon"></i>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#f59e0b,#d97706)">
            <div class="stat-value">{{ $favoriteCount }}</div>
            <div class="stat-label">Favorites</div>
            <i class="bi bi-star stat-icon"></i>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header-clean">Movies by Genre</div>
            <div class="card-body">
                <canvas id="genreChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header-clean">Movies by Year</div>
            <div class="card-body">
                <canvas id="yearChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header-clean">Watch Status</div>
            <div class="card-body">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const genreLabels  = @json($genreData->keys());
const genreValues  = @json($genreData->values());
const yearLabels   = @json($moviesPerYear->keys());
const yearValues   = @json($moviesPerYear->values());
const statusLabels = @json($statusData->keys());
const statusValues = @json($statusData->values());

const palette = ['#6366f1','#06b6d4','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899','#14b8a6','#f97316'];

new Chart(document.getElementById('genreChart'), {
    type: 'doughnut',
    data: { labels: genreLabels, datasets: [{ data: genreValues, backgroundColor: palette, borderWidth: 0, hoverOffset: 6 }] },
    options: { cutout: '65%', plugins: { legend: { position: 'bottom', labels: { padding: 12, font: { size: 11 } } } } }
});

new Chart(document.getElementById('yearChart'), {
    type: 'bar',
    data: { labels: yearLabels, datasets: [{ label: 'Movies', data: yearValues, backgroundColor: 'rgba(99,102,241,0.15)', borderColor: '#6366f1', borderWidth: 2, borderRadius: 8, borderSkipped: false }] },
    options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: 'rgba(0,0,0,0.04)' } }, x: { grid: { display: false } } } }
});

new Chart(document.getElementById('statusChart'), {
    type: 'pie',
    data: { labels: statusLabels, datasets: [{ data: statusValues, backgroundColor: ['#10b981','#ef4444','#6366f1'], borderWidth: 0 }] },
    options: { plugins: { legend: { position: 'bottom', labels: { padding: 12, font: { size: 11 } } } } }
});
</script>
@endsection
