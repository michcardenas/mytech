@extends('layouts.app_admin')

@section('content')
<div class="container py-5 text-white">
    <h2 class="mb-4 text-warning">Add New Category 🐄</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data" class="bg-dark p-4 rounded">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label text-light">Category Name</label>
            <input type="text" name="name" id="name" class="form-control" required value="{{ old('name') }}" placeholder="Enter category name">
        </div>

        <div class="mb-3">
            <label for="description" class="form-label text-light">Description</label>
            <textarea name="description" id="description" class="form-control" rows="4" placeholder="Enter category description">{{ old('description') }}</textarea>
        </div>

        <!-- <div class="mb-3">
            <label for="country" class="form-label text-light">Country of Origin</label>
            <select name="country" id="country" class="form-control" required>
                <option value="">Select a country</option>
                <option value="Uruguay" {{ old('country') == 'Uruguay' ? 'selected' : '' }}>Uruguay</option>
                <option value="Argentina" {{ old('country') == 'Argentina' ? 'selected' : '' }}>Argentina</option>
                <option value="America" {{ old('country') == 'America' ? 'selected' : '' }}>America</option>
            </select>
        </div> -->

        <div class="mb-3">
            <label for="image" class="form-label text-light">Category Image</label>
            <input type="file" name="image" id="image" class="form-control" accept="image/*">
            <small class="text-muted">Accepted formats: JPG, PNG, GIF. Max size: 2MB</small>
        </div>

        <div class="d-flex gap-3">
            <button type="submit" class="btn btn-warning text-dark fw-bold">
                <i class="fas fa-save me-2"></i>Save Category
            </button>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Categories
            </a>
        </div>
    </form>
</div>

<style>
/* Estilos adicionales para mejorar el formulario */
.form-control {
    background-color: #2d3748;
    border: 1px solid #4a5568;
    color: #fff;
}

.form-control:focus {
    background-color: #2d3748;
    border-color: #ffc107;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
    color: #fff;
}

.form-control::placeholder {
    color: #a0aec0;
}

.btn-warning:hover {
    background-color: #e0a800;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(255, 193, 7, 0.3);
}

.btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
}

.btn-secondary:hover {
    background-color: #5a6268;
    transform: translateY(-2px);
}
</style>
@endsection