@extends('layouts.app')
@section('title', 'My Movies')

@section('content')

{{-- Top bar --}}
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <p class="text-muted mb-0" style="font-size:0.875rem">
        {{ $movies->total() }} movie{{ $movies->total() != 1 ? 's' : '' }} found
    </p>
    <div class="d-flex gap-2">
        <div class="btn-group btn-group-sm">
            <button class="btn btn-outline-primary active" id="btnTable" onclick="setView('table')"><i class="bi bi-list-ul"></i></button>
            <button class="btn btn-outline-primary" id="btnCard" onclick="setView('card')"><i class="bi bi-grid-3x3-gap"></i></button>
        </div>
        <button class="btn btn-primary btn-sm px-3" data-bs-toggle="modal" data-bs-target="#addMovieModal">
            <i class="bi bi-plus-circle me-1"></i> <span class="d-none d-sm-inline">Add Movie</span><span class="d-sm-none">Add</span>
        </button>
    </div>
</div>

{{-- Filters --}}
<div class="card mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('movies.index') }}">
            <div class="row g-2 align-items-end">
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="form-label mb-1">Search</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text" style="background:var(--input-bg);border-color:var(--border)"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Search title..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-2">
                    <label class="form-label mb-1">Genre</label>
                    <select name="genre" class="form-select form-select-sm">
                        <option value="">All Genres</option>
                        @foreach(['Action','Comedy','Drama','Horror','Romance','Sci-Fi','Thriller','Animation','Documentary','Fantasy'] as $g)
                            <option value="{{ $g }}" {{ request('genre') == $g ? 'selected' : '' }}>{{ $g }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-sm-4 col-md-2">
                    <label class="form-label mb-1">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Status</option>
                        @foreach(['Watched','Unwatched','Watchlist'] as $s)
                            <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-sm-4 col-md-2">
                    <label class="form-label mb-1">Year Range</label>
                    <div class="d-flex gap-1">
                        <input type="number" name="year_from" class="form-control form-control-sm" placeholder="From" value="{{ request('year_from') }}" min="1900" max="{{ date('Y')+1 }}">
                        <input type="number" name="year_to" class="form-control form-control-sm" placeholder="To" value="{{ request('year_to') }}" min="1900" max="{{ date('Y')+1 }}">
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-2">
                    <label class="form-label mb-1">Sort By</label>
                    <select name="sort" class="form-select form-select-sm">
                        <option value="latest"      {{ request('sort','latest') == 'latest'      ? 'selected' : '' }}>Latest</option>
                        <option value="title"       {{ request('sort') == 'title'                ? 'selected' : '' }}>Title A–Z</option>
                        <option value="year_desc"   {{ request('sort') == 'year_desc'            ? 'selected' : '' }}>Year ↓</option>
                        <option value="year_asc"    {{ request('sort') == 'year_asc'             ? 'selected' : '' }}>Year ↑</option>
                        <option value="rating_desc" {{ request('sort') == 'rating_desc'          ? 'selected' : '' }}>Rating ↓</option>
                        <option value="rating_asc"  {{ request('sort') == 'rating_asc'           ? 'selected' : '' }}>Rating ↑</option>
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-1 d-flex gap-1">
                    <button type="submit" class="btn btn-primary btn-sm flex-fill"><i class="bi bi-funnel"></i></button>
                    <a href="{{ route('movies.index') }}" class="btn btn-outline-secondary btn-sm flex-fill"><i class="bi bi-x"></i></a>
                </div>
            </div>
            <div class="mt-2">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="favorites" id="favOnly" value="1"
                        {{ request('favorites') ? 'checked' : '' }} onchange="this.form.submit()">
                    <label class="form-check-label" for="favOnly" style="font-size:0.82rem">
                        <i class="bi bi-star-fill text-warning me-1"></i>Favorites only
                    </label>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- TABLE VIEW --}}
