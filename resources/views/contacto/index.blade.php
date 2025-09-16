@extends('layouts.app')

@section('title', 'Contacto - MY Tech Solutions')

@push('styles')
<style>
    /* Variables */
    :root {
        --gradient-primary: linear-gradient(135deg, #007bff 0%, #1a5cff 100%);
        --gradient-secondary: linear-gradient(135deg, #00d4ff 0%, #007bff 100%);
        --glass-bg: rgba(255, 255, 255, 0.1);
        --glass-border: rgba(255, 255, 255, 0.2);
    }

    /* Hero Section con Parallax Avanzado */
    .hero-contact {
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-dark) 100%);
        padding: 120px 0 80px;
        color: white;
        position: relative;
        overflow: hidden;
        min-height: 100vh;
        display: flex;
        align-items: center;
    }

    .parallax-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 120%;
        height: 120%;
        z-index: 1;
        background: 
            radial-gradient(circle at 25% 25%, rgba(0, 212, 255, 0.4) 0%, transparent 50%),
            radial-gradient(circle at 75% 75%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 50% 10%, rgba(0, 123, 255, 0.3) 0%, transparent 40%);
        animation: parallax-float 20s ease-in-out infinite;
    }

    @keyframes parallax-float {
        0%, 100% { 
            transform: translateX(0) translateY(0) rotate(0deg); 
        }
        33% { 
            transform: translateX(-30px) translateY(-20px) rotate(1deg); 
        }
        66% { 
            transform: translateX(20px) translateY(-10px) rotate(-0.5deg); 
        }
    }

    .geometric-elements {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 2;
        pointer-events: none;
    }

    .geo-element {
        position: absolute;
        background: rgba(0, 212, 255, 0.1);
        border: 2px solid rgba(0, 212, 255, 0.3);
        animation: geo-float 15s ease-in-out infinite;
    }

    .geo-element:nth-child(1) {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        top: 15%;
        left: 10%;
        animation-delay: 0s;
    }

    .geo-element:nth-child(2) {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        top: 70%;
        right: 15%;
        animation-delay: 2s;
    }

    .geo-element:nth-child(3) {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        top: 40%;
        right: 5%;
        animation-delay: 4s;
        background: rgba(255, 255, 255, 0.05);
        border-color: rgba(255, 255, 255, 0.2);
    }

    .geo-element:nth-child(4) {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        top: 60%;
        left: 20%;
        animation-delay: 6s;
        transform: rotate(45deg);
    }

    @keyframes geo-float {
        0%, 100% { 
            transform: translateY(0px) translateX(0px) rotate(0deg);
            opacity: 0.3;
        }
        25% { 
            transform: translateY(-30px) translateX(20px) rotate(90deg);
            opacity: 0.6;
        }
        50% { 
            transform: translateY(-20px) translateX(-15px) rotate(180deg);
            opacity: 0.4;
        }
        75% { 
            transform: translateY(-40px) translateX(10px) rotate(270deg);
            opacity: 0.7;
        }
    }

    .hero-content {
        position: relative;
        z-index: 3;
        text-align: center;
        max-width: 900px;
        margin: 0 auto;
        animation: content-slide-up 1.2s ease-out;
    }

    @keyframes content-slide-up {
        0% { 
            opacity: 0; 
            transform: translateY(50px); 
        }
        100% { 
            opacity: 1; 
            transform: translateY(0); 
        }
    }

    .contact-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.8rem;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(25px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 50px;
        padding: 1.2rem 2.5rem;
        margin-bottom: 2rem;
        font-weight: 600;
        font-size: 1.1rem;
        color: #00d4ff;
        animation: badge-pulse 4s ease-in-out infinite;
        box-shadow: 
            0 15px 35px rgba(0, 212, 255, 0.2),
            inset 0 1px 0 rgba(255, 255, 255, 0.2);
    }

    @keyframes badge-pulse {
        0%, 100% { 
            box-shadow: 
                0 15px 35px rgba(0, 212, 255, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            transform: scale(1);
        }
        50% { 
            box-shadow: 
                0 20px 50px rgba(0, 212, 255, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.3);
            transform: scale(1.02);
        }
    }

    .hero-contact h1 {
        font-size: 4.5rem;
        font-weight: 900;
        margin-bottom: 2rem;
        background: linear-gradient(45deg, #ffffff 20%, #e3f2fd 50%, #00d4ff 80%, #ffffff 100%);
        background-size: 300% 300%;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        line-height: 1.1;
        animation: gradient-shift 6s ease-in-out infinite;
        text-shadow: 0 0 40px rgba(255, 255, 255, 0.1);
    }

    @keyframes gradient-shift {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }

    .hero-contact .lead {
        font-size: 1.5rem;
        margin-bottom: 3rem;
        opacity: 0.95;
        line-height: 1.6;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        animation: text-fade-in 1.8s ease-out 0.5s both;
    }

    @keyframes text-fade-in {
        0% { opacity: 0; transform: translateY(30px); }
        100% { opacity: 0.95; transform: translateY(0); }
    }

    .cta-buttons-hero {
        display: flex;
        gap: 1.5rem;
        justify-content: center;
        flex-wrap: wrap;
        animation: buttons-fade-in 2s ease-out 1s both;
    }

    @keyframes buttons-fade-in {
        0% { opacity: 0; transform: translateY(40px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    .hero-cta {
        display: inline-flex;
        align-items: center;
        gap: 0.8rem;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(20px);
        color: white;
        padding: 1.3rem 2.8rem;
        border-radius: 30px;
        font-weight: 700;
        font-size: 1.1rem;
        text-decoration: none;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 2px solid rgba(255, 255, 255, 0.3);
        box-shadow: 
            0 15px 35px rgba(0, 0, 0, 0.1),
            inset 0 1px 0 rgba(255, 255, 255, 0.2);
        position: relative;
        overflow: hidden;
    }

    .hero-cta::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.6s ease;
    }

    .hero-cta:hover::before {
        left: 100%;
    }

    .hero-cta:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-5px) scale(1.05);
        box-shadow: 
            0 25px 50px rgba(0, 0, 0, 0.2),
            inset 0 1px 0 rgba(255, 255, 255, 0.3);
        color: white;
    }

    .whatsapp-hero {
        background: rgba(37, 211, 102, 0.2);
        border-color: rgba(37, 211, 102, 0.5);
    }

    .whatsapp-hero:hover {
        background: rgba(37, 211, 102, 0.3);
        color: white;
    }

    /* Contact Methods Section */
    .contact-methods {
        padding: 100px 0;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 50%, #e3f2fd 100%);
        position: relative;
    }

    .contact-methods::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 20% 30%, rgba(0, 123, 255, 0.05) 0%, transparent 50%),
            radial-gradient(circle at 80% 70%, rgba(0, 212, 255, 0.05) 0%, transparent 50%);
        pointer-events: none;
    }

    .section-header {
        text-align: center;
        margin-bottom: 4rem;
        position: relative;
        z-index: 2;
    }

    .section-header h2 {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 1rem;
        background: linear-gradient(135deg, var(--primary-blue) 0%, #00d4ff 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        position: relative;
    }

    .section-header h2::after {
        content: '';
        position: absolute;
        bottom: -15px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 4px;
        background: var(--gradient-primary);
        border-radius: 2px;
    }

    .section-header p {
        font-size: 1.3rem;
        color: #6c757d;
        max-width: 600px;
        margin: 0 auto;
    }

    .contact-options {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin-bottom: 4rem;
        position: relative;
        z-index: 2;
    }

    .contact-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(20px);
        border-radius: 25px;
        padding: 3rem 2rem;
        text-align: center;
        box-shadow: 0 20px 60px rgba(0, 123, 255, 0.1);
        border: 1px solid rgba(0, 123, 255, 0.1);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
    }

    .contact-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--gradient-primary);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .contact-card:hover::before {
        transform: scaleX(1);
    }

    .contact-card:hover {
        transform: translateY(-15px);
        box-shadow: 0 30px 80px rgba(0, 123, 255, 0.2);
    }

    .contact-icon {
        width: 80px;
        height: 80px;
        background: var(--gradient-primary);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
        font-size: 2rem;
        color: white;
        box-shadow: 0 15px 30px rgba(0, 123, 255, 0.3);
        transition: all 0.3s ease;
    }

    .contact-card:hover .contact-icon {
        transform: scale(1.1) rotate(5deg);
    }

    .contact-card h3 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: var(--dark-text);
    }

    .contact-card p {
        color: #6c757d;
        line-height: 1.7;
        margin-bottom: 2rem;
    }

    .contact-btn {
        background: var(--gradient-primary);
        color: white;
        padding: 1rem 2rem;
        border-radius: 25px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        box-shadow: 0 10px 25px rgba(0, 123, 255, 0.3);
    }

    .contact-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(0, 123, 255, 0.4);
        color: white;
    }

    /* Contact Form Section con Parallax */
    .form-section {
        padding: 120px 0;
        background: 
            linear-gradient(135deg, #ffffff 0%, #f8f9fa 50%, #e3f2fd 100%),
            radial-gradient(circle at 20% 80%, rgba(0, 123, 255, 0.05) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(0, 212, 255, 0.05) 0%, transparent 50%);
        position: relative;
        overflow: hidden;
    }

    .form-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 120%;
        height: 120%;
        background: 
            radial-gradient(circle at 30% 30%, rgba(0, 123, 255, 0.1) 0%, transparent 30%),
            radial-gradient(circle at 70% 70%, rgba(0, 212, 255, 0.08) 0%, transparent 40%);
        animation: form-parallax 25s ease-in-out infinite;
        z-index: 1;
    }

    @keyframes form-parallax {
        0%, 100% { 
            transform: translateX(0) translateY(0) rotate(0deg); 
        }
        33% { 
            transform: translateX(-40px) translateY(-20px) rotate(1deg); 
        }
        66% { 
            transform: translateX(30px) translateY(-30px) rotate(-1deg); 
        }
    }

    .floating-shapes {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1;
        pointer-events: none;
    }

    .shape {
        position: absolute;
        background: linear-gradient(45deg, rgba(0, 123, 255, 0.1), rgba(0, 212, 255, 0.15));
        border-radius: 50%;
        animation: shape-float 20s ease-in-out infinite;
    }

    .shape:nth-child(1) {
        width: 60px;
        height: 60px;
        top: 10%;
        left: 5%;
        animation-delay: 0s;
    }

    .shape:nth-child(2) {
        width: 80px;
        height: 80px;
        top: 60%;
        right: 10%;
        animation-delay: 3s;
    }

    .shape:nth-child(3) {
        width: 40px;
        height: 40px;
        top: 30%;
        left: 80%;
        animation-delay: 6s;
    }

    .shape:nth-child(4) {
        width: 100px;
        height: 100px;
        top: 80%;
        left: 15%;
        animation-delay: 9s;
    }

    @keyframes shape-float {
        0%, 100% { 
            transform: translateY(0px) translateX(0px) scale(1);
            opacity: 0.4;
        }
        25% { 
            transform: translateY(-50px) translateX(20px) scale(1.1);
            opacity: 0.6;
        }
        50% { 
            transform: translateY(-30px) translateX(-25px) scale(0.9);
            opacity: 0.3;
        }
        75% { 
            transform: translateY(-70px) translateX(15px) scale(1.05);
            opacity: 0.5;
        }
    }

    .form-container {
        max-width: 900px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    .contact-form {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(25px);
        border-radius: 35px;
        padding: 4rem 3rem;
        box-shadow: 
            0 40px 100px rgba(0, 123, 255, 0.15),
            0 20px 60px rgba(0, 0, 0, 0.05),
            inset 0 1px 0 rgba(255, 255, 255, 0.8);
        border: 1px solid rgba(255, 255, 255, 0.3);
        position: relative;
        overflow: hidden;
        animation: form-slide-up 1.5s ease-out;
    }

    @keyframes form-slide-up {
        0% { 
            opacity: 0; 
            transform: translateY(80px);
        }
        100% { 
            opacity: 1; 
            transform: translateY(0);
        }
    }

    .contact-form::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 8px;
        background: linear-gradient(90deg, var(--primary-blue) 0%, #00d4ff 50%, var(--primary-blue) 100%);
        background-size: 200% 200%;
        border-radius: 35px 35px 0 0;
        animation: gradient-flow 3s ease-in-out infinite;
    }

    @keyframes gradient-flow {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }

    .form-header {
        text-align: center;
        margin-bottom: 3rem;
    }

    .form-header h3 {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 1rem;
        background: linear-gradient(135deg, var(--primary-blue) 0%, #00d4ff 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .form-header p {
        font-size: 1.2rem;
        color: #6c757d;
        margin: 0;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .form-group {
        position: relative;
        animation: field-fade-in 1.8s ease-out;
    }

    @keyframes field-fade-in {
        0% { 
            opacity: 0; 
            transform: translateY(30px);
        }
        100% { 
            opacity: 1; 
            transform: translateY(0);
        }
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-control {
        width: 100%;
        padding: 1.5rem 2rem;
        border: 3px solid transparent;
        border-radius: 20px;
        font-size: 1.05rem;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        background: 
            linear-gradient(white, white) padding-box,
            linear-gradient(135deg, rgba(0, 123, 255, 0.1), rgba(0, 212, 255, 0.1)) border-box;
        box-shadow: 
            0 5px 15px rgba(0, 123, 255, 0.08),
            inset 0 1px 0 rgba(255, 255, 255, 0.8);
        position: relative;
    }

    .form-control:focus {
        outline: none;
        background: 
            linear-gradient(white, white) padding-box,
            linear-gradient(135deg, var(--primary-blue), #00d4ff) border-box;
        box-shadow: 
            0 15px 35px rgba(0, 123, 255, 0.2),
            0 0 0 4px rgba(0, 123, 255, 0.1),
            inset 0 1px 0 rgba(255, 255, 255, 0.9);
        transform: translateY(-3px);
    }

    .form-control::placeholder {
        color: #adb5bd;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .form-control:focus::placeholder {
        opacity: 0.7;
        transform: translateY(-2px);
    }

    .form-label {
        position: absolute;
        top: -12px;
        left: 20px;
        background: linear-gradient(135deg, var(--primary-blue), #00d4ff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        padding: 0 15px;
        font-size: 0.95rem;
        font-weight: 700;
        border-radius: 15px;
        background-color: white;
        z-index: 2;
    }

    select.form-control {
        cursor: pointer;
        appearance: none;
        background-image: 
            linear-gradient(white, white),
            linear-gradient(135deg, var(--primary-blue), #00d4ff),
            url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23007bff' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 20px center;
        background-repeat: no-repeat;
        background-size: 20px;
        padding-right: 60px;
    }

    textarea.form-control {
        min-height: 140px;
        resize: vertical;
        font-family: inherit;
        line-height: 1.6;
    }

    .submit-btn {
        background: linear-gradient(135deg, var(--primary-blue) 0%, #00d4ff 100%);
        color: white;
        padding: 1.8rem 4rem;
        border: none;
        border-radius: 30px;
        font-size: 1.2rem;
        font-weight: 800;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 
            0 20px 40px rgba(0, 123, 255, 0.3),
            0 10px 20px rgba(0, 0, 0, 0.1),
            inset 0 1px 0 rgba(255, 255, 255, 0.2);
        width: 100%;
        position: relative;
        overflow: hidden;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .submit-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.8s ease;
    }

    .submit-btn:hover::before {
        left: 100%;
    }

    .submit-btn:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 
            0 30px 60px rgba(0, 123, 255, 0.4),
            0 15px 30px rgba(0, 0, 0, 0.15),
            inset 0 1px 0 rgba(255, 255, 255, 0.3);
        background: linear-gradient(135deg, #00d4ff 0%, var(--primary-blue) 100%);
    }

    .submit-btn:active {
        transform: translateY(-2px) scale(1.01);
    }

    .form-features {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 2rem;
        margin-top: 3rem;
        padding-top: 2rem;
        border-top: 2px solid rgba(0, 123, 255, 0.1);
    }

    .feature-item {
        text-align: center;
        padding: 1.5rem;
        background: rgba(0, 123, 255, 0.05);
        border-radius: 20px;
        border: 1px solid rgba(0, 123, 255, 0.1);
        transition: all 0.3s ease;
    }

    .feature-item:hover {
        background: rgba(0, 123, 255, 0.08);
        transform: translateY(-5px);
    }

    .feature-item i {
        font-size: 2rem;
        color: var(--primary-blue);
        margin-bottom: 1rem;
    }

    .feature-item h4 {
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: var(--dark-text);
    }

    .feature-item p {
        font-size: 0.9rem;
        color: #6c757d;
        margin: 0;
    }

    /* Map Section */
    .map-section {
        padding: 100px 0;
        background: linear-gradient(135deg, #f8f9fa 0%, #e3f2fd 100%);
        position: relative;
    }

    .map-content {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        align-items: center;
        position: relative;
        z-index: 2;
    }

    .map-info h3 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: var(--dark-text);
    }

    .map-info p {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #495057;
        margin-bottom: 2rem;
    }

    .info-grid {
        display: grid;
        gap: 1rem;
    }

    .info-item {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        border: 1px solid rgba(0, 123, 255, 0.1);
        transition: all 0.3s ease;
    }

    .info-item:hover {
        background: white;
        transform: translateX(10px);
        box-shadow: 0 10px 25px rgba(0, 123, 255, 0.1);
    }

    .info-icon {
        width: 50px;
        height: 50px;
        background: var(--gradient-primary);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
    }

    .info-text h4 {
        font-weight: 600;
        margin-bottom: 0.3rem;
        color: var(--dark-text);
    }

    .info-text p {
        margin: 0;
        color: #6c757d;
        font-size: 0.95rem;
    }

    .map-container {
        position: relative;
        border-radius: 25px;
        overflow: hidden;
        box-shadow: 0 25px 50px rgba(0, 123, 255, 0.15);
        background: white;
        padding: 15px;
    }

    .map-container iframe {
        width: 100%;
        height: 400px;
        border-radius: 15px;
        border: none;
    }

    .map-overlay {
        position: absolute;
        top: 25px;
        left: 25px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(15px);
        border-radius: 15px;
        padding: 1rem 1.5rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        z-index: 10;
    }

    .map-overlay h5 {
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: var(--primary-blue);
    }

    .map-overlay p {
        margin: 0;
        font-size: 0.9rem;
        color: #6c757d;
    }

    /* Responsive Mejorado con Parallax */
    @media (max-width: 1024px) {
        .hero-contact h1 {
            font-size: 3.5rem;
        }
        
        .geometric-elements {
            display: none; /* Simplificar en tablet */
        }
        
        .contact-form {
            padding: 3rem 2.5rem;
        }
    }

    @media (max-width: 768px) {
        .hero-contact {
            padding: 80px 0 60px;
            min-height: 80vh;
        }
        
        .hero-contact h1 {
            font-size: 2.8rem;
            line-height: 1.2;
            margin-bottom: 1.5rem;
        }
        
        .hero-contact .lead {
            font-size: 1.3rem;
            margin-bottom: 2.5rem;
        }
        
        .contact-badge {
            padding: 1rem 2rem;
            font-size: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .cta-buttons-hero {
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }
        
        .hero-cta {
            width: 100%;
            max-width: 320px;
            justify-content: center;
            padding: 1.2rem 2rem;
            font-size: 1rem;
        }
        
        /* Simplificar animaciones en móvil */
        .parallax-bg {
            animation: none;
            opacity: 0.6;
        }
        
        .floating-shapes .shape {
            animation-duration: 30s; /* Más lento para mejor performance */
        }
        
        .contact-options {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        
        .contact-card {
            padding: 2rem 1.5rem;
        }
        
        .form-section {
            padding: 80px 0;
        }
        
        .section-header h2 {
            font-size: 2.2rem;
        }
        
        .section-header p {
            font-size: 1.1rem;
        }
        
        .form-container {
            margin: 0 1rem;
        }
        
        .contact-form {
            padding: 2.5rem 2rem;
            border-radius: 25px;
        }
        
        .form-header h3 {
            font-size: 2rem;
        }
        
        .form-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        
        .form-control {
            padding: 1.3rem 1.8rem;
            font-size: 1rem;
        }
        
        .form-label {
            left: 18px;
            font-size: 0.9rem;
        }
        
        .submit-btn {
            padding: 1.5rem 3rem;
            font-size: 1.1rem;
        }
        
        .form-features {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        
        .feature-item {
            padding: 1rem;
        }
        
        .feature-item i {
            font-size: 1.5rem;
        }
        
        .feature-item h4 {
            font-size: 0.9rem;
        }
        
        .feature-item p {
            font-size: 0.8rem;
        }
        
        .map-content {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        
        .map-info {
            order: 1;
        }
        
        .map-container {
            order: -1;
        }
        
        .map-container iframe {
            height: 300px;
        }
    }

    @media (max-width: 480px) {
        .hero-contact {
            padding: 60px 0 40px;
            min-height: 70vh;
        }
        
        .hero-contact h1 {
            font-size: 2.2rem;
            margin-bottom: 1rem;
        }
        
        .hero-contact .lead {
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }
        
        .contact-badge {
            padding: 0.9rem 1.8rem;
            font-size: 0.95rem;
            margin-bottom: 1rem;
        }
        
        .hero-cta {
            max-width: 280px;
            padding: 1.1rem 1.8rem;
            font-size: 0.95rem;
        }
        
        .form-section {
            padding: 60px 0;
        }
        
        .section-header h2 {
            font-size: 2rem;
        }
        
        .section-header p {
            font-size: 1rem;
        }
        
        .form-container {
            margin: 0 0.5rem;
        }
        
        .contact-form {
            padding: 2rem 1.5rem;
            border-radius: 20px;
        }
        
        .form-header {
            margin-bottom: 2rem;
        }
        
        .form-header h3 {
            font-size: 1.8rem;
        }
        
        .form-header p {
            font-size: 1rem;
        }
        
        .form-control {
            padding: 1.2rem 1.5rem;
            font-size: 0.95rem;
            border-radius: 15px;
        }
        
        .form-label {
            left: 15px;
            font-size: 0.85rem;
            padding: 0 10px;
        }
        
        select.form-control {
            background-size: 18px;
            padding-right: 50px;
        }
        
        textarea.form-control {
            min-height: 120px;
        }
        
        .submit-btn {
            padding: 1.4rem 2.5rem;
            font-size: 1rem;
            border-radius: 25px;
        }
        
        .form-features {
            grid-template-columns: 1fr;
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .feature-item {
            padding: 1rem 0.8rem;
            text-align: left;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .feature-item i {
            font-size: 1.2rem;
            margin-bottom: 0;
            flex-shrink: 0;
        }
        
        .contact-card {
            padding: 1.5rem;
        }
        
        .contact-icon {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }
        
        .map-container iframe {
            height: 250px;
        }
        
        .map-overlay {
            top: 15px;
            left: 15px;
            padding: 0.8rem 1rem;
        }
    }

    /* Pantallas muy pequeñas */
    @media (max-width: 360px) {
        .hero-contact h1 {
            font-size: 1.9rem;
        }
        
        .hero-cta {
            max-width: 100%;
            padding: 1rem 1.5rem;
            font-size: 0.9rem;
        }
        
        .contact-form {
            padding: 1.5rem 1rem;
        }
        
        .form-control {
            padding: 1.1rem 1.3rem;
            font-size: 0.9rem;
        }
        
        .submit-btn {
            padding: 1.3rem 2rem;
            font-size: 0.95rem;
        }
    }

    /* Animaciones optimizadas para mobile */
    @media (max-width: 768px) {
        .hero-content {
            animation-duration: 0.8s;
        }
        
        .form-slide-up {
            animation-duration: 1s;
        }
        
        .field-fade-in {
            animation-duration: 1.2s;
        }
        
        .buttons-fade-in {
            animation-duration: 1.5s;
        }
        
        /* Reducir motion para usuarios sensibles */
        @media (prefers-reduced-motion: reduce) {
            .parallax-bg,
            .geometric-elements,
            .floating-shapes,
            .geo-element,
            .shape {
                animation: none;
            }
            
            .hero-contact h1 {
                animation: none;
                background: linear-gradient(45deg, #ffffff 30%, #00d4ff 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="hero-contact">
    <!-- Fondo con parallax -->
    <div class="parallax-bg"></div>
    
    <!-- Elementos geométricos flotantes -->
    <div class="geometric-elements">
        <div class="geo-element"></div>
        <div class="geo-element"></div>
        <div class="geo-element"></div>
        <div class="geo-element"></div>
    </div>
    
    <div class="container">
        <div class="hero-content">
            <div class="contact-badge">
                <i class="fas fa-rocket"></i>
                <span>Tu Próximo Proyecto Comienza Aquí</span>
            </div>
            <h1>¿Listo para Transformar tu Idea en Realidad?</h1>
            <p class="lead">
                No esperes más para digitalizar tu negocio. Conversemos sobre tu proyecto y descubre cómo podemos crear la 
                <strong>solución digital perfecta</strong> que impulse el crecimiento de tu empresa.
            </p>
            
            <div class="cta-buttons-hero">
                <a href="https://wa.me/573123708407?text=Hola,%20quiero%20digitalizar%20mi%20negocio%20y%20me%20interesa%20una%20consultoría%20gratuita" 
                   target="_blank" 
                   class="hero-cta whatsapp-hero">
                    <i class="fab fa-whatsapp"></i>
                    Consultoría Gratuita
                </a>
                <a href="#form" class="hero-cta">
                    <i class="fas fa-edit"></i>
                    Enviar mi Proyecto
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Contact Methods -->
<section class="contact-methods">
    <div class="container">
        <div class="section-header">
            <h2>Múltiples Formas de Contactarnos</h2>
            <p>Elige la opción que más te convenga para comenzar tu proyecto</p>
        </div>
        
        <div class="contact-options">
            <div class="contact-card">
                <div class="contact-icon">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <h3>WhatsApp Directo</h3>
                <p>
                    La forma más rápida de contactarnos. Te respondemos en menos de 30 minutos 
                    durante horario laboral. Perfecto para consultas rápidas y coordinación de reuniones.
                </p>
                <a href="https://wa.me/573123708407?text=Hola,%20me%20interesa%20conocer%20más%20sobre%20sus%20servicios%20de%20desarrollo%20web" 
                   target="_blank" 
                   class="contact-btn">
                    <i class="fab fa-whatsapp"></i>
                    +57 312 370 8407
                </a>
            </div>
            
            <div class="contact-card">
                <div class="contact-icon">
                    <i class="fas fa-video"></i>
                </div>
                <h3>Videollamada de Consultoría</h3>
                <p>
                    Agenda una videollamada gratuita de 30 minutos para analizar tu proyecto en detalle. 
                    Revisamos tus necesidades y te damos una propuesta inicial sin compromiso.
                </p>
                <a href="https://wa.me/573123708407?text=Hola,%20me%20gustaría%20agendar%20una%20videollamada%20de%20consultoría%20gratuita" 
                   target="_blank" 
                   class="contact-btn">
                    <i class="fas fa-calendar"></i>
                    Agendar Consultoría
                </a>
            </div>
            
            <div class="contact-card">
                <div class="contact-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <h3>Email Profesional</h3>
                <p>
                    Para consultas detalladas, envío de documentos o comunicación formal. 
                    Te respondemos en máximo 24 horas con una propuesta personalizada.
                </p>
                <a href="mailto:contacto@mytechsolutions.com" class="contact-btn">
                    <i class="fas fa-envelope"></i>
                    contacto@mytechsolutions.com
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Contact Form -->
<section class="form-section" id="form">
    <!-- Formas flotantes de fondo -->
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    
    <div class="container">
        <div class="section-header">
            <h2>Cuéntanos sobre tu Proyecto</h2>
            <p>Completa el formulario y te contactaremos en menos de 24 horas con una propuesta personalizada</p>
        </div>
        
        <div class="form-container">
            <form class="contact-form" action="#" method="POST">
                @csrf
                
                <div class="form-header">
                    <h3>Formulario de Proyecto</h3>
                    <p>Todos los campos son importantes para crear la mejor propuesta para ti</p>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Nombre Completo</label>
                        <input type="text" class="form-control" placeholder="Escribe tu nombre completo" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email Profesional</label>
                        <input type="email" class="form-control" placeholder="tu@empresa.com" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">WhatsApp</label>
                        <input type="tel" class="form-control" placeholder="+57 312 345 6789" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Empresa/Organización</label>
                        <input type="text" class="form-control" placeholder="Nombre de tu empresa o proyecto" required>
                    </div>
                    <div class="form-group full-width">
                        <label class="form-label">Tipo de Proyecto</label>
                        <select class="form-control" required>
                            <option value="">¿Qué tipo de proyecto necesitas?</option>
                            <option value="web">Página Web Profesional</option>
                            <option value="ecommerce">Tienda Online / E-commerce</option>
                            <option value="marketplace">Marketplace como MercadoLibre</option>
                            <option value="app">Aplicación Web Personalizada</option>
                            <option value="booking">Sistema de Reservas/Citas</option>
                            <option value="restaurant">Plataforma para Restaurante (QR, Pedidos)</option>
                            <option value="admin">Sistema Administrativo/CRM</option>
                            <option value="saas">Plataforma SaaS</option>
                            <option value="otros">Otro (especificar en descripción)</option>
                        </select>
                    </div>
                    <div class="form-group full-width">
                        <label class="form-label">Presupuesto Disponible</label>
                        <select class="form-control" required>
                            <option value="">Selecciona tu rango de presupuesto</option>
                            <option value="1-3">$1,000 - $3,000 USD</option>
                            <option value="3-5">$3,000 - $5,000 USD</option>
                            <option value="5-10">$5,000 - $10,000 USD</option>
                            <option value="10-20">$10,000 - $20,000 USD</option>
                            <option value="20+">Más de $20,000 USD</option>
                            <option value="consultar">Prefiero consultarlo en la reunión</option>
                        </select>
                    </div>
                    <div class="form-group full-width">
                        <label class="form-label">Descripción Detallada del Proyecto</label>
                        <textarea class="form-control" placeholder="Describe tu proyecto: ¿Qué problema resuelve? ¿Quién es tu audiencia? ¿Qué funcionalidades específicas necesitas? ¿Cuándo te gustaría lanzarlo? Mientras más detalles nos proporciones, mejor será nuestra propuesta." required></textarea>
                    </div>
                </div>
                
                <button type="submit" class="submit-btn">
                    <i class="fas fa-rocket"></i>
                    Enviar Proyecto y Recibir Propuesta
                </button>
                
                <div class="form-features">
                    <div class="feature-item">
                        <i class="fas fa-clock"></i>
                        <h4>Respuesta en 24h</h4>
                        <p>Te contactamos máximo en 24 horas</p>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-video"></i>
                        <h4>Consultoría Gratuita</h4>
                        <p>Primera videollamada sin costo</p>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-shield-alt"></i>
                        <h4>Propuesta Personalizada</h4>
                        <p>Diseñada específicamente para ti</p>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-handshake"></i>
                        <h4>Sin Compromiso</h4>
                        <p>Analizamos tu proyecto gratis</p>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="map-section">
    <div class="container">
        <div class="map-content">
            <div class="map-info">
                <h3>Nuestra Oficina en Bogotá</h3>
                <p>
                    Trabajamos desde el corazón de Bogotá, Colombia, pero nuestro alcance es global. 
                    Desarrollamos proyectos para clientes en múltiples países, combinando la calidez 
                    del servicio colombiano con estándares internacionales.
                </p>
                
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="info-text">
                            <h4>Ubicación</h4>
                            <p>Bogotá, Colombia</p>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="info-text">
                            <h4>Horario de Atención</h4>
                            <p>Lunes a Viernes: 8:00 AM - 6:00 PM (COT)</p>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-globe"></i>
                        </div>
                        <div class="info-text">
                            <h4>Alcance</h4>
                            <p>Proyectos en América y Europa</p>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <div class="info-text">
                            <h4>Soporte</h4>
                            <p>Disponible 24/7 para proyectos activos</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="map-container">
                <div class="map-overlay">
                    <h5>MY Tech Solutions</h5>
                    <p>Desarrollo Web Profesional</p>
                </div>
                <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d3249.012195470509!2d-74.13449935362908!3d4.600360674860746!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1ses-419!2sco!4v1757988380012!5m2!1ses-419!2sco" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </div>
</section>
@endsection