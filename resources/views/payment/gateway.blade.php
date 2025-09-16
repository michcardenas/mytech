@extends('layouts.app')

@section('title', 'Payment Gateway - Order #' . $order->order_number)

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    .payment-card {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        border: none;
    }
    .order-summary-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-left: 4px solid #011904;
    }
    .payment-form-card {
        border-left: 4px solid #011904;
    }
    #card-container {
        min-height: 100px; /* Importante: altura mínima como en la vista que funciona */
        padding: 0; /* Sin padding para que Square.js maneje el styling */
        border: none; /* Square.js maneja el border */
        border-radius: 0; /* Square.js maneja el border-radius */
        background-color: transparent; /* Square.js maneja el background */
    }
    .security-badges {
        background: linear-gradient(135deg, #011904 0%, #28a745 100%);
        color: white;
    }
    .total-amount {
        font-size: 1.5rem;
        font-weight: bold;
        color: #011904;
    }
    .btn-success {
        background-color: #011904;
        border-color: #011904;
    }
    .btn-success:hover {
        background-color: #023a07;
        border-color: #023a07;
    }
    .text-brand {
        color: #011904;
    }
    .bg-brand {
        background-color: #011904;
    }
    .card-title {
        color: #011904;
    }
    .order-header {
        color: #011904;
        font-weight: bold;
    }
</style>
@endpush

@section('content')
<div class="container mt-4">
    <div class="row">
        <!-- Order Summary -->
        <div class="col-lg-4 mb-4">
            <div class="card payment-card order-summary-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-receipt me-2"></i>Order Summary
                    </h5>
                </div>
                <div class="card-body">
                    <h6 class="order-header">Order #{{ $order->order_number }}</h6>
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax:</span>
                        <span>${{ number_format($order->tax_amount, 2) }}</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <span>Shipping:</span>
                        <span>${{ number_format($order->shipping_amount, 2) }}</span>
                    </div>
                    
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="fw-bold">Total to Pay:</span>
                        <span class="total-amount">${{ number_format($order->total_amount, 2) }}</span>
                    </div>
                    
                    <hr>
                    <h6 class="text-info">
                        <i class="fas fa-shipping-fast me-2"></i>Shipping Address:
                    </h6>
                    <address class="small text-muted mb-0">
                        <strong>{{ $order->customer_name }}</strong><br>
                        {{ $order->customer_address }}<br>
                        {{ $order->city->name ?? '' }}, {{ $order->country->name }}<br>
                        {{ $order->customer_phone }}
                    </address>
                </div>
            </div>
        </div>

        <!-- Payment Form -->
        <div class="col-lg-8">
            <div class="card payment-card payment-form-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-credit-card me-2"></i>Payment Information
                    </h5>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form id="payment-form" action="{{ route('payment.process', $order) }}" method="POST">
                        @csrf
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded">
                                    <h6 class="text-brand mb-2">
                                        <i class="fas fa-dollar-sign me-2"></i>Amount to Pay:
                                    </h6>
                                    <div class="total-amount">${{ number_format($order->total_amount, 2) }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-brand text-white rounded">
                                    <h6 class="mb-2">
                                        <i class="fas fa-info-circle me-2"></i>Information:
                                    </h6>
                                    <small>Secure payment processed by Square</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-credit-card me-2"></i>Card Information
                            </label>
                            <div id="card-container"></div>
                            <small class="form-text text-muted mt-2">
                                <i class="fas fa-lock me-1"></i>
                                Your data is protected with SSL encryption
                            </small>
                        </div>

                        <input type="hidden" id="source-id" name="source_id">
                        
                        <div class="d-grid gap-2 mb-3">
                            <button type="submit" class="btn btn-success btn-lg" id="payment-button">
                                <i class="fas fa-lock me-2"></i>
                                Complete Payment - ${{ number_format($order->total_amount, 2) }}
                            </button>
                        </div>
                        
                        <div class="text-center">
                            <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Shop
                            </a>
                        </div>
                    </form>

                    <!-- Security Badges -->
                    <div class="security-badges p-3 rounded mt-4 text-center">
                        <div class="row">
                            <div class="col-4">
                                <i class="fas fa-shield-alt fa-2x mb-2"></i>
                                <div class="small">Secure Payment</div>
                            </div>
                            <div class="col-4">
                                <i class="fas fa-lock fa-2x mb-2"></i>
                                <div class="small">SSL Encrypted</div>
                            </div>
                            <div class="col-4">
                                <i class="fas fa-certificate fa-2x mb-2"></i>
                                <div class="small">Square Verified</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Square.js Script - Cargado directamente en el HTML como en la vista que funciona -->
<script type="text/javascript" src="https://sandbox.web.squarecdn.com/v1/square.js"></script>

<script>
async function initializeCard(payments) {
    const card = await payments.card({
        style: {
            '.input-container': {
                borderRadius: '6px',
                borderColor: '#d1d5db'
            },
            '.input-container.is-focus': {
                borderColor: '#011904'
            },
            '.input-container.is-error': {
                borderColor: '#ef4444'
            }
        }
    });
    await card.attach('#card-container');
    return card;
}

document.addEventListener('DOMContentLoaded', async function () {
    // Debug: Verify credentials are loading
    console.log('Application ID:', '{{ config("square.application_id") }}');
    console.log('Location ID:', '{{ config("square.location_id") }}');
    
    if (!window.Square) {
        throw new Error('Square.js failed to load properly');
    }

    const payments = window.Square.payments('{{ config("square.application_id") }}', '{{ config("square.location_id") }}');
    
    let card;
    try {
        card = await initializeCard(payments);
    } catch (e) {
        console.error('Initializing Card failed', e);
        document.getElementById('card-container').innerHTML = '<div class="alert alert-danger">Failed to load payment form. Please refresh the page.</div>';
        return;
    }

    // Handle payment form submission
    document.getElementById('payment-form').addEventListener('submit', async function (e) {
        e.preventDefault();

        const button = document.getElementById('payment-button');
        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';

        try {
            console.log('Starting tokenization...');
            const result = await card.tokenize();
            console.log('Tokenization result:', result);
            
            if (result.status === 'OK') {
                console.log('Token generated:', result.token);
                document.getElementById('source-id').value = result.token;
                console.log('Form submitting with token...');
                e.target.submit();
            } else {
                console.error('Tokenization failed', result);
                let errorMsg = 'Error processing card information.';
                if (result.errors && result.errors.length > 0) {
                    errorMsg += ' ' + result.errors[0].message;
                }
                alert(errorMsg + ' Please check your details and try again.');
                button.disabled = false;
                button.innerHTML = originalText;
            }
        } catch (e) {
            console.error('Payment failed', e);
            alert('Payment processing failed. Please try again. Error: ' + e.message);
            button.disabled = false;
            button.innerHTML = originalText;
        }
    });
});
</script>
@endsection