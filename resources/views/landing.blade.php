<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Academic Funding Gateway empowers Nigerian students with grants, training, and mentorship to become entrepreneurs and leaders." />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="{{ asset('img/favicon.png') }}" />
    <link rel="apple-touch-icon" href="{{ asset('img/apple-touch-icon.png') }}" />
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}" />
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <script type="module" src="https://unpkg.com/ionicons@5.4.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.4.0/dist/ionicons/ionicons.js"></script>
    <script defer src="https://unpkg.com/smoothscroll-polyfill@0.4.4/dist/smoothscroll.min.js"></script>
    <title>Academic Funding Gateway &mdash; From Student to CEO</title>
    <style>
        /* Your CSS styles remain exactly the same */
        :root {
            --color-light-background: #f9f7f0;
            --color-primary: #18b7be;
            --color-secondary: #178ca4;
            --color-dark: #072a40;
        }
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }
        


         :root {
            --color-light-background: #f9f7f0;
            --color-primary: #18b7be;
            --color-secondary: #178ca4;
            --color-dark: #072a40;
        }
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }
        html {
            font-size: 62.5%;
            overflow-x: hidden;
        }
        body {
            font-family: "Rubik", sans-serif;
            line-height: 1;
            font-weight: 400;
            color: var(--color-dark);
            overflow-x: hidden;
            background-color: var(--color-light-background);
        }
        .container {
            max-width: 120rem;
            padding: 0 3.2rem;
            margin: 0 auto;
        }
        .grid {
            display: grid;
            column-gap: 6.4rem;
            row-gap: 9.6rem;
        }
        .grid:not(:last-child) {
            margin-bottom: 9.6rem;
        }
        .grid--2-cols {
            grid-template-columns: repeat(2, 1fr);
        }
        .grid--3-cols {
            grid-template-columns: repeat(3, 1fr);
        }
        .grid--4-cols {
            grid-template-columns: repeat(4, 1fr);
        }
        .grid--center-v {
            align-items: center;
        }
        .heading-primary,
        .heading-secondary,
        .heading-tertiary {
            font-weight: 700;
            color: var(--color-dark);
            letter-spacing: -0.5px;
        }
        .heading-primary {
            font-size: 5.2rem;
            line-height: 1.05;
            margin-bottom: 3.2rem;
        }
        .heading-secondary {
            font-size: 4.4rem;
            line-height: 1.2;
            margin-bottom: 9.6rem;
        }
        .heading-tertiary {
            font-size: 3rem;
            line-height: 1.2;
            margin-bottom: 3.2rem;
        }
        .subheading {
            display: block;
            font-size: 1.6rem;
            font-weight: 500;
            color: var(--color-primary);
            text-transform: uppercase;
            margin-bottom: 1.6rem;
            letter-spacing: 0.75px;
        }
        .btn,
        .btn:link,
        .btn:visited {
            display: inline-block;
            text-decoration: none;
            font-size: 2rem;
            font-weight: 600;
            padding: 1.6rem 3.2rem;
            border-radius: 9px;
            border: none;
            cursor: pointer;
            font-family: inherit;
            transition: all 0.3s;
        }
        .btn--full:link,
        .btn--full:visited {
            background-color: var(--color-primary);
            color: #fff;
        }
        .btn--full:hover,
        .btn--full:active {
            background-color: var(--color-secondary);
        }
        .btn--outline:link,
        .btn--outline:visited {
            background-color: #fff;
            color: var(--color-dark);
        }
        .btn--outline:hover,
        .btn--outline:active {
            background-color: var(--color-light-background);
            box-shadow: inset 0 0 0 3px #fff;
        }
        .btn--form {
            background-color: var(--color-dark);
            color: var(--color-light-background);
            align-self: end;
            padding: 1.2rem;
        }
        .btn--form:hover {
            background-color: #fff;
            color: var(--color-dark);
        }
        .link:link,
        .link:visited {
            color: var(--color-primary);
            text-decoration: none;
            border-bottom: 1px solid currentColor;
            padding-bottom: 2px;
            transition: all 0.3s;
        }
        .link:hover,
        .link:active {
            color: var(--color-secondary);
            border-bottom: 1px solid transparent;
        }
        .list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 1.6rem;
        }
        .list-item {
            font-size: 1.8rem;
            display: flex;
            align-items: center;
            gap: 1.6rem;
            line-height: 1.2;
        }
        .list-icon {
            width: 3rem;
            height: 3rem;
            color: var(--color-primary);
        }
        *:focus {
            outline: none;
            box-shadow: 0 0 0 0.8rem rgba(24, 183, 190, 0.5);
        }
        .margin-right-sm {
            margin-right: 1.6rem !important;
        }
        .margin-bottom-md {
            margin-bottom: 4.8rem !important;
        }
        .center-text {
            text-align: center;
        }
        strong {
            font-weight: 500;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: var(--color-light-background);
            height: 9.6rem;
            padding: 0 4.8rem;
            position: relative;
        }
        .logo {
            height: 2.2rem;
        }
        .main-nav-list {
            list-style: none;
            display: flex;
            align-items: center;
            gap: 4.8rem;
        }
        .main-nav-link:link,
        .main-nav-link:visited {
            display: inline-block;
            text-decoration: none;
            color: var(--color-dark);
            font-weight: 500;
            font-size: 1.8rem;
            transition: all 0.3s;
        }
        .main-nav-link:hover,
        .main-nav-link:active {
            color: var(--color-secondary);
        }
        .main-nav-link.nav-cta:link,
        .main-nav-link.nav-cta:visited {
            padding: 1.2rem 2.4rem;
            border-radius: 9px;
            color: #fff;
            background-color: var(--color-primary);
        }
        .main-nav-link.nav-cta:hover,
        .main-nav-link.nav-cta:active {
            background-color: var(--color-secondary);
        }
        .btn-mobile-nav {
            border: none;
            background: none;
            cursor: pointer;
            display: none;
        }
        .icon-mobile-nav {
            height: 4.8rem;
            width: 4.8rem;
            color: var(--color-dark);
        }
        .icon-mobile-nav[name="close-outline"] {
            display: none;
        }
        .sticky .header {
            position: fixed;
            top: 0;
            bottom: 0;
            width: 100%;
            height: 8rem;
            padding-top: 0;
            padding-bottom: 0;
            background-color: rgba(255, 255, 255, 0.97);
            z-index: 999;
            box-shadow: 0 1.2rem 3.2rem rgba(0, 0, 0, 0.03);
        }
        .sticky .section-hero {
            margin-top: 9.6rem;
        }
        .section-hero {
            background: url('https://images.unsplash.com/photo-1522071820081-009f0129c71c?q=80&w=2070&auto=format&fit=crop') no-repeat center center/cover;
            position: relative;
            padding: 4.8rem 0 9.6rem 0;
        }
        .hero-overlay {
            background-color: rgba(7, 42, 64, 0.7);
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        .hero {
            max-width: 130rem;
            margin: 0 auto;
            padding: 0 3.2rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 9.6rem;
            align-items: center;
            position: relative;
            z-index: 1;
        }
        .hero-description {
            font-size: 2rem;
            line-height: 1.6;
            margin-bottom: 4.8rem;
            color: #fff;
        }
        .hero-img {
            width: 100%;
        }
        .section-featured {
            padding: 4.8rem 0 3.2rem 0;
        }
        .heading-featured-in {
            font-size: 1.4rem;
            text-transform: uppercase;
            letter-spacing: 0.75px;
            font-weight: 500;
            text-align: center;
            margin-bottom: 2.4rem;
            color: #888;
        }
        .logos {
            display: flex;
            justify-content: space-around;
        }
        .logos img {
            height: 3.2rem;
            filter: brightness(0);
            opacity: 50%;
        }
        .section-how {
            padding: 9.6rem 0;
        }
        .step-number {
            font-size: 8.6rem;
            font-weight: 600;
            color: #bab9b9;
            margin-bottom: 1.2rem;
        }
        .step-description {
            font-size: 1.8rem;
            line-height: 1.8;
        }
        .step-img-box {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .step-img-box::before,
        .step-img-box::after {
            content: "";
            display: block;
            border-radius: 50%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        .step-img-box::before {
            width: 60%;
            padding-bottom: 60%;
            background-color: var(--color-light-background);
            z-index: -2;
        }
        .step-img-box::after {
            width: 45%;
            padding-bottom: 45%;
            background-color: #e6f3f4;
            z-index: -1;
        }
        .step-img {
            width: 35%;
        }
        .section-offers {
            padding: 9.6rem 0;
        }
        .offer {
            box-shadow: 0 2.4rem 4.8rem rgba(0, 0, 0, 0.075);
            border-radius: 11px;
            overflow: hidden;
            transition: all 0.4s;
        }
        .offer:hover {
            transform: translateY(-1.2rem);
            box-shadow: 0 3.2rem 6.4rem rgba(0, 0, 0, 0.06);
        }
        .offer-content {
            padding: 3.2rem 4.8rem 4.8rem 4.8rem;
        }
        .offer-title {
            font-size: 2.4rem;
            color: var(--color-dark);
            font-weight: 600;
            margin-bottom: 3.2rem;
        }
        .offer-attributes {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }
        .offer-attribute {
            font-size: 1.8rem;
            display: flex;
            align-items: center;
            gap: 1.6rem;
        }
        .offer-icon {
            height: 2.4rem;
            width: 2.4rem;
            color: var(--color-primary);
        }
        .section-cta {
            padding: 4.8rem 0 12.8rem;
        }
        .cta {
            display: grid;
            grid-template-columns: 2fr 1fr;
            box-shadow: 0 2.4rem 4.8rem rgba(0, 0, 0, 0.15);
            border-radius: 11px;
            background-image: linear-gradient(to right bottom, var(--color-primary), var(--color-secondary));
            overflow: hidden;
        }
        .cta-text-box {
            padding: 4.8rem 6.4rem 6.4rem 6.4rem;
            color: #fff;
        }
        .cta .heading-secondary {
            color: inherit;
            margin-bottom: 3.2rem;
        }
        .cta-text {
            font-size: 1.8rem;
            line-height: 1.8;
            margin-bottom: 4.8rem;
        }
        .cta-img-box {
            background-image: linear-gradient(
                to right bottom,
                rgba(24, 183, 190, 0.35),
                rgba(23, 140, 164, 0.35)
            ),
            url("https://images.unsplash.com/photo-1522204523234-8729aa6e993e?q=80&w=2070&auto=format&fit=crop");
            background-size: cover;
            background-position: center;
        }
        .cta-form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            column-gap: 3.2rem;
            row-gap: 2.4rem;
        }
        .cta-form label {
            display: block;
            font-size: 1.6rem;
            font-weight: 500;
            margin-bottom: 1.2rem;
        }
        .cta-form input,
        .cta-form select {
            width: 100%;
            padding: 1.2rem;
            font-size: 1.8rem;
            font-family: inherit;
            color: inherit;
            border: none;
            background-color: var(--color-light-background);
            border-radius: 9px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }
        .cta-form input::placeholder {
            color: #aaa;
        }
        .cta *:focus {
            outline: none;
            box-shadow: 0 0 0 0.8rem rgba(249, 247, 240, 0.5);
        }
        .footer {
            padding: 12.8rem 0;
            border-top: 1px solid #eee;
            background-color: var(--color-dark);
            color: #fff;
        }
        .grid--footer {
            grid-template-columns: 1.5fr 1.5fr 1fr 1fr 1fr;
        }
        .logo-col {
            display: flex;
            flex-direction: column;
        }
        .footer-logo {
            display: block;
            margin-bottom: 3.2rem;
        }
        .social-links {
            list-style: none;
            display: flex;
            gap: 2.4rem;
        }
        .social-icon {
            height: 2.4rem;
            width: 2.4rem;
        }
        .copyright {
            font-size: 1.4rem;
            line-height: 1.6;
            color: #767676;
            margin-top: auto;
        }
        .footer-heading {
            font-size: 1.8rem;
            font-weight: 500;
            margin-bottom: 4rem;
        }
        .contacts {
            font-style: normal;
            font-size: 1.6rem;
            line-height: 1.6;
        }
        .address {
            margin-bottom: 2.4rem;
        }
        .footer-nav {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 2.4rem;
        }
        .footer-link:link,
        .footer-link:visited {
            text-decoration: none;
            font-size: 1.6rem;
            color: #767676;
            transition: all 0.3s;
        }
        .footer-link:hover,
        .footer-link:active {
            color: var(--color-primary);
        }
    </style>
</head>
<body>
    <header class="header">
        <a href="#">
            <img class="logo" alt="Academic Funding Gateway logo" src="{{ asset('img/academic-funding-logo.png') }}" />
        </a>
        <nav class="main-nav">
            <ul class="main-nav-list">
                <li><a class="main-nav-link" href="#about">About Us</a></li>
                <li><a class="main-nav-link" href="#how">How It Works</a></li>
                <li><a class="main-nav-link" href="#offers">What We Offer</a></li>
                <li><a class="main-nav-link nav-cta" href="{{ route('student.register') }}">Complete Registration</a></li>
            </ul>
        </nav>
        <button class="btn-mobile-nav">
            <ion-icon class="icon-mobile-nav" name="menu-outline"></ion-icon>
            <ion-icon class="icon-mobile-nav" name="close-outline"></ion-icon>
        </button>
    </header>
    <main>
        <section class="section-hero">
            <div class="hero-overlay"></div>
            <div class="hero">
                <div class="hero-text-box">
                    <h1 class="heading-primary">From Student to CEO – Empowering the Next Generation of Leaders</h1>
                    <p class="hero-description">
                        We provide Nigerian students with grants, training, mentorship, and resources to transition from the classroom to the boardroom.
                    </p>
                    <a href="{{ route('student.register') }}" class="btn btn--full margin-right-sm">Complete Registration</a>
                    <a href="#about" class="btn btn--outline">Learn More &darr;</a>
                </div>
                <div class="hero-img-box">
                    <picture>
                        <source srcset="{{ asset('img/hero.webp') }}" type="image/webp" />
                        <source srcset="{{ asset('img/hero-student.png') }}" type="image/png" />
                        <img src="{{ asset('img/hero-student.png') }}" class="hero-img" alt="Students collaborating on business ideas" />
                    </picture>
                </div>
            </div>
        </section>
        <section class="section-featured">
            <div class="container">
                <h2 class="heading-featured-in">As featured in</h2>
                <div class="logos">
                    <img src="{{ asset('img/logos/university-logo.png') }}" alt="University logo" />
                    <img src="{{ asset('img/logos/business-insider.png') }}" alt="Business Insider logo" />
                    <img src="{{ asset('img/logos/techcrunch.png') }}" alt="Techcrunch logo" />
                    <img src="{{ asset('img/logos/forbes.png') }}" alt="Forbes logo" />
                </div>
            </div>
        </section>
        <section class="section-about" id="about">
            <div class="container">
                <span class="subheading">About Us</span>
                <h2 class="heading-secondary">Empowering Students to Become Entrepreneurs</h2>
            </div>
            <div class="container grid grid--2-cols grid--center-v">
                <div class="step-text-box">
                    <p class="step-description">
                        The Student-to-CEO Initiative, powered by the Academic Funding Gateway Network, is committed to bridging the gap between education and entrepreneurship. We equip Nigerian students with the financial support, incubation, and learning tools they need to start and grow businesses—while still in school.
                    </p>
                </div>
                <div class="step-img-box">
                    <img src="{{ asset('img/students-collaborating.jpg') }}" class="step-img" alt="Students working on business ideas" />
                </div>
            </div>
        </section>
        <section class="section-how" id="how">
            <div class="container">
                <span class="subheading">How It Works</span>
                <h2 class="heading-secondary">Your Path to Entrepreneurship in 4 Simple Steps</h2>
            </div>
            <div class="container grid grid--2-cols grid--center-v">
                <div class="step-text-box">
                    <p class="step-number">01</p>
                    <h3 class="heading-tertiary">Apply Offline</h3>
                    <p class="step-description">
                        Begin your journey by submitting an offline application through your university or designated centers.
                    </p>
                </div>
                <div class="step-img-box">
                    <img src="{{ asset('img/app/offline-application.png') }}" class="step-img" alt="Offline application process" />
                </div>
                <div class="step-img-box">
                    <img src="{{ asset('img/app/complete-registration.png') }}" class="step-img" alt="Online registration completion screen" />
                </div>
                <div class="step-text-box">
                    <p class="step-number">02</p>
                    <h3 class="heading-tertiary">Complete Online Registration</h3>
                    <p class="step-description">
                        Once your details are imported by our admin team, log in to the platform to complete your registration and verify your profile.
                    </p>
                </div>
                <div class="step-text-box">
                    <p class="step-number">03</p>
                    <h3 class="heading-tertiary">Access Grants & Resources</h3>
                    <p class="step-description">
                        Receive financial support and business toolkits to fuel your startup journey.
                    </p>
                </div>
                <div class="step-img-box">
                    <img src="{{ asset('img/app/grants-screen.png') }}" class="step-img" alt="Grants access screen" />
                </div>
                <div class="step-img-box">
                    <img src="{{ asset('img/app/mentorship-screen.png') }}" class="step-img" alt="Mentorship program screen" />
                </div>
                <div class="step-text-box">
                    <p class="step-number">04</p>
                    <h3 class="heading-tertiary">Join Training & Launch Your Business</h3>
                    <p class="step-description">
                        Participate in six months of skill-based training and mentorship, then launch and grow your business with ongoing support.
                    </p>
                </div>
            </div>
        </section>
        <section class="section-offers" id="offers">
            <div class="container center-text">
                <span class="subheading">What We Offer</span>
                <h2 class="heading-secondary">Tools for Your Entrepreneurial Journey</h2>
            </div>
            <div class="container grid grid--3-cols margin-bottom-md">
                <div class="offer">
                    <div class="offer-content">
                        <p class="offer-title">Business Grants</p>
                        <ul class="offer-attributes">
                            <li class="offer-attribute">
                                <ion-icon class="offer-icon" name="cash-outline"></ion-icon>
                                <span>Financial support for startups</span>
                            </li>
                            <li class="offer-attribute">
                                <ion-icon class="offer-icon" name="rocket-outline"></ion-icon>
                                <span>Kickstart innovative ventures</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="offer">
                    <div class="offer-content">
                        <p class="offer-title">Skill Development</p>
                        <ul class="offer-attributes">
                            <li class="offer-attribute">
                                <ion-icon class="offer-icon" name="school-outline"></ion-icon>
                                <span>Structured training programs</span>
                            </li>
                            <li class="offer-attribute">
                                <ion-icon class="offer-icon" name="bulb-outline"></ion-icon>
                                <span>Leadership and innovation skills</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="offer">
                    <div class="offer-content">
                        <p class="offer-title">Mentorship & Incubation</p>
                        <ul class="offer-attributes">
                            <li class="offer-attribute">
                                <ion-icon class="offer-icon" name="people-outline"></ion-icon>
                                <span>Guidance from industry experts</span>
                            </li>
                            <li class="offer-attribute">
                                <ion-icon class="offer-icon" name="trending-up-outline"></ion-icon>
                                <span>Six months of incubation</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
        <section class="section-cta" id="cta">
            <div class="container">
                <div class="cta">
                    <div class="cta-text-box">
                        <h2 class="heading-secondary">Launch Your Entrepreneurial Journey Today!</h2>
                        <p class="cta-text">
                            Have questions about our Student-to-CEO Initiative or the registration process? Our team is here to guide you every step of the way. Reach out to our admin for personalized support and start building your future as a business leader.
                        </p>
                        <a href="mailto:info@academicfunding.org" class="btn btn--full">Contact Us for Registration Info</a>
                       
                    </div>
                    <div class="cta-img-box" role="img" aria-label="Students collaborating"></div>
                </div>
            </div>
        </section>
    </main>
    <footer class="footer">
        <div class="container">
            <div class="logo-col">
                <a href="#" class="footer-logo">
                    <img class="logo" alt="Academic Funding Gateway logo" src="{{ asset('img/academic-funding-logo.png') }}" />
                </a>
                <address class="contacts">
                    <p class="address">NO 3 TAURA CLOSE 2/2 KUBWA, ABUJA, FCT</p>
                    <p>
                        <a class="footer-link" href="tel:08030634841">08030634841</a><br />
                        <a class="footer-link" href="mailto:victoruadaji1@gmail.com">victoruadaji1@gmail.com</a>
                    </p>
                </address>
                <p class="copyright">
                    &copy; 2025 Academic Funding Gateway. All Rights Reserved.
                </p>
            </div>
        </div>
    </footer>
</body>
</html>