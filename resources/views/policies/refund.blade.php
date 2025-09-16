@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white">
                    <h1 class="h3 mb-0 text-center">
                        <i class="fas fa-money-bill-wave me-2"></i>
                        Refund Policy
                    </h1>
                </div>
                <div class="card-body p-5">
                    
                    <div class="text-center mb-5">
                        <div class="card bg-primary bg-opacity-10 border-primary">
                            <div class="card-body">
                                <h2 class="h4 text-primary mb-3">
                                    <i class="fas fa-heart me-2"></i>
                                    Customer Satisfaction Guarantee
                                </h2>
                                <p class="lead mb-0">If you're not completely satisfied with your order or have any issues with it, please contact our Customer Service Team and we'll take care of you.</p>
                            </div>
                        </div>
                    </div>

                    <!-- CONTACT OPTIONS -->
                    <section class="mb-5">
                        <h2 class="h4 text-primary mb-3">
                            <i class="fas fa-phone-alt me-2"></i>
                            How to Contact Us
                        </h2>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="card bg-light h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-phone fa-2x text-primary mb-3"></i>
                                        <h5 class="text-primary">By Phone</h5>
                                        <p class="mb-2">Call us directly to speak with our customer service team:</p>
                                        <a href="tel:+13058420234" class="btn btn-primary">
                                            <i class="fas fa-phone me-2"></i>
                                            +1 (305) 842-0234
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card bg-light h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-envelope fa-2x text-primary mb-3"></i>
                                        <h5 class="text-primary">By Email</h5>
                                        <p class="mb-2">Send us an email with your order details and concern:</p>
                                        <a href="mailto:sales@meatology.us" class="btn btn-primary">
                                            <i class="fas fa-envelope me-2"></i>
                                            sales@meatology.us
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- PHOTO REQUIREMENT -->
                    <section class="mb-5">
                        <h2 class="h4 text-warning mb-3">
                            <i class="fas fa-camera me-2"></i>
                            Photo Documentation Required
                        </h2>
                        <div class="alert alert-warning">
                            <h5 class="alert-heading">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Important Documentation
                            </h5>
                            <p class="mb-0"><strong>Photos are required to analyze your case and comply with our refund policy.</strong> Please include clear photos of any issues with your order when contacting us.</p>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-md-4 text-center mb-3">
                                <i class="fas fa-box-open fa-3x text-muted mb-2"></i>
                                <h6>Package Condition</h6>
                                <p class="text-muted small">Photos of the packaging upon arrival</p>
                            </div>
                            <div class="col-md-4 text-center mb-3">
                                <i class="fas fa-thermometer-half fa-3x text-muted mb-2"></i>
                                <h6>Product Temperature</h6>
                                <p class="text-muted small">Evidence of temperature issues</p>
                            </div>
                            <div class="col-md-4 text-center mb-3">
                                <i class="fas fa-search fa-3x text-muted mb-2"></i>
                                <h6>Product Quality</h6>
                                <p class="text-muted small">Clear images of any quality concerns</p>
                            </div>
                        </div>
                    </section>

                    <!-- REFUND PROCESS -->
                    <section class="mb-5">
                        <h2 class="h4 text-success mb-3">
                            <i class="fas fa-clipboard-list me-2"></i>
                            Our Refund Process
                        </h2>
                        <div class="card bg-success bg-opacity-10 border-success">
                            <div class="card-body">
                                <ol class="list-group list-group-numbered list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="ms-2 me-auto">
                                            <div class="fw-bold">Contact Our Team</div>
                                            Reach out via phone or email with your order details
                                        </div>
                                        <span class="badge bg-success rounded-pill">Step 1</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="ms-2 me-auto">
                                            <div class="fw-bold">Provide Documentation</div>
                                            Send clear photos and describe the issue
                                        </div>
                                        <span class="badge bg-success rounded-pill">Step 2</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="ms-2 me-auto">
                                            <div class="fw-bold">Case Review</div>
                                            Our team analyzes your case according to our policy
                                        </div>
                                        <span class="badge bg-success rounded-pill">Step 3</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="ms-2 me-auto">
                                            <div class="fw-bold">Resolution</div>
                                            We provide an appropriate solution or refund
                                        </div>
                                        <span class="badge bg-success rounded-pill">Step 4</span>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </section>

                    <!-- WHAT TO INCLUDE -->
                    <section class="mb-5">
                        <h2 class="h4 text-info mb-3">
                            <i class="fas fa-list-check me-2"></i>
                            What to Include When Contacting Us
                        </h2>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex align-items-center">
                                        <i class="fas fa-hashtag text-info me-2"></i>
                                        <strong>Order Number</strong>
                                    </li>
                                    <li class="list-group-item d-flex align-items-center">
                                        <i class="fas fa-calendar text-info me-2"></i>
                                        <strong>Order Date</strong>
                                    </li>
                                    <li class="list-group-item d-flex align-items-center">
                                        <i class="fas fa-truck text-info me-2"></i>
                                        <strong>Delivery Date</strong>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex align-items-center">
                                        <i class="fas fa-camera text-info me-2"></i>
                                        <strong>Clear Photos</strong>
                                    </li>
                                    <li class="list-group-item d-flex align-items-center">
                                        <i class="fas fa-comment text-info me-2"></i>
                                        <strong>Detailed Description</strong>
                                    </li>
                                    <li class="list-group-item d-flex align-items-center">
                                        <i class="fas fa-user text-info me-2"></i>
                                        <strong>Contact Information</strong>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- CTA SECTION -->
                    <section class="text-center bg-light rounded p-4">
                        <h2 class="h4 text-primary mb-3">
                            <i class="fas fa-headset me-2"></i>
                            Ready to Get Help?
                        </h2>
                        <p class="mb-4">Our customer service team is standing by to assist you with any refund requests or concerns about your order.</p>
                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <a href="mailto:sales@meatology.us" class="btn btn-primary btn-lg me-3 mb-2">
                                    <i class="fas fa-envelope me-2"></i>
                                    Email Us Now
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="tel:+13058420234" class="btn btn-outline-primary btn-lg mb-2">
                                    <i class="fas fa-phone me-2"></i>
                                    Call Us Now
                                </a>
                            </div>
                        </div>
                        <p class="text-muted mt-3 small">We're committed to making sure every Meatology customer is completely satisfied with their order.</p>
                    </section>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection