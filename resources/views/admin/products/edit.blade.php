@extends('layouts.app_admin')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Editar producto</h2>

    @include('admin.products._flash')

    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('admin.products._form', ['product' => $product])
    </form>
</div>
@endsection
