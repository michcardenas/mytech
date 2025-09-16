@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-dark text-white">
                    <h1 class="h3 mb-0 text-center">
                        <i class="fas fa-file-contract me-2"></i>
                        Terms & Conditions
                    </h1>
                </div>
                <div class="card-body p-5">
                    
                    <div class="text-center mb-5">
                        <p class="lead">Welcome to Meatology. By placing an order with us, you agree to the following terms and conditions.</p>
                        <p class="text-muted">Last updated: {{ date('F Y') }}</p>
                    </div>

                    <!-- ACCEPTANCE OF TERMS -->
                    <section class="mb-5">
                        <h2 class="h4 text-dark mb-3">
                            <i class="fas fa-handshake me-2"></i>
                            Acceptance of Terms
                        </h2>
                        <div class="card bg-light">
                            <div class="card-body">
                                <p class="mb-0">By accessing our website, placing an order, or using our services, you acknowledge that you have read, understood, and agree to be bound by these Terms & Conditions and all applicable laws and regulations.</p>
                            </div>
                        </div>
                    </section>

                    <!-- PRODUCT INFORMATION -->
                    <section class="mb-5">
                        <h2 class="h4 text-dark mb-3">
                            <i class="fas fa-box me-2"></i>
                            Product Information & Quality
                        </h2>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex align-items-start">
                                <i class="fas fa-leaf text-success me-3 mt-1"></i>
                                <div>
                                    <strong>Premium Quality:</strong> We provide premium grass-fed beef cuts, ethically sourced and delivered with care.
                                </div>
                            </li>
                            <li class="list-group-item d-flex align-items-start">
                                <i class="fas fa-certificate text-success me-3 mt-1"></i>
                                <div>
                                    <strong>Certifications:</strong> Our products are Certified Humane® and 100% Grass-Fed.
                                </div>
                            </li>
                            <li class="list-group-item d-flex align-items-start">
                                <i class="fas fa-thermometer-half text-info me-3 mt-1"></i>
                                <div>
                                    <strong>Perishable Products:</strong> Due to the perishable nature of our products, special handling and delivery requirements apply.
                                </div>
                            </li>
                        </ul>
                    </section>

                    <!-- ORDER TERMS -->
                    <section class="mb-5">
                        <h2 class="h4 text-dark mb-3">
                            <i class="fas fa-shopping-cart me-2"></i>
                            Order Terms
                        </h2>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="card bg-success bg-opacity-10 border-success h-100">
                                    <div class="card-body">
                                        <h5 class="text-success">
                                            <i class="fas fa-edit me-2"></i>
                                            Order Modification
                                        </h5>
                                        <p class="mb-0">Orders can be canceled or modified before they are processed for shipping. Once shipped, orders cannot be canceled.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card bg-warning bg-opacity-10 border-warning h-100">
                                    <div class="card-body">
                                        <h5 class="text-warning">
                                            <i class="fas fa-map-marker-alt me-2"></i>
                                            Address Accuracy
                                        </h5>
                                        <p class="mb-0">Please check all addresses carefully. Damage due to incorrect or incomplete shipping addresses voids our guarantee.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- DELIVERY TERMS -->
                    <section class="mb-5">
                        <h2 class="h4 text-dark mb-3">
                            <i class="fas fa-truck me-2"></i>
                            Delivery Terms
                        </h2>
                        <div class="alert alert-info">
                            <h5 class="alert-heading">
                                <i class="fas fa-clock me-2"></i>
                                Delivery Schedule
                            </h5>
                            <ul class="mb-2">
                                <li><strong>Florida:</strong> UPS Ground delivery Monday to Thursday (1-2 business days)</li>
                                <li><strong>All Other States:</strong> UPS 2nd Day Air Monday to Wednesday (48 hours typically)</li>
                                <li><strong>Same Day Delivery:</strong> Available for select ZIP codes in Palm Beach County</li>
                            </ul>
                            <p class="mb-0">You will receive a confirmation email with tracking details once your order has shipped.</p>
                        </div>
                    </section>

                    <!-- CUSTOMER RESPONSIBILITIES -->
                    <section class="mb-5">
                        <h2 class="h4 text-dark mb-3">
                            <i class="fas fa-user-check me-2"></i>
                            Customer Responsibilities
                        </h2>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="card bg-light h-100 text-center">
                                    <div class="card-body">
                                        <i class="fas fa-search fa-2x text-primary mb-2"></i>
                                        <h6>Order Inspection</h6>
                                        <p class="small mb-0">Inspect your order upon delivery and report any issues immediately</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card bg-light h-100 text-center">
                                    <div class="card-body">
                                        <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                                        <h6>Timely Reporting</h6>
                                        <p class="small mb-0">Report quality issues within 24 hours of delivery</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card bg-light h-100 text-center">
                                    <div class="card-body">
                                        <i class="fas fa-camera fa-2x text-info mb-2"></i>
                                        <h6>Documentation</h6>
                                        <p class="small mb-0">Provide photos and order details for any claims</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- LIMITATIONS OF LIABILITY -->
                    <section class="mb-5">
                        <h2 class="h4 text-danger mb-3">
                            <i class="fas fa-shield-alt me-2"></i>
                            Limitations of Liability
                        </h2>
                        <div class="card border-danger">
                            <div class="card-body">
                                <ul class="mb-0">
                                    <li>Meatology is not responsible for spoilage due to missed deliveries</li>
                                    <li>We are not responsible for delays caused by adverse weather conditions</li>
                                    <li>Damage due to incorrect shipping addresses voids our guarantee</li>
                                    <li>UPS may not leave packages in open areas (apartment or condo complexes)</li>
                                    <li>We do not ship internationally due to customs laws</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- POLICY REFERENCES -->
                    <section class="mb-5">
                        <h2 class="h4 text-dark mb-3">
                            <i class="fas fa-link me-2"></i>
                            Related Policies
                        </h2>
                        <p class="mb-3">For detailed information about specific policies, please review:</p>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('shipping.policy') }}" class="btn btn-outline-success btn-lg w-100">
                                    <i class="fas fa-shipping-fast me-2"></i>
                                    Shipping Policy
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('return.policy') }}" class="btn btn-outline-warning btn-lg w-100">
                                    <i class="fas fa-undo-alt me-2"></i>
                                    Return Policy
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('refund.policy') }}" class="btn btn-outline-primary btn-lg w-100">
                                    <i class="fas fa-money-bill-wave me-2"></i>
                                    Refund Policy
                                </a>
                            </div>
                        </div>
                    </section>

                    <!-- CONTACT AND CHANGES -->
                    <section class="mb-5">
                        <h2 class="h4 text-dark mb-3">
                            <i class="fas fa-edit me-2"></i>
                            Changes to Terms
                        </h2>
                        <div class="alert alert-secondary">
                            <p class="mb-2">Meatology reserves the right to update these Terms & Conditions at any time. Changes will be posted on this page with an updated revision date.</p>
                            <p class="mb-0"><strong>Note:</strong> This policy is subject to change. Please check our website for the most up-to-date information.</p>
                        </div>
                    </section>

                    <!-- CONTACT SECTION -->
                    <section class="text-center bg-dark text-white rounded p-4">
                        <h2 class="h4 mb-3">
                            <i class="fas fa-phone-alt me-2"></i>
                            Questions About Our Terms?
                        </h2>
                        <p class="mb-4">If you have any questions about these Terms & Conditions, please don't hesitate to contact us.</p>
                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <a href="mailto:sales@meatology.us" class="btn btn-light btn-lg me-3 mb-2">
                                    <i class="fas fa-envelope me-2"></i>
                                    sales@meatology.us
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="tel:+13058420234" class="btn btn-outline-light btn-lg mb-2">
                                    <i class="fas fa-phone me-2"></i>
                                    (305) 842-0234
                                </a>
                            </div>
                        </div>
                        <p class="mt-3 mb-0 small">We're here to ensure your Meatology experience is exceptional.</p>
                    </section>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection