@extends('layouts.app_admin')

@section('content')
<div class="container py-4">
    <h2 class="text-light">🛒 Welcome {{ $user->name }}</h2>
    <p class="text-muted">Email: {{ $user->email }}</p>

    <hr class="border-secondary">

    <h4 class="text-light">📦 Your Orders</h4>

    @if($orders->isEmpty())
        <div class="alert alert-info">You don’t have any registered orders yet.</div>
    @else
        <div class="table-responsive mt-3">
            <table class="table table-dark table-bordered table-striped align-middle text-light">
                <thead class="table-secondary text-dark">
                    <tr>
                        <th># Order</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Payment</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->order_number }}</td>
                        <td>{{ $order->created_at->format('d/m/Y') }}</td>
                        <td>${{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        <td>{{ ucfirst($order->status) }}</td>
                        <td>{{ ucfirst($order->payment_status) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
