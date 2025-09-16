<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Support Nigerian students by donating to Academic Funding Gateway. Help empower the next generation of entrepreneurs and leaders." />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="{{ asset('assets/img/logo-favicon.png') }}" />
    <link rel="apple-touch-icon" href="{{ asset('img/apple-touch-icon.png') }}" />
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}" />
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <script type="module" src="https://unpkg.com/ionicons@5.4.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.4.0/dist/ionicons/ionicons.js"></script>
    <script defer src="https://unpkg.com/smoothscroll-polyfill@0.4.4/dist/smoothscroll.min.js"></script>
    <title>Donate &mdash; Academic Funding Gateway</title>
    <style>
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
        *:focus {
            outline: none;
            box-shadow: 0 0 0 0.8rem rgba(24, 183, 190, 0.5);
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
            height: clamp(2.8rem, 4vw, 3.2rem);
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
        .section-donation {
            padding: 9.6rem 0;
        }
        .donation-text {
            font-size: 2rem;
            line-height: 1.6;
            margin-bottom: 4.8rem;
        }
        .card-body {
            padding: 3.2rem;
            background-color: #fff;
            border-radius: 11px;
            box-shadow: 0 2.4rem 4.8rem rgba(0, 0, 0, 0.075);
        }
        .card-body p {
            font-size: 1.8rem;
            line-height: 1.6;
        }
        .text-primary {
            color: var(--color-primary);
        }
        .fs-5 {
            font-size: 2rem;
        }
        .mb-2 {
            margin-bottom: 1.6rem !important;
        }
        .mb-0 {
            margin-bottom: 0 !important;
        }
        .footer {
            padding: 12.8rem 0;
            border-top: 1px solid #eee;
            background-color: var(--color-dark);
            color: #fff;
        }
        .logo-col {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .footer-logo {
            display: block;
            margin-bottom: 3.2rem;
        }
        .contacts {
            font-style: normal;
            font-size: 1.6rem;
            line-height: 1.6;
            text-align: center;
        }
        .address {
            margin-bottom: 2.4rem;
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
        .copyright {
            font-size: 1.4rem;
            line-height: 1.6;
            color: #767676;
            margin-top: auto;
            text-align: center;
        }
        @media (max-width: 75em) {
            .container {
                max-width: 96rem;
                padding: 0 2.4rem;
            }
            .heading-primary {
                font-size: 4.4rem;
            }
            .heading-secondary {
                font-size: 3.6rem;
            }
            .donation-text {
                font-size: 1.6rem;
            }
        }
        @media (max-width: 59em) {
            html {
                font-size: 56.25%;
            }
            .header {
                padding: 0 2.4rem;
            }
            .main-nav {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background-color: var(--color-light-background);
                padding: 2.4rem;
                z-index: 1000;
            }
            .main-nav.active {
                display: block;
            }
            .main-nav-list {
                flex-direction: column;
                gap: 2.4rem;
                align-items: center;
            }
            .btn-mobile-nav {
                display: block;
            }
            .btn-mobile-nav[aria-expanded="true"] .icon-mobile-nav[name="menu-outline"] {
                display: none;
            }
            .btn-mobile-nav[aria-expanded="true"] .icon-mobile-nav[name="close-outline"] {
                display: block;
            }
            .section-donation {
                padding: 6.4rem 0;
            }
        }
        @media (max-width: 34em) {
            html {
                font-size: 50%;
            }
            .container {
                padding: 0 1.6rem;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <a href="{{ route('landing') }}">
            <img class="logo" alt="Academic Funding Gateway logo" src="{{ asset('assets/img/logo-dark-transparent.png') }}" />
        </a>
        <nav class="main-nav">
            <ul class="main-nav-list">
                <li><a class="main-nav-link" href="{{ route('landing') }}#about">About Us</a></li>
                <li><a class="main-nav-link" href="{{ route('donation') }}">Partner with us</a></li>
                <li><a class="main-nav-link nav-cta" href="{{ route('student.register') }}">Complete Registration</a></li>
            </ul>
        </nav>
        <button class="btn-mobile-nav" aria-expanded="false">
            <ion-icon class="icon-mobile-nav" name="menu-outline"></ion-icon>
            <ion-icon class="icon-mobile-nav" name="close-outline"></ion-icon>
        </button>
    </header>
    <main>
        <section class="section-donation">
            <div class="container center-text">
                <span class="subheading">Support Our Mission</span>
                <h2 class="heading-secondary">Donate to Empower Future Leaders</h2>
                <p class="donation-text">
                    Your generous contributions help Nigerian students access grants, training, and mentorship to become successful entrepreneurs. Every donation makes a difference in transforming lives and building a brighter future.
                </p>
            </div>
            <div class="container">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <p class="mb-2"><strong>Account Number:</strong> <span class="text-primary fs-5">1028614880</span></p>
                            <p class="mb-2"><strong>Bank Name:</strong> UBA (United Bank for Africa)</p>
                            <p class="mb-0"><strong>Account Name:</strong> Academic Funding Gateway Network</p>
                        </div>
                    </div>
                </div>
            </div>            
        </section>
    </main>
    <footer class="footer">
        <div class="container">
            <div class="logo-col">
                <a href="#" class="footer-logo">
                    <img class="logo" alt="Academic Funding Gateway logo" src="{{ asset('assets/img/logo-light-transparent.png') }}" />
                </a>
                <address class="contacts">
                    <p class="address">NO 3 TAURA CLOSE 2/2 KUBWA, ABUJA, FCT</p>
                    <p>
                        <a class="footer-link" href="tel:08030634841">09134448135</a><br />
                        <a class="footer-link" href="mailto:victoruadaji1@gmail.com">info@academicfunding.org</a>
                    </p>
                </address>
                <p class="copyright">
                    &copy; 2025 Academic Funding Gateway. All Rights Reserved.
                </p>
            </div>
        </div>
    </footer>
    <script>
        document.querySelector('.btn-mobile-nav').addEventListener('click', function () {
            const nav = document.querySelector('.main-nav');
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', !isExpanded);
            nav.classList.toggle('active');
        });
    </script>
</body>
</html>