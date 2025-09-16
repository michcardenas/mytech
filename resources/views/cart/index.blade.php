@extends('layouts.app')

@section('content')
<div class="container">
    <h2>🛒 Your Shopping Cart</h2>
    
    @if(Cart::count() > 0)
        <div class="row">
            <div class="col-md-8">
                @foreach(Cart::content() as $item)
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <img src="{{ $item->options->image ? Storage::url($item->options->image) : asset('images/placeholder.jpg') }}" 
                                         class="img-fluid rounded" alt="{{ $item->name }}">
                                </div>
                                <div class="col-md-4">
                                    <h5>{{ $item->name }}</h5>
                                    <small class="text-muted d-block">{{ $item->options->category_name }}</small>

                                    <small class="d-block text-muted">
                                        Base price: ${{ number_format($item->options->base_price, 0, ',', '.') }}
                                    </small>
                                    <small class="d-block text-muted">
                                        Interest: ${{ number_format($item->options->interest, 0, ',', '.') }}
                                    </small>
                                    <small class="d-block fw-bold">
                                        Unit total: ${{ number_format($item->price, 0, ',', '.') }}
                                    </small>
                                </div>

                                <div class="col-md-2">
                                    <strong>${{ number_format($item->price, 0) }}</strong>
                                </div>
                                <div class="col-md-2">
                                    <form action="{{ route('cart.update', $item->rowId) }}" method="POST" class="d-flex">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="qty" value="{{ $item->qty }}" 
                                               min="0" max="{{ $item->options->stock }}" 
                                               class="form-control form-control-sm me-2" style="width: 70px;"
                                               onchange="this.form.submit()">
                                    </form>
                                </div>
                                <div class="col-md-2 text-end">
                                    <strong>${{ number_format($item->total, 0) }}</strong>
                                    <form action="{{ route('cart.remove', $item->rowId) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger ms-2">🗑️</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <span>Subtotal:</span>
                            <span>${{ Cart::subtotal() }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Tax:</span>
                            <span>${{ Cart::tax() }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Total:</strong>
                            <strong>${{ Cart::total() }}</strong>
                        </div>
                        
<a href="{{ route('checkout.index') }}" class="btn btn-success w-100 mt-3">Proceed to Checkout</a>                        
                        <form action="{{ route('cart.clear') }}" method="POST" class="mt-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-secondary w-100" 
                                    onclick="return confirm('Are you sure you want to empty the cart?')">
                                Empty Cart
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <h4>Your cart is empty</h4>
            <p>Add some delicious products!</p>
            <a href="{{ route('shop.index') }}" class="btn btn-primary">Continue Shopping</a>
        </div>
    @endif
</div>
@endsection
