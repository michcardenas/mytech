@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-success text-white">
                    <h1 class="h3 mb-0 text-center">
                        <i class="fas fa-shipping-fast me-2"></i>
                        Shipping Policy
                    </h1>
                </div>
                <div class="card-body p-5">
                    
                    <!-- FREE SHIPPING RATES -->
                    <section class="mb-5">
                        <h2 class="h4 text-success mb-3">
                            <i class="fas fa-truck me-2"></i>
                            Free Shipping Rates
                        </h2>
                        <p class="mb-3">Shipping fees are calculated based on weight and destination. Free shipping may apply for orders over $100 or other posted amounts, check website for promotions at checkout.</p>
                        
                        <div class="alert alert-info">
                            <h5 class="alert-heading">
                                <i class="fas fa-clock me-2"></i>
                                Same Day Rush Delivery Available
                            </h5>
                            <p class="mb-0">If you live in ZIP Codes:</p>
                            <ul class="mt-2 mb-2">
                                <li><strong>33411</strong> (Royal Palm Beach, West Palm Beach)</li>
                                <li><strong>33413</strong> (Greenacres, West Palm Beach)</li>
                                <li><strong>33449</strong> (Wellington, Lake Worth)</li>
                                <li><strong>33467</strong> (Greenacres, Lake Worth)</li>
                                <li><strong>33470</strong> (Loxahatchee)</li>
                            </ul>
                            <p class="mb-0">Contact us via email at <a href="mailto:sales@meatology.us">sales@meatology.us</a> or phone at <a href="tel:+13058420234">(305) 842-0234</a> about same day rush delivery.</p>
                        </div>
                    </section>

                    <!-- NATIONAL SHIPPING -->
                    <section class="mb-5">
                        <h2 class="h4 text-success mb-3">
                            <i class="fas fa-map-marked-alt me-2"></i>
                            National Shipping
                        </h2>
                        <p class="mb-3">We are based out of Florida. We deliver to Palm Beach, Miami-Dade & Broward County usually within 1-2 business days of receiving your order. You will receive a confirmation email with tracking details once your order has shipped.</p>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="card bg-light h-100">
                                    <div class="card-body">
                                        <h5 class="text-warning">
                                            <i class="fas fa-map-marker-alt me-2"></i>
                                            Within Florida
                                        </h5>
                                        <p class="mb-0">We deliver anywhere in Florida via UPS Ground from <strong>Monday to Thursday</strong>. Standard shipping rates and free shipping rates to Florida include Ground Shipping which means an order takes typically <strong>1-2 business days</strong> to arrive to its destination.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card bg-light h-100">
                                    <div class="card-body">
                                        <h5 class="text-primary">
                                            <i class="fas fa-plane me-2"></i>
                                            All Other States
                                        </h5>
                                        <p class="mb-0">We deliver to every State via UPS from <strong>Monday to Wednesday</strong>. Standard shipping rates and free shipping rates include 2nd Day Air shipping and arrive, typically, <strong>48 hours</strong> after the order is delivered. If you place your order by Wednesday after 4:00pm and wish to receive it for the weekend, we can schedule Next Day Air delivery for an additional cost. If not, it will be shipped next Monday.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- IMPORTANT NOTE -->
                    <section class="mb-5">
                        <div class="alert alert-warning">
                            <h5 class="alert-heading">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Please Note
                            </h5>
                            <p class="mb-2">Taking into account the delivery company might experience delays, all orders are packed in insulated coolers with dry ice and/or enough ice packs to maintain your items safe up to 48 hours.</p>
                            <p class="mb-2">Items may begin to thaw during transit, as long as items arrive cold to the touch we consider them safe for consumption.</p>
                            <p class="mb-0">If you are not available to receive your order, our courier may leave it in a safe, cool location or attempt redelivery. <strong>Meatology is not responsible for spoilage due to missed deliveries.</strong></p>
                        </div>
                    </section>

                    <!-- SHIPPING RESTRICTIONS -->
                    <section class="mb-5">
                        <h2 class="h4 text-danger mb-3">
                            <i class="fas fa-ban me-2"></i>
                            Shipping Restrictions
                        </h2>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex align-items-start">
                                <i class="fas fa-building text-muted me-2 mt-1"></i>
                                <span>UPS might not leave packages in open areas (apartment or condo complexes).</span>
                            </li>
                            <li class="list-group-item d-flex align-items-start">
                                <i class="fas fa-globe text-muted me-2 mt-1"></i>
                                <span>We do not ship Internationally due to customs laws.</span>
                            </li>
                            <li class="list-group-item d-flex align-items-start">
                                <i class="fas fa-map-pin text-muted me-2 mt-1"></i>
                                <span>Please double-check addresses. We are not responsible for incorrect or incomplete shipping addresses.</span>
                            </li>
                            <li class="list-group-item d-flex align-items-start">
                                <i class="fas fa-cloud-rain text-muted me-2 mt-1"></i>
                                <span>Inclement weather and other unforeseen circumstances may delay your shipment. We are not responsible for shipment delays caused by adverse or unpredictable weather conditions.</span>
                            </li>
                        </ul>
                    </section>

                    <!-- CONTACT -->
                    <section class="text-center">
                        <h2 class="h4 text-success mb-3">
                            <i class="fas fa-phone-alt me-2"></i>
                            Contact Us
                        </h2>
                        <p class="mb-3">For any delivery or return inquiries, please reach out to our customer service team:</p>
                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <a href="mailto:sales@meatology.us" class="btn btn-outline-success btn-lg me-3 mb-2">
                                    <i class="fas fa-envelope me-2"></i>
                                    sales@meatology.us
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="tel:+13058420234" class="btn btn-outline-success btn-lg mb-2">
                                    <i class="fas fa-phone me-2"></i>
                                    (305) 842-0234
                                </a>
                            </div>
                        </div>
                        <p class="text-muted mt-3 small">We're here to ensure your Meatology experience is exceptional.</p>
                        <p class="text-muted small"><strong>Note:</strong> This policy is subject to change. Please check our website for the most up-to-date information.</p>
                    </section>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection