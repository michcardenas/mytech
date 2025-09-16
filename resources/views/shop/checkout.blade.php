{{-- Vista: resources/views/shop/checkout.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h2>🛒 Checkout</h2>
            
            <div class="row">
                <!-- Información del pedido -->
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>📦 Order Details</h5>
                        </div>
                        <div class="card-body">
                            @foreach($cartItems as $item)
                                <div class="row align-items-center mb-3 pb-3 border-bottom">
                                    <div class="col-md-2">
                                        <img src="{{ $item->options->image ? Storage::url($item->options->image) : asset('images/placeholder.jpg') }}" 
                                             class="img-fluid rounded" alt="{{ $item->name }}">
                                    </div>
                                    <div class="col-md-6">
                                        <h6>{{ $item->name }}</h6>
                                        <small class="text-muted">{{ $item->options->category_name }}</small>
                                        <br>
                                        <small class="text-muted">Qty: {{ $item->qty }}</small>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <strong>${{ number_format($item->total, 0) }}</strong>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- 🔴 Nueva sección de ubicación de envío -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>🚚 Shipping Location</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">State *</label>
                                    <select id="shipping-country" name="shipping_country" class="form-select" required>
                                        <option value="">-- Select Country --</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}" data-cities="{{ $country->cities->toJson() }}">
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">City</label>
                                    <select id="shipping-city" name="shipping_city" class="form-select">
                                        <option value="">-- Select Country First --</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-3">
                                <small class="text-muted">
                                    📍 Shipping costs and taxes will be calculated based on your location
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Información de envío/contacto -->
                    <div class="card">
                        <div class="card-header">
                            <h5>📍 Contact Information</h5>
                        </div>
                        <div class="card-body">
                            @if($isAuthenticated)
                                <!-- Usuario autenticado -->
                                <div class="alert alert-info">
                                    <h6>Welcome back, {{ $user->name }}!</h6>
                                    <p class="mb-0">Email: {{ $user->email }}</p>
                                </div>
                                
                                <form id="checkoutForm" action="{{ route('order.process') }}" method="POST">
                                    @csrf
                                    <input type="hidden" id="final-country" name="country_id">
                                    <input type="hidden" id="final-city" name="city_id">
                                    <input type="hidden" id="final-total" name="total">
                                    <input type="hidden" id="final-tax" name="tax">
                                    <input type="hidden" id="final-shipping" name="shipping">
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label">Address *</label>
                                            <textarea name="address" class="form-control" rows="3" placeholder="Enter your complete address" required>{{ old('address', $user->address ?? '') }}</textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Phone *</label>
                                            <input type="tel" name="phone" class="form-control" value="{{ old('phone', $user->phone ?? '') }}" placeholder="Your phone number" required>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <label class="form-label">Special Instructions (Optional)</label>
                                            <textarea name="notes" class="form-control" rows="2" placeholder="Any special instructions for your order">{{ old('notes') }}</textarea>
                                        </div>
                                    </div>
                                </form>
                            @else
                                <!-- Usuario no autenticado -->
                                <div class="alert alert-warning">
                                    <h6>Guest Checkout</h6>
                                    <p class="mb-0">Please provide your contact and shipping information</p>
                                </div>
                                
                                <form id="checkoutForm" action="{{ route('order.process') }}" method="POST">
                                    @csrf
                                    <input type="hidden" id="final-country" name="country_id">
                                    <input type="hidden" id="final-city" name="city_id">
                                    <input type="hidden" id="final-total" name="total">
                                    <input type="hidden" id="final-tax" name="tax">
                                    <input type="hidden" id="final-shipping" name="shipping">
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label">Full Name *</label>
                                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Your full name" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Email *</label>
                                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="your.email@example.com" required>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Phone *</label>
                                            <input type="tel" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="Your phone number" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Address *</label>
                                            <textarea name="address" class="form-control" rows="3" placeholder="Enter your complete address" required>{{ old('address') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <label class="form-label">Special Instructions (Optional)</label>
                                            <textarea name="notes" class="form-control" rows="2" placeholder="Any special instructions for your order">{{ old('notes') }}</textarea>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <small class="text-muted">
                                            <a href="{{ route('login') }}">Already have an account? Login here</a> | 
                                            <a href="{{ route('register') }}">Create account for faster checkout</a>
                                        </small>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Resumen del pedido -->
                <div class="col-md-4">
                    <div class="card sticky-top">
                        <div class="card-header">
                            <h5>💰 Order Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span id="display-subtotal">${{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax:</span>
                                <span id="display-tax">$0.00</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Shipping:</span>
                                <span id="display-shipping">Select location</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Total:</strong>
                                <strong id="display-total">${{ number_format($subtotal, 2) }}</strong>
                            </div>
                            
                            <div id="location-warning" class="alert alert-warning" style="display: none;">
                                📍 Please select shipping location to see final costs
                            </div>
                            
                            <button type="submit" form="checkoutForm" class="btn btn-success w-100 mb-2" id="place-order-btn" disabled>
                                💳 Place Order
                            </button>
                            
                            <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary w-100">
                                ← Back to Cart
                            </a>
                            
                            <div class="mt-3 text-center">
                                <small class="text-muted">
                                    🔒 Secure checkout <br>
                                    💚 Fresh meat guarantee
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const countrySelect = document.getElementById('shipping-country');
    const citySelect = document.getElementById('shipping-city');
    const placeOrderBtn = document.getElementById('place-order-btn');
    const locationWarning = document.getElementById('location-warning');
    
    // Mostrar advertencia inicialmente
    locationWarning.style.display = 'block';
    
    // Manejar cambio de país
    countrySelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const cities = selectedOption.dataset.cities ? JSON.parse(selectedOption.dataset.cities) : [];
        
        // Limpiar ciudades
        citySelect.innerHTML = '<option value="">-- Select City --</option>';
        
        // Agregar ciudades
        cities.forEach(city => {
            const option = document.createElement('option');
            option.value = city.id;
            option.textContent = city.name;
            citySelect.appendChild(option);
        });
        
        // Calcular costos si hay país seleccionado
        if (this.value) {
            calculateCosts();
        } else {
            resetCosts();
        }
    });
    
    // Manejar cambio de ciudad
    citySelect.addEventListener('change', function() {
        if (countrySelect.value) {
            calculateCosts();
        }
    });
    
    function calculateCosts() {
        const countryId = countrySelect.value;
        const cityId = citySelect.value;
        
        if (!countryId) return;
        
        // Mostrar loading
        document.getElementById('display-tax').textContent = 'Calculating...';
        document.getElementById('display-shipping').textContent = 'Calculating...';
        document.getElementById('display-total').textContent = 'Calculating...';
        
        fetch('{{ route("checkout.calculate") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                country_id: countryId,
                city_id: cityId
            })
        })
        .then(response => response.json())
        .then(data => {
            // Actualizar display
            document.getElementById('display-tax').textContent = '$' + data.tax;
            document.getElementById('display-shipping').textContent = '$' + data.shipping;
            document.getElementById('display-total').textContent = '$' + data.total;
            
            // Actualizar campos ocultos
            document.getElementById('final-country').value = countryId;
            document.getElementById('final-city').value = cityId;
            document.getElementById('final-total').value = data.total_raw;
            document.getElementById('final-tax').value = data.tax_raw;
            document.getElementById('final-shipping').value = data.shipping_raw;
            
            // Habilitar botón y ocultar advertencia
            placeOrderBtn.disabled = false;
            locationWarning.style.display = 'none';
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error calculating shipping costs. Please try again.');
        });
    }
    
    function resetCosts() {
        document.getElementById('display-tax').textContent = '$0.00';
        document.getElementById('display-shipping').textContent = 'Select location';
        document.getElementById('display-total').textContent = '{{ "$" . number_format($subtotal, 2) }}';
        
        placeOrderBtn.disabled = true;
        locationWarning.style.display = 'block';
    }
});
</script>
@endsection