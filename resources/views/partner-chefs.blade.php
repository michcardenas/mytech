@extends('layouts.app')

@section('title', 'Contacto - ElectraHome | Servicio Técnico Especializado')

@section('content')
<div class="contact-page">
    
    {{-- HERO SECTION - Dinámico --}}
    @if(isset($sectionsData['hero']) && $sectionsData['hero'])
        @php $heroSection = $sectionsData['hero']; @endphp
        <section class="contact-hero">
            <div class="hero-background">
                {{-- Imagen de fondo dinámica o logo por defecto --}}
                @if($heroSection->getImagesArray())
                    <img src="{{ Storage::url($heroSection->getImagesArray()[0]) }}" alt="Servicio Técnico ElectraHome" class="hero-bg-image">
                @else
                    <img src="{{ asset('images/logo.png') }}" alt="Servicio Técnico ElectraHome" class="hero-bg-image">
                @endif
                <div class="hero-overlay"></div>
            </div>
            <div class="container">
                <div class="row justify-content-center text-center">
                    <div class="col-lg-10">
                        <h1 class="hero-title">{{ $heroSection->title ?? 'Contáctanos' }}</h1>
                        <p class="hero-subtitle">{{ $heroSection->content ?? 'Servicio técnico especializado en línea blanca y electrodomésticos' }}</p>
                    </div>
                </div>
            </div>
        </section>
    @else
        {{-- Fallback si no hay sección hero --}}
        <section class="contact-hero">
            <div class="hero-background">
                <img src="{{ asset('images/logo.png') }}" alt="Servicio Técnico ElectraHome" class="hero-bg-image">
                <div class="hero-overlay"></div>
            </div>
            <div class="container">
                <div class="row justify-content-center text-center">
                    <div class="col-lg-10">
                        <h1 class="hero-title">Contáctanos</h1>
                        <p class="hero-subtitle">Servicio técnico especializado en línea blanca y electrodomésticos</p>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Main Content -->
    <section class="contact-content">
        <div class="container">
            <div class="row">
                <!-- Left Column - Information -->
                <div class="col-lg-6 mb-5">
                    <div class="info-content">
                        
                        {{-- INFO SECTION - Dinámico --}}
                        @if(isset($sectionsData['info']) && $sectionsData['info'])
                            @php $infoSection = $sectionsData['info']; @endphp
                            <h2 class="section-title">{{ $infoSection->title ?? '¿Necesitas ayuda con tus electrodomésticos?' }}</h2>
                            <p class="section-description">
                                {{ $infoSection->content ?? 'En ElectraHome somos especialistas en reparación, mantenimiento e instalación de línea blanca.' }}
                            </p>
                        @else
                            <h2 class="section-title">¿Necesitas ayuda con tus electrodomésticos?</h2>
                            <p class="section-description">
                                En ElectraHome somos especialistas en reparación, mantenimiento e instalación de línea blanca. 
                                También vendemos y reparamos electrodomésticos Oster. Contáctanos y recibe atención personalizada 
                                con técnicos certificados.
                            </p>
                        @endif
                        
                        {{-- SERVICES LIST - Dinámico --}}
                        <div class="services-list">
                            @if(isset($sectionsData['services']) && $sectionsData['services'])
                                @php $servicesSection = $sectionsData['services']; @endphp
                                
                                {{-- Servicio 1 --}}
                                <div class="service-item">
                                    <div class="service-icon">{{ $servicesSection->getCustomData('service_1_icon', '🔧') }}</div>
                                    <div class="service-text">
                                        <h4>{{ $servicesSection->getCustomData('service_1_title', 'Reparación Especializada') }}</h4>
                                        <p>{{ $servicesSection->getCustomData('service_1_desc', 'Lavadoras, secadoras, refrigeradoras, cocinas, microondas y más') }}</p>
                                    </div>
                                </div>
                                
                                {{-- Servicio 2 --}}
                                <div class="service-item">
                                    <div class="service-icon">{{ $servicesSection->getCustomData('service_2_icon', '🏠') }}</div>
                                    <div class="service-text">
                                        <h4>{{ $servicesSection->getCustomData('service_2_title', 'Servicio a Domicilio') }}</h4>
                                        <p>{{ $servicesSection->getCustomData('service_2_desc', 'Atendemos en toda la ciudad con horarios flexibles') }}</p>
                                    </div>
                                </div>
                                
                                {{-- Servicio 3 --}}
                                <div class="service-item">
                                    <div class="service-icon">{{ $servicesSection->getCustomData('service_3_icon', '⚡') }}</div>
                                    <div class="service-text">
                                        <h4>{{ $servicesSection->getCustomData('service_3_title', 'Electrodomésticos Oster') }}</h4>
                                        <p>{{ $servicesSection->getCustomData('service_3_desc', 'Venta y reparación de licuadoras, freidoras de aire') }}</p>
                                    </div>
                                </div>
                                
                                {{-- Servicio 4 --}}
                                <div class="service-item">
                                    <div class="service-icon">{{ $servicesSection->getCustomData('service_4_icon', '✅') }}</div>
                                    <div class="service-text">
                                        <h4>{{ $servicesSection->getCustomData('service_4_title', 'Garantía y Calidad') }}</h4>
                                        <p>{{ $servicesSection->getCustomData('service_4_desc', 'Todos nuestros trabajos incluyen garantía y repuestos originales') }}</p>
                                    </div>
                                </div>
                            @else
                                {{-- Fallback - servicios por defecto --}}
                                <div class="service-item">
                                    <div class="service-icon">🔧</div>
                                    <div class="service-text">
                                        <h4>Reparación Especializada</h4>
                                        <p>Lavadoras, secadoras, refrigeradoras, cocinas, microondas y más</p>
                                    </div>
                                </div>
                                
                                <div class="service-item">
                                    <div class="service-icon">🏠</div>
                                    <div class="service-text">
                                        <h4>Servicio a Domicilio</h4>
                                        <p>Atendemos en toda la ciudad de Quito con horarios flexibles</p>
                                    </div>
                                </div>
                                
                                <div class="service-item">
                                    <div class="service-icon">⚡</div>
                                    <div class="service-text">
                                        <h4>Electrodomésticos Oster</h4>
                                        <p>Venta y reparación de licuadoras, freidoras de aire, extractores</p>
                                    </div>
                                </div>
                                
                                <div class="service-item">
                                    <div class="service-icon">✅</div>
                                    <div class="service-text">
                                        <h4>Garantía y Calidad</h4>
                                        <p>Todos nuestros trabajos incluyen garantía y repuestos originales</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- CONTACT INFO CARDS - Dinámico --}}
                        <div class="contact-info-cards mt-5">
                            @if(isset($sectionsData['contact_info']) && $sectionsData['contact_info'])
                                @php $contactInfoSection = $sectionsData['contact_info']; @endphp
                                
                                {{-- WhatsApp --}}
                                <div class="info-card">
                                    <div class="info-icon">
                                        <i class="fab fa-whatsapp"></i>
                                    </div>
                                    <div class="info-details">
                                        <h5>WhatsApp</h5>
                                        <a href="{{ $contactInfoSection->getCustomData('whatsapp_link', 'https://wa.me/593987654321') }}" target="_blank">
                                            {{ $contactInfoSection->getCustomData('whatsapp_number', '+593 98 765 4321') }}
                                        </a>
                                    </div>
                                </div>

                                {{-- Teléfono --}}
                                <div class="info-card">
                                    <div class="info-icon">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <div class="info-details">
                                        <h5>Teléfono</h5>
                                        <a href="{{ $contactInfoSection->getCustomData('phone_link', 'tel:+59322345678') }}">
                                            {{ $contactInfoSection->getCustomData('phone_number', '+593 2 234 5678') }}
                                        </a>
                                    </div>
                                </div>

                                {{-- Email --}}
                                <div class="info-card">
                                    <div class="info-icon">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div class="info-details">
                                        <h5>Email</h5>
                                        <a href="{{ $contactInfoSection->getCustomData('email_link', 'mailto:info@electrahome.com') }}">
                                            {{ $contactInfoSection->getCustomData('email', 'info@electrahome.com') }}
                                        </a>
                                    </div>
                                </div>

                                {{-- Horarios --}}
                                <div class="info-card">
                                    <div class="info-icon">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="info-details">
                                        <h5>Horarios</h5>
                                        <span>
                                            {{ $contactInfoSection->getCustomData('schedule_weekdays', 'Lun-Vie: 8:00-18:00') }}<br>
                                            {{ $contactInfoSection->getCustomData('schedule_saturday', 'Sáb: 8:00-16:00') }}
                                        </span>
                                    </div>
                                </div>
                            @else
                                {{-- Fallback - información por defecto --}}
                                <div class="info-card">
                                    <div class="info-icon">
                                        <i class="fab fa-whatsapp"></i>
                                    </div>
                                    <div class="info-details">
                                        <h5>WhatsApp</h5>
                                        <a href="https://wa.me/593987654321" target="_blank">+593 98 765 4321</a>
                                    </div>
                                </div>

                                <div class="info-card">
                                    <div class="info-icon">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <div class="info-details">
                                        <h5>Teléfono</h5>
                                        <a href="tel:+59322345678">+593 2 234 5678</a>
                                    </div>
                                </div>

                                <div class="info-card">
                                    <div class="info-icon">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div class="info-details">
                                        <h5>Email</h5>
                                        <a href="mailto:info@electrahome.com">info@electrahome.com</a>
                                    </div>
                                </div>

                                <div class="info-card">
                                    <div class="info-icon">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="info-details">
                                        <h5>Horarios</h5>
                                        <span>Lun-Vie: 8:00-18:00<br>Sáb: 8:00-16:00</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Right Column - Form -->
                <div class="col-lg-6">
                    <div class="contact-form-container">
                        
                        {{-- FORM HEADER - Dinámico --}}
                        @if(isset($sectionsData['form_header']) && $sectionsData['form_header'])
                            @php $formHeaderSection = $sectionsData['form_header']; @endphp
                            <div class="form-header">
                                <h3>{{ $formHeaderSection->title ?? 'Solicita tu Servicio' }}</h3>
                                <p>{{ $formHeaderSection->content ?? '¿Tienes problemas con algún electrodoméstico? Completa el formulario y nos pondremos en contacto contigo.' }}</p>
                            </div>
                        @else
                            <div class="form-header">
                                <h3>Solicita tu Servicio</h3>
                                <p>¿Tienes problemas con algún electrodoméstico? Completa el formulario y nos pondremos en contacto contigo en menos de 24 horas para agendar tu servicio técnico.</p>
                            </div>
                        @endif
                        
                        @if(session('success'))
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        {{-- FORMULARIO - Mantiene toda la funcionalidad existente --}}
                        <form action="{{ route('contact.submit') }}" method="POST" class="contact-form">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label">Nombre*</label>
                                    <input type="text" 
                                           class="form-control @error('first_name') is-invalid @enderror" 
                                           id="first_name" 
                                           name="first_name" 
                                           value="{{ old('first_name') }}" 
                                           placeholder="Tu nombre" 
                                           required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label">Apellido*</label>
                                    <input type="text" 
                                           class="form-control @error('last_name') is-invalid @enderror" 
                                           id="last_name" 
                                           name="last_name" 
                                           value="{{ old('last_name') }}" 
                                           placeholder="Tu apellido" 
                                           required>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email*</label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           placeholder="tu@email.com" 
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Teléfono/WhatsApp*</label>
                                    <div class="phone-input">
                                        <select class="country-code" name="country_code">
                                            <option value="+593" selected>🇪🇨 +593</option>
                                            <option value="+1">🇺🇸 +1</option>
                                            <option value="+57">🇨🇴 +57</option>
                                            <option value="+51">🇵🇪 +51</option>
                                        </select>
                                        <input type="tel" 
                                               class="form-control phone-number @error('phone') is-invalid @enderror" 
                                               id="phone" 
                                               name="phone" 
                                               value="{{ old('phone') }}" 
                                               placeholder="98 765 4321" 
                                               required>
                                    </div>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="service_type" class="form-label">Tipo de Servicio*</label>
                                    <select class="form-control @error('service_type') is-invalid @enderror" 
                                            id="service_type" 
                                            name="service_type" 
                                            required>
                                        <option value="">Selecciona un servicio</option>
                                        <option value="reparacion" {{ old('service_type') == 'reparacion' ? 'selected' : '' }}>Reparación</option>
                                        <option value="mantenimiento" {{ old('service_type') == 'mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                                        <option value="instalacion" {{ old('service_type') == 'instalacion' ? 'selected' : '' }}>Instalación</option>
                                        <option value="venta" {{ old('service_type') == 'venta' ? 'selected' : '' }}>Compra de electrodomésticos</option>
                                        <option value="consulta" {{ old('service_type') == 'consulta' ? 'selected' : '' }}>Consulta general</option>
                                    </select>
                                    @error('service_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="appliance_type" class="form-label">Electrodoméstico</label>
                                    <select class="form-control @error('appliance_type') is-invalid @enderror" 
                                            id="appliance_type" 
                                            name="appliance_type">
                                        <option value="">Selecciona el electrodoméstico</option>
                                        <option value="lavadora" {{ old('appliance_type') == 'lavadora' ? 'selected' : '' }}>Lavadora</option>
                                        <option value="secadora" {{ old('appliance_type') == 'secadora' ? 'selected' : '' }}>Secadora</option>
                                        <option value="refrigeradora" {{ old('appliance_type') == 'refrigeradora' ? 'selected' : '' }}>Refrigeradora</option>
                                        <option value="cocina" {{ old('appliance_type') == 'cocina' ? 'selected' : '' }}>Cocina</option>
                                        <option value="microondas" {{ old('appliance_type') == 'microondas' ? 'selected' : '' }}>Microondas</option>
                                        <option value="calefon" {{ old('appliance_type') == 'calefon' ? 'selected' : '' }}>Calefón</option>
                                        <option value="aspiradora" {{ old('appliance_type') == 'aspiradora' ? 'selected' : '' }}>Aspiradora</option>
                                        <option value="lavavajillas" {{ old('appliance_type') == 'lavavajillas' ? 'selected' : '' }}>Lavavajillas</option>
                                        <option value="licuadora" {{ old('appliance_type') == 'licuadora' ? 'selected' : '' }}>Licuadora Oster</option>
                                        <option value="freidora" {{ old('appliance_type') == 'freidora' ? 'selected' : '' }}>Freidora de Aire Oster</option>
                                        <option value="extractor" {{ old('appliance_type') == 'extractor' ? 'selected' : '' }}>Extractor Oster</option>
                                        <option value="otro" {{ old('appliance_type') == 'otro' ? 'selected' : '' }}>Otro</option>
                                    </select>
                                    @error('appliance_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="address" class="form-label">Dirección en Quito*</label>
                                <input type="text" 
                                       class="form-control @error('address') is-invalid @enderror" 
                                       id="address" 
                                       name="address" 
                                       value="{{ old('address') }}" 
                                       placeholder="Av. Amazonas y Naciones Unidas, Sector La Carolina" 
                                       required>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="message" class="form-label">Describe el problema o consulta*</label>
                                <textarea class="form-control @error('message') is-invalid @enderror" 
                                          id="message" 
                                          name="message" 
                                          rows="4" 
                                          placeholder="Describe detalladamente el problema que tienes con tu electrodoméstico, marca, modelo si lo conoces, y síntomas específicos..." 
                                          required>{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="preferred_time" class="form-label">Horario Preferido</label>
                                <select class="form-control" id="preferred_time" name="preferred_time">
                                    <option value="">Selecciona un horario</option>
                                    <option value="morning" {{ old('preferred_time') == 'morning' ? 'selected' : '' }}>Mañana (8:00 - 12:00)</option>
                                    <option value="afternoon" {{ old('preferred_time') == 'afternoon' ? 'selected' : '' }}>Tarde (12:00 - 18:00)</option>
                                    <option value="flexible" {{ old('preferred_time') == 'flexible' ? 'selected' : '' }}>Horario flexible</option>
                                </select>
                            </div>
                            
                            <div class="whatsapp-option mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="whatsapp_contact" name="whatsapp_contact" value="1" {{ old('whatsapp_contact') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="whatsapp_contact">
                                        <i class="fab fa-whatsapp me-2"></i>
                                        Prefiero que me contacten por WhatsApp
                                    </label>
                                </div>
                            </div>
                            
                            <button type="submit" class="submit-btn">
                                <i class="fas fa-paper-plane me-2"></i>
                                Enviar Solicitud
                            </button>

                            {{-- Quick Contact dinámico --}}
                            @if(isset($sectionsData['contact_info']) && $sectionsData['contact_info'])
                                @php $contactInfoSection = $sectionsData['contact_info']; @endphp
                                <div class="quick-contact mt-4">
                                    <p class="text-center text-white-50">¿Necesitas atención inmediata?</p>
                                    <a href="{{ $contactInfoSection->getCustomData('whatsapp_link', 'https://wa.me/593987654321') }}" target="_blank" class="whatsapp-btn">
                                        <i class="fab fa-whatsapp me-2"></i>
                                        Escribir por WhatsApp
                                    </a>
                                </div>
                            @else
                                <div class="quick-contact mt-4">
                                    <p class="text-center text-white-50">¿Necesitas atención inmediata?</p>
                                    <a href="https://wa.me/593987654321" target="_blank" class="whatsapp-btn">
                                        <i class="fab fa-whatsapp me-2"></i>
                                        Escribir por WhatsApp
                                    </a>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<style>
.contact-page {
    font-family: 'Inter', sans-serif;
}

.contact-hero {
    position: relative;
    height: 50vh;
    min-height: 400px;
    display: flex;
    align-items: center;
    overflow: hidden;
}

.hero-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
}

.hero-bg-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(0, 169, 224, 0.8) 0%, rgba(0, 207, 180, 0.7) 100%);
}

.hero-title {
    font-size: 3rem;
    font-weight: 900;
    color: white;
    margin-bottom: 20px;
    text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    position: relative;
    z-index: 2;
}

.hero-subtitle {
    font-size: 1.2rem;
    color: rgba(255, 255, 255, 0.95);
    position: relative;
    z-index: 2;
    font-weight: 300;
}

.contact-content {
    padding: 80px 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.info-content {
    padding-right: 40px;
}

.section-title {
    font-size: 2.5rem;
    font-weight: 800;
    color: #101820;
    margin-bottom: 30px;
    line-height: 1.2;
}

.section-description {
    font-size: 1.1rem;
    line-height: 1.7;
    color: #555;
    margin-bottom: 40px;
}

.services-list {
    display: flex;
    flex-direction: column;
    gap: 30px;
}

.service-item {
    display: flex;
    align-items: flex-start;
    gap: 20px;
    padding: 20px;
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.service-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 169, 224, 0.15);
}

.service-icon {
    font-size: 2.5rem;
    flex-shrink: 0;
}

.service-text h4 {
    font-size: 1.3rem;
    font-weight: 700;
    color: #00A9E0;
    margin-bottom: 10px;
}

.service-text p {
    color: #666;
    line-height: 1.5;
    margin: 0;
}

.contact-info-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.info-card {
    background: white;
    padding: 25px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.info-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0, 169, 224, 0.15);
}

.info-icon {
    background: linear-gradient(135deg, #00A9E0, #00CFB4);
    color: white;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.info-details h5 {
    color: #101820;
    font-weight: 700;
    margin-bottom: 5px;
    font-size: 1rem;
}

.info-details a,
.info-details span {
    color: #666;
    text-decoration: none;
    font-size: 0.9rem;
    line-height: 1.4;
}

.info-details a:hover {
    color: #00A9E0;
}

.contact-form-container {
    background: linear-gradient(135deg, #101820 0%, #1a252f 100%);
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
}

.form-header {
    text-align: center;
    margin-bottom: 40px;
}

.form-header h3 {
    color: white;
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 20px;
}

.form-header p {
    color: rgba(255, 255, 255, 0.9);
    line-height: 1.6;
    font-size: 0.95rem;
}

.contact-form .form-label {
    color: #fff;
    font-weight: 600;
    margin-bottom: 8px;
    font-size: 0.9rem;
}

.contact-form .form-control {
    background: rgba(255, 255, 255, 0.1);
    border: 2px solid rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    color: white;
    padding: 12px 16px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.contact-form .form-control::placeholder {
    color: rgba(255, 255, 255, 0.6);
}

.contact-form .form-control:focus {
    background: rgba(255, 255, 255, 0.15);
    border-color: #00A9E0;
    box-shadow: 0 0 0 0.2rem rgba(0, 169, 224, 0.25);
    color: white;
}

.phone-input {
    display: flex;
    gap: 10px;
}

.country-code {
    background: rgba(255, 255, 255, 0.1);
    border: 2px solid rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    color: white;
    padding: 12px;
    width: 110px;
    font-size: 0.85rem;
}

.country-code option {
    background: #101820;
    color: white;
}

.phone-number {
    flex: 1;
}

.whatsapp-option {
    background: rgba(0, 169, 224, 0.1);
    border: 1px solid rgba(0, 169, 224, 0.3);
    border-radius: 10px;
    padding: 15px;
}

.form-check-label {
    color: rgba(255, 255, 255, 0.9);
    cursor: pointer;
}

.form-check-input:checked {
    background-color: #00A9E0;
    border-color: #00A9E0;
}

.submit-btn {
    width: 100%;
    background: linear-gradient(135deg, #00A9E0, #00CFB4);
    color: white;
    border: none;
    padding: 15px 30px;
    border-radius: 50px;
    font-weight: 700;
    font-size: 1rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
    box-shadow: 0 8px 25px rgba(0, 169, 224, 0.3);
}

.submit-btn:hover {
    background: linear-gradient(135deg, #00CFB4, #00A9E0);
    transform: translateY(-3px);
    box-shadow: 0 12px 35px rgba(0, 169, 224, 0.4);
}

.whatsapp-btn {
    display: block;
    width: 100%;
    background: #25d366;
    color: white;
    text-decoration: none;
    padding: 12px 20px;
    border-radius: 25px;
    text-align: center;
    font-weight: 600;
    transition: all 0.3s ease;
}

.whatsapp-btn:hover {
    background: #128c7e;
    color: white;
    transform: translateY(-2px);
}

.alert-success {
    background: rgba(0, 207, 180, 0.2);
    border: 2px solid rgba(0, 207, 180, 0.5);
    color: #00CFB4;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 30px;
}

.is-invalid {
    border-color: #dc3545 !important;
}

.invalid-feedback {
    color: #ff6b6b;
    font-size: 0.85rem;
    margin-top: 5px;
}

/* Responsive */
@media (max-width: 992px) {
    .hero-title {
        font-size: 2.5rem;
    }
    
    .section-title {
        font-size: 2.2rem;
    }
    
    .info-content {
        padding-right: 0;
        margin-bottom: 40px;
    }
    
    .contact-form-container {
        padding: 30px;
    }
}

@media (max-width: 768px) {
    .hero-title {
        font-size: 2rem;
    }
    
    .hero-subtitle {
        font-size: 1.1rem;
    }
    
    .section-title {
        font-size: 2rem;
    }
    
    .contact-content {
        padding: 60px 0;
    }
    
    .services-list {
        gap: 25px;
    }
    
    .service-item {
        gap: 15px;
        padding: 15px;
    }
    
    .service-icon {
        font-size: 2rem;
    }
    
    .contact-form-container {
        padding: 25px;
    }
    
    .form-header h3 {
        font-size: 1.6rem;
    }

    .contact-info-cards {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 576px) {
    .hero-title {
        font-size: 1.8rem;
    }
    
    .section-title {
        font-size: 1.8rem;
    }
    
    .phone-input {
        flex-direction: column;
        gap: 15px;
    }
    
    .country-code {
        width: 100%;
    }

    .service-item {
        flex-direction: column;
        text-align: center;
    }

    .info-card {
        flex-direction: column;
        text-align: center;
    }
}
</style>
@endsection