<div id="tableView">
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:40px">#</th>
                        <th style="width:55px">Poster</th>
                        <th>Title</th>
                        <th>Genre</th>
                        <th>Year</th>
                        <th>Rating</th>
                        <th>Status</th>
                        <th class="d-none d-lg-table-cell">Director</th>
                        <th style="width:115px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movies as $movie)
                    <tr>
                        <td class="text-muted">{{ $loop->iteration }}</td>
                        <td>
                            @if($movie->poster)
                                <img src="{{ str_starts_with($movie->poster,'http') ? $movie->poster : Storage::url($movie->poster) }}"
                                    class="rounded" width="38" height="52" style="object-fit:cover">
                            @else
                                <div class="rounded d-flex align-items-center justify-content-center"
                                    style="width:38px;height:52px;background:linear-gradient(135deg,#6366f1,#06b6d4)">
                                    <i class="bi bi-film text-white" style="font-size:0.9rem"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold" style="cursor:pointer;white-space:nowrap" onclick="showDetail({{ $movie->id }})">
                                {{ $movie->title }}
                                @if($movie->is_favorite)<i class="bi bi-star-fill text-warning ms-1" style="font-size:0.7rem"></i>@endif
                            </div>
                            @if($movie->duration)<div class="text-muted" style="font-size:0.72rem">{{ $movie->duration }} min</div>@endif
                        </td>
                        <td><span class="badge-genre">{{ $movie->genre }}</span></td>
                        <td class="text-muted" style="white-space:nowrap">{{ $movie->year }}</td>
                        <td>
                            @if($movie->rating)
                                <span class="badge-rating"><i class="bi bi-star-fill me-1"></i>{{ $movie->rating }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @php $sc = ['Watched'=>'badge-watched','Unwatched'=>'badge-unwatched','Watchlist'=>'badge-watchlist']; @endphp
                            <span class="{{ $sc[$movie->status] ?? 'badge-unwatched' }}">{{ $movie->status }}</span>
                        </td>
                        <td class="text-muted d-none d-lg-table-cell" style="font-size:0.8rem;white-space:nowrap;max-width:120px;overflow:hidden;text-overflow:ellipsis">
                            {{ $movie->director ?? '—' }}
                        </td>
                        <td style="white-space:nowrap">
                            <div class="d-flex align-items-center gap-1">
                                <form method="POST" action="{{ route('movies.favorite', $movie) }}">
                                    @csrf
                                    <button type="submit" class="fav-btn {{ $movie->is_favorite ? 'active' : '' }}" title="Favorite">
                                        <i class="bi bi-star{{ $movie->is_favorite ? '-fill' : '' }}"></i>
                                    </button>
                                </form>
                                <a href="{{ route('movies.edit', $movie) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                <form method="POST" action="{{ route('movies.destroy', $movie) }}" onsubmit="return confirm('Delete this movie?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="bi bi-camera-video fs-2 d-block mb-2 opacity-25"></i>
                            No movies found. Try adjusting your filters.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- CARD VIEW --}}
