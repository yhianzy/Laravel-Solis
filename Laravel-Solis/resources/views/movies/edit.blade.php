@extends('layouts.app')
@section('title', 'Edit Movie')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-9">
        <div class="card">
            <div class="card-header-clean pb-3 border-bottom" style="border-color:var(--border)!important">
                <i class="bi bi-pencil-square me-2 text-primary"></i>Edit Movie
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('movies.update', $movie) }}" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        {{-- Poster preview --}}
                        <div class="col-md-3 text-center">
                            @if($movie->poster)
                                <img src="{{ str_starts_with($movie->poster,'http') ? $movie->poster : Storage::url($movie->poster) }}"
                                    class="rounded mb-2" style="width:100%;max-height:200px;object-fit:cover">
                            @else
                                <div class="rounded mb-2 d-flex align-items-center justify-content-center"
                                    style="width:100%;height:160px;background:linear-gradient(135deg,#6366f1,#06b6d4)">
                                    <i class="bi bi-film text-white" style="font-size:2.5rem;opacity:0.5"></i>
                                </div>
                            @endif
                            <label class="form-label">Upload Poster</label>
                            <input type="file" name="poster" class="form-control form-control-sm" accept="image/*">
                            <div class="mt-2">
                                <label class="form-label">Or Poster URL</label>
                                <input type="text" name="poster_url" class="form-control form-control-sm"
                                    value="{{ str_starts_with($movie->poster ?? '','http') ? $movie->poster : '' }}" placeholder="https://...">
                            </div>
                        </div>

                        <div class="col-md-9">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label">Movie Title *</label>
                                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                        value="{{ old('title', $movie->title) }}" required>
                                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Year *</label>
                                    <input type="number" name="year" class="form-control"
                                        value="{{ old('year', $movie->year) }}" min="1900" max="{{ date('Y')+1 }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Genre *</label>
                                    <select name="genre" class="form-select" required>
                                        @foreach(['Action','Comedy','Drama','Horror','Romance','Sci-Fi','Thriller','Animation','Documentary','Fantasy'] as $g)
                                            <option value="{{ $g }}" {{ old('genre',$movie->genre) == $g ? 'selected' : '' }}>{{ $g }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        @foreach(['Unwatched','Watched','Watchlist'] as $s)
                                            <option value="{{ $s }}" {{ old('status',$movie->status) == $s ? 'selected' : '' }}>{{ $s }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Rating</label>
                                    <input type="number" name="rating" class="form-control"
                                        value="{{ old('rating', $movie->rating) }}" min="0" max="10" step="0.1">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Duration (min)</label>
                                    <input type="number" name="duration" class="form-control"
                                        value="{{ old('duration', $movie->duration) }}" min="1">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Language</label>
                                    <select name="language" class="form-select">
                                        @foreach(['English','Filipino','Japanese','Korean','Spanish','French','Hindi','Chinese','Other'] as $l)
                                            <option value="{{ $l }}" {{ old('language',$movie->language) == $l ? 'selected' : '' }}>{{ $l }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Director</label>
                                    <input type="text" name="director" class="form-control"
                                        value="{{ old('director', $movie->director) }}" placeholder="e.g. Christopher Nolan">
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label">Cast</label>
                                    <input type="text" name="cast" class="form-control"
                                        value="{{ old('cast', $movie->cast) }}" placeholder="e.g. Christian Bale, Heath Ledger">
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description', $movie->description) }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-sm px-4">Save Changes</button>
                        <a href="{{ route('movies.index') }}" class="btn btn-sm btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
