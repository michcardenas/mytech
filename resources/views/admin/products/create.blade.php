@extends('layouts.app_admin')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Nuevo producto</h2>

    @include('admin.products._flash')

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @include('admin.products._form', ['product' => null])
    </form>
</div>
@endsection