<div id="cardView" style="display:none">
    <div class="row g-3">
        @forelse($movies as $movie)
        <div class="col-6 col-sm-4 col-md-3 col-xl-2">
            <div class="movie-card h-100" onclick="showDetail({{ $movie->id }})">
                <div class="poster-wrap">
                    @if($movie->poster)
                        <img src="{{ str_starts_with($movie->poster,'http') ? $movie->poster : Storage::url($movie->poster) }}" alt="{{ $movie->title }}">
                    @else
                        <i class="bi bi-film"></i>
                    @endif
                </div>
                <div class="card-body">
                    <div class="movie-title">{{ $movie->title }}</div>
                    <div class="d-flex justify-content-between align-items-center mt-1">
                        <span class="badge-genre" style="font-size:0.65rem">{{ $movie->genre }}</span>
                        <span class="text-muted" style="font-size:0.72rem">{{ $movie->year }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-1">
                        @php $sc = ['Watched'=>'badge-watched','Unwatched'=>'badge-unwatched','Watchlist'=>'badge-watchlist']; @endphp
                        <span class="{{ $sc[$movie->status] ?? 'badge-unwatched' }}" style="font-size:0.65rem">{{ $movie->status }}</span>
                        @if($movie->rating)<span class="badge-rating" style="font-size:0.65rem"><i class="bi bi-star-fill"></i> {{ $movie->rating }}</span>@endif
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-2" onclick="event.stopPropagation()">
                        <form method="POST" action="{{ route('movies.favorite', $movie) }}">
                            @csrf
                            <button type="submit" class="fav-btn {{ $movie->is_favorite ? 'active' : '' }}">
                                <i class="bi bi-star{{ $movie->is_favorite ? '-fill' : '' }}"></i>
                            </button>
                        </form>
                        <div class="d-flex gap-1">
                            <a href="{{ route('movies.edit', $movie) }}" class="btn btn-sm btn-outline-primary py-0 px-1" style="font-size:0.75rem"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('movies.destroy', $movie) }}" onsubmit="return confirm('Delete?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-1" style="font-size:0.75rem"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5 text-muted">
            <i class="bi bi-camera-video fs-2 d-block mb-2 opacity-25"></i>No movies found.
        </div>
        @endforelse
    </div>
</div>

{{-- Pagination --}}
<div class="mt-4 d-flex justify-content-center">
    {{ $movies->links() }}
</div>

{{-- Movie Detail Modal --}}
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailTitle">Movie Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" id="detailBody">
                <div class="text-center py-5"><div class="spinner-border text-primary"></div></div>
            </div>
        </div>
    </div>
</div>

{{-- Add Movie Modal --}}
<div class="modal fade" id="addMovieModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-camera-video me-2 text-primary"></i>Add New Movie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('movies.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12 col-sm-8">
                            <label class="form-label">Movie Title *</label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title') }}" placeholder="e.g. The Dark Knight" required>
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-6 col-sm-4">
                            <label class="form-label">Year *</label>
                            <input type="number" name="year" class="form-control @error('year') is-invalid @enderror"
                                value="{{ old('year', date('Y')) }}" min="1900" max="{{ date('Y')+1 }}" required>
                            @error('year')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-6 col-sm-4">
                            <label class="form-label">Genre *</label>
                            <select name="genre" class="form-select @error('genre') is-invalid @enderror" required>
                                <option value="">Select genre</option>
                                @foreach(['Action','Comedy','Drama','Horror','Romance','Sci-Fi','Thriller','Animation','Documentary','Fantasy'] as $g)
                                    <option value="{{ $g }}" {{ old('genre') == $g ? 'selected' : '' }}>{{ $g }}</option>
                                @endforeach
                            </select>
                            @error('genre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-6 col-sm-4">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                @foreach(['Unwatched','Watched','Watchlist'] as $s)
                                    <option value="{{ $s }}" {{ old('status','Unwatched') == $s ? 'selected' : '' }}>{{ $s }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6 col-sm-4">
                            <label class="form-label">Language</label>
                            <select name="language" class="form-select">
                                @foreach(['English','Filipino','Japanese','Korean','Spanish','French','Hindi','Chinese','Other'] as $l)
                                    <option value="{{ $l }}" {{ old('language','English') == $l ? 'selected' : '' }}>{{ $l }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6 col-sm-2">
                            <label class="form-label">Rating</label>
                            <input type="number" name="rating" class="form-control" value="{{ old('rating') }}" min="0" max="10" step="0.1" placeholder="0–10">
                        </div>
                        <div class="col-6 col-sm-2">
                            <label class="form-label">Duration (min)</label>
                            <input type="number" name="duration" class="form-control" value="{{ old('duration') }}" min="1" placeholder="120">
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label">Director</label>
                            <input type="text" name="director" class="form-control" value="{{ old('director') }}" placeholder="e.g. Christopher Nolan">
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label">Cast</label>
                            <input type="text" name="cast" class="form-control" value="{{ old('cast') }}" placeholder="e.g. Christian Bale, Heath Ledger">
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label">Poster Image Upload</label>
                            <input type="file" name="poster" class="form-control" accept="image/*" id="posterFile">
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label">Or Poster URL</label>
                            <input type="text" name="poster_url" class="form-control" value="{{ old('poster_url') }}" placeholder="https://..." id="posterUrl">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="2" placeholder="Brief synopsis...">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary px-4">Add Movie</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Hidden data for detail modal --}}
<div id="movieData" style="display:none">
    @foreach($movies as $m)
    <div data-id="{{ $m->id }}"
         data-title="{{ $m->title }}"
         data-genre="{{ $m->genre }}"
         data-year="{{ $m->year }}"
         data-rating="{{ $m->rating ?? '—' }}"
         data-status="{{ $m->status }}"
         data-director="{{ $m->director ?? '—' }}"
         data-cast="{{ $m->cast ?? '—' }}"
         data-duration="{{ $m->duration ? $m->duration.' min' : '—' }}"
         data-language="{{ $m->language ?? '—' }}"
         data-description="{{ $m->description ?? '' }}"
         data-poster="{{ $m->poster ? (str_starts_with($m->poster,'http') ? $m->poster : Storage::url($m->poster)) : '' }}"
         data-favorite="{{ $m->is_favorite ? '1' : '0' }}"
         data-edit="{{ route('movies.edit', $m) }}">
    </div>
    @endforeach
</div>

@if($errors->any())
<script>document.addEventListener('DOMContentLoaded', () => new bootstrap.Modal(document.getElementById('addMovieModal')).show());</script>
@endif
@endsection

@section('scripts')
<script>
function setView(v) {
    const isCard = v === 'card';
    document.getElementById('tableView').style.display = isCard ? 'none' : 'block';
    document.getElementById('cardView').style.display  = isCard ? 'block' : 'none';
    document.getElementById('btnTable').classList.toggle('active', !isCard);
    document.getElementById('btnCard').classList.toggle('active', isCard);
    localStorage.setItem('movieView', v);
}
(function(){
    const v = localStorage.getItem('movieView') || 'table';
    if (v === 'card') setView('card');
})();

// Clear file input when URL is typed and vice versa
document.getElementById('posterUrl')?.addEventListener('input', function() {
    if (this.value) document.getElementById('posterFile').value = '';
});
document.getElementById('posterFile')?.addEventListener('change', function() {
    if (this.files.length) document.getElementById('posterUrl').value = '';
});

function showDetail(id) {
    const el = document.querySelector('#movieData [data-id="' + id + '"]');
    if (!el) return;
    const d = el.dataset;
    const sc = {Watched:'badge-watched', Unwatched:'badge-unwatched', Watchlist:'badge-watchlist'};
    const poster = d.poster
        ? '<img src="' + d.poster + '" style="width:100%;height:260px;object-fit:cover">'
        : '<div style="width:100%;height:260px;background:linear-gradient(135deg,#6366f1,#06b6d4);display:flex;align-items:center;justify-content:center"><i class="bi bi-film text-white" style="font-size:4rem;opacity:0.4"></i></div>';

    document.getElementById('detailTitle').textContent = d.title;
    document.getElementById('detailBody').innerHTML =
        '<div class="row g-0">' +
            '<div class="col-md-4">' + poster + '</div>' +
            '<div class="col-md-8 p-4">' +
                '<div class="d-flex gap-2 flex-wrap mb-3">' +
                    '<span class="badge-genre">' + d.genre + '</span>' +
                    '<span class="' + (sc[d.status] || 'badge-unwatched') + '">' + d.status + '</span>' +
                    (d.favorite === '1' ? '<span style="color:#f59e0b"><i class="bi bi-star-fill"></i> Favorite</span>' : '') +
                '</div>' +
                '<div class="row g-2 mb-3">' +
                    '<div class="col-6"><div style="font-size:0.68rem;font-weight:600;color:#94a3b8;text-transform:uppercase">Year</div><div style="font-weight:600">' + d.year + '</div></div>' +
                    '<div class="col-6"><div style="font-size:0.68rem;font-weight:600;color:#94a3b8;text-transform:uppercase">Rating</div><div style="font-weight:600">' + (d.rating !== '—' ? '⭐ ' + d.rating + '/10' : '—') + '</div></div>' +
                    '<div class="col-6"><div style="font-size:0.68rem;font-weight:600;color:#94a3b8;text-transform:uppercase">Duration</div><div style="font-weight:600">' + d.duration + '</div></div>' +
                    '<div class="col-6"><div style="font-size:0.68rem;font-weight:600;color:#94a3b8;text-transform:uppercase">Language</div><div style="font-weight:600">' + d.language + '</div></div>' +
                    '<div class="col-12"><div style="font-size:0.68rem;font-weight:600;color:#94a3b8;text-transform:uppercase">Director</div><div style="font-weight:600">' + d.director + '</div></div>' +
                    '<div class="col-12"><div style="font-size:0.68rem;font-weight:600;color:#94a3b8;text-transform:uppercase">Cast</div><div style="font-weight:600;font-size:0.85rem">' + d.cast + '</div></div>' +
                '</div>' +
                (d.description ? '<p style="font-size:0.875rem;color:var(--text-muted);line-height:1.6">' + d.description + '</p>' : '') +
                '<a href="' + d.edit + '" class="btn btn-sm btn-primary mt-2"><i class="bi bi-pencil me-1"></i>Edit Movie</a>' +
            '</div>' +
        '</div>';
    new bootstrap.Modal(document.getElementById('detailModal')).show();
}
</script>
@endsection
