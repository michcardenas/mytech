@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-warning text-dark">
                    <h1 class="h3 mb-0 text-center">
                        <i class="fas fa-undo-alt me-2"></i>
                        Return Policy
                    </h1>
                </div>
                <div class="card-body p-5">
                    
                    <div class="text-center mb-4">
                        <p class="lead">At Meatology, we are committed to delivering high-quality, fresh meat products straight to your door. Our delivery policy ensures a seamless and reliable experience for our customers.</p>
                    </div>

                    <!-- INSPECTION UPON DELIVERY -->
                    <section class="mb-5">
                        <h2 class="h4 text-warning mb-3">
                            <i class="fas fa-search me-2"></i>
                            Inspection Upon Delivery
                        </h2>
                        <div class="alert alert-info">
                            <p class="mb-0">Please inspect your order upon delivery. If your package arrives damaged or compromised, contact us immediately at <a href="mailto:sales@meatology.us" class="alert-link">sales@meatology.us</a> with photos of the issue.</p>
                        </div>
                    </section>

                    <!-- QUALITY ISSUES -->
                    <section class="mb-5">
                        <h2 class="h4 text-warning mb-3">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            Quality Issues
                        </h2>
                        <div class="card bg-light">
                            <div class="card-body">
                                <p class="mb-2">If you receive products that are damaged, spoiled, or do not meet our quality standards, please contact us <strong>within 24 hours of delivery</strong>.</p>
                                <p class="mb-0">Provide your order number and photos of the issue to <a href="mailto:sales@meatology.us">sales@meatology.us</a>.</p>
                            </div>
                        </div>
                    </section>

                    <!-- IMPORTANT POLICY NOTES -->
                    <section class="mb-5">
                        <h2 class="h4 text-danger mb-3">
                            <i class="fas fa-ban me-2"></i>
                            Important Policy Notes
                        </h2>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex align-items-start border-start border-danger border-3">
                                <i class="fas fa-times-circle text-danger me-3 mt-1"></i>
                                <div>
                                    <strong>No Returns Accepted:</strong> Due to the perishable nature of the product, we cannot accept returned merchandise.
                                </div>
                            </li>
                            <li class="list-group-item d-flex align-items-start border-start border-danger border-3">
                                <i class="fas fa-credit-card text-danger me-3 mt-1"></i>
                                <div>
                                    <strong>No Refunds for Refused Deliveries:</strong> We will not issue a refund to anyone's credit card who refuses a delivery or in any way attempts to return a shipment.
                                </div>
                            </li>
                        </ul>
                    </section>

                    <!-- INCORRECT ITEMS -->
                    <section class="mb-5">
                        <h2 class="h4 text-success mb-3">
                            <i class="fas fa-exchange-alt me-2"></i>
                            Incorrect Items
                        </h2>
                        <div class="card bg-success bg-opacity-10 border-success">
                            <div class="card-body">
                                <p class="mb-2">If you receive an incorrect item, contact us <strong>within 24 hours</strong>.</p>
                                <p class="mb-0">We'll arrange for the correct product to be shipped and provide a prepaid return label for the incorrect item.</p>
                            </div>
                        </div>
                    </section>

                    <!-- ADDRESS VERIFICATION -->
                    <section class="mb-5">
                        <h2 class="h4 text-info mb-3">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            Address Verification
                        </h2>
                        <div class="alert alert-warning">
                            <h5 class="alert-heading">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Please Check All Addresses Carefully
                            </h5>
                            <p class="mb-0">Damage due to incorrect or incomplete shipping addresses voids our guarantee so please be sure to double-check addresses.</p>
                        </div>
                    </section>

                    <!-- ORDER CANCELLATION -->
                    <section class="mb-5">
                        <h2 class="h4 text-primary mb-3">
                            <i class="fas fa-times me-2"></i>
                            Order Cancellation
                        </h2>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="card bg-light h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-check-circle text-success fa-2x mb-2"></i>
                                        <h5 class="text-success">Before Processing</h5>
                                        <p class="mb-0">Orders can be canceled before they are processed for shipping.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card bg-light h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-times-circle text-danger fa-2x mb-2"></i>
                                        <h5 class="text-danger">After Shipping</h5>
                                        <p class="mb-0">Once shipped, orders cannot be canceled but may qualify for a return under the above conditions.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- CONTACT SECTION -->
                    <section class="text-center">
                        <h2 class="h4 text-warning mb-3">
                            <i class="fas fa-headset me-2"></i>
                            Need Help?
                        </h2>
                        <p class="mb-3">If you have any questions about our return policy or need assistance with your order:</p>
                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <a href="mailto:sales@meatology.us" class="btn btn-outline-warning btn-lg me-3 mb-2">
                                    <i class="fas fa-envelope me-2"></i>
                                    sales@meatology.us
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="tel:+13058420234" class="btn btn-outline-warning btn-lg mb-2">
                                    <i class="fas fa-phone me-2"></i>
                                    (305) 842-0234
                                </a>
                            </div>
                        </div>
                        <p class="text-muted mt-3 small">Our customer service team is here to help resolve any issues quickly and fairly.</p>
                    </section>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection