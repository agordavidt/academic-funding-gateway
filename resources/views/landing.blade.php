<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academic Funding Gateway - Your Path to Academic Excellence</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --color-light-background: #f9f7f0;
            --color-primary: #18b7be;
            --color-secondary: #178ca4;
            --color-dark: #072a40;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--color-light-background);
            color: var(--color-dark);
        }
        .navbar-brand, .nav-link {
            color: var(--color-dark) !important;
        }
        .btn-primary {
            background-color: var(--color-primary);
            border-color: var(--color-primary);
            color: #fff;
        }
        .btn-primary:hover {
            background-color: var(--color-secondary);
            border-color: var(--color-secondary);
        }
        .bg-primary-dark {
            background-color: var(--color-dark);
        }
        .text-primary-light {
            color: var(--color-primary);
        }
        .hero-section {
            background: url('https://images.unsplash.com/photo-1522071820081-009f0129c71c?q=80&w=2070&auto=format&fit=crop') no-repeat center center/cover;
            color: #fff;
            padding: 100px 0;
            position: relative;
        }
        .hero-overlay {
            background-color: rgba(7, 42, 64, 0.7);
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        .hero-content {
            position: relative;
            z-index: 1;
        }
        .contact-info a {
            color: inherit;
            text-decoration: none;
        }
        .contact-info a:hover {
            text-decoration: underline;
        }
        .section-title {
            color: var(--color-secondary);
        }
    </style>
</head>
<body>

{{-- Navbar --}}
<nav class="navbar navbar-expand-lg navbar-light py-3">
    <div class="container">
        <a class="navbar-brand fw-bold fs-4" href="#">
            <i class="fas fa-graduation-cap me-2 text-primary-light"></i>Academic Funding Gateway
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#about">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#eligibility">Eligibility</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#contact">Contact</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

{{-- Hero Section --}}
<header class="hero-section">
    <div class="hero-overlay"></div>
    <div class="container hero-content text-center">
        <h1 class="display-4 fw-bold mb-3">Your Journey to Academic Excellence Starts Here</h1>
        <p class="lead mb-4">Empowering students across Nigerian Universities with academic grants to achieve their dreams without financial barriers.</p>
        <p>
            <a href="{{ route('student.register') }}" class="btn btn-primary btn-lg rounded-pill px-5">
                Complete Your Registration
            </a>
        </p>
    </div>
</header>

{{-- About Section --}}
<section id="about" class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0">
                <img src="https://images.unsplash.com/photo-1522204523234-8729aa6e993e?q=80&w=2070&auto=format&fit=crop" alt="Students studying together" class="img-fluid rounded shadow-lg">
            </div>
            <div class="col-md-6">
                <h2 class="section-title fw-bold">About Academic Funding Gateway</h2>
                <p class="lead">We are a dedicated platform providing academic grants to deserving students in various Nigerian universities. Our mission is to alleviate financial stress and enable students to focus on their studies, research, and personal growth.</p>
                <p>We believe that no student should have to abandon their academic pursuits due to a lack of funds. Our grants cover tuition, research materials, and other essential academic expenses, paving the way for a brighter future for Nigeria's youth.</p>
            </div>
        </div>
    </div>
</section>

{{-- Eligibility Section --}}
<section id="eligibility" class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title fw-bold">Who is Eligible?</h2>
            <p class="lead text-muted">Our grants are designed to support a wide range of students.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm rounded-lg">
                    <div class="card-body text-center">
                        <div class="icon-box text-primary-light fs-1 mb-3">
                            <i class="fas fa-university"></i>
                        </div>
                        <h5 class="fw-bold">University Students</h5>
                        <p class="text-muted">Currently enrolled undergraduate or postgraduate students in any recognized Nigerian university.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm rounded-lg">
                    <div class="card-body text-center">
                        <div class="icon-box text-primary-light fs-1 mb-3">
                            <i class="fas fa-star"></i>
                        </div>
                        <h5 class="fw-bold">Academic Merit</h5>
                        <p class="text-muted">Applicants with a proven record of academic excellence and good standing.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm rounded-lg">
                    <div class="card-body text-center">
                        <div class="icon-box text-primary-light fs-1 mb-3">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                        <h5 class="fw-bold">Demonstrated Need</h5>
                        <p class="text-muted">Students who can demonstrate a genuine financial need for support.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Contact Section --}}
<section id="contact" class="py-5">
    <div class="container text-center">
        <h2 class="section-title fw-bold mb-4">Contact Us</h2>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <p class="lead mb-4">Have questions? Feel free to reach out to us using the details below.</p>
                <div class="card border-0 shadow-sm rounded-lg p-4">
                    <div class="card-body contact-info">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-3">
                                <i class="fas fa-phone-alt me-2 text-primary-light"></i>
                                <strong>Phone:</strong> <a href="tel:+2349134448135">09134448135</a>
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-envelope me-2 text-primary-light"></i>
                                <strong>Email:</strong> <a href="mailto:info@academicfunding.org">info@academicfunding.org</a>
                            </li>
                            <li>
                                <i class="fas fa-map-marker-alt me-2 text-primary-light"></i>
                                <strong>Address:</strong> NO 3 TAURA CLOSE 2/2 KUBWA, ABUJA, FCT
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Footer --}}
<footer class="bg-primary-dark text-white py-4">
    <div class="container text-center">
        <p class="mb-0">&copy; {{ date('Y') }} Academic Funding Gateway. All Rights Reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>