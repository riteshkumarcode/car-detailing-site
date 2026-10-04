@extends('layouts.public')

@section('title', 'Privacy Policy — The Drive Clinic')
@section('meta_description', 'Learn how The Drive Clinic collects, manages, and protects your personal and vehicle data in compliance with the Digital Personal Data Protection Act.')

@section('content')
<section class="bg-gradient-to-b from-mist to-white py-16 lg:py-24 border-b border-border-light">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Breadcrumb --}}
        <nav class="flex items-center space-x-2 text-xs font-mono tracking-wider uppercase text-teal-deep/60 mb-6">
            <a href="{{ route('home') }}" class="hover:text-teal transition-colors">Home</a>
            <span>/</span>
            <span class="text-teal font-medium">Privacy Policy</span>
        </nav>

        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-display font-black text-teal-deep tracking-tight mb-4">
            Privacy Policy & Data Protection
        </h1>
        <p class="text-base sm:text-lg text-slate-deep leading-relaxed">
            Effective Date: October 2026 &bull; Compliant with the Digital Personal Data Protection Act (DPDP), 2023.
        </p>
    </div>
</section>

<section class="py-16 lg:py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <article class="prose prose-slate max-w-none text-slate-deep space-y-10 leading-relaxed">
            
            <div>
                <h2 class="text-2xl font-display font-bold text-teal-deep mb-4">1. Overview & Commitment</h2>
                <p>
                    The Drive Clinic ("we", "our", or "the Studio"), located at Nanak Nagar, Jammu, J&K 180004, is dedicated to protecting the privacy, security, and integrity of your personal and vehicular information. This policy describes how we collect, handle, store, and safeguard your data when you visit our studio, use our website (<code class="bg-mist px-2 py-0.5 rounded text-teal-deep font-mono text-sm">thedriveclinic.in</code>), or access our digital car passport and diagnostic services.
                </p>
            </div>

            <div>
                <h2 class="text-2xl font-display font-bold text-teal-deep mb-4">2. Information We Collect</h2>
                <p class="mb-3">To deliver precision automotive care and verifiable service passports, we collect:</p>
                <ul class="list-disc pl-6 space-y-2">
                    <li><strong class="text-ink">Contact Details:</strong> Full name, telephone/WhatsApp mobile number, email address, and billing address.</li>
                    <li><strong class="text-ink">Vehicle Information:</strong> Vehicle registration number (plate), make, model, variant, year, odometer reading, and paint/bodywork condition notes.</li>
                    <li><strong class="text-ink">Diagnostic & Inspection Media:</strong> High-definition photographs and video recordings captured during 40-point vehicle health checks, pre-wash walkarounds, and post-treatment handovers.</li>
                    <li><strong class="text-ink">Service Records:</strong> Wash history, detailing milestones, ceramic coating layer logs, warranty activations, and invoices.</li>
                    <li><strong class="text-ink">Digital Interactions:</strong> Inquiries submitted through our website, appointment bookings, and Drive Club membership records.</li>
                </ul>
            </div>

            <div>
                <h2 class="text-2xl font-display font-bold text-teal-deep mb-4">3. How We Use Your Information</h2>
                <p class="mb-3">Your information is used strictly for legitimate studio operations, including:</p>
                <ul class="list-disc pl-6 space-y-2">
                    <li>Scheduling and managing detailing appointments and bay allocations.</li>
                    <li>Delivering automated WhatsApp service updates, live bay status notifications, and inspection report PDFs.</li>
                    <li>Maintaining your vehicle's permanent Digital Car Passport for resale value preservation and warranty verification.</li>
                    <li>Managing Drive Club membership allocations, credit balances, and renewal reminders.</li>
                    <li>Generating GST-compliant invoices and accounting documentation.</li>
                </ul>
                <p class="mt-3 font-semibold text-teal-deep">
                    We never sell, rent, or trade your personal or vehicle data to third-party marketers or advertisers.
                </p>
            </div>

            <div>
                <h2 class="text-2xl font-display font-bold text-teal-deep mb-4">4. Digital Inspection Media & Privacy</h2>
                <p>
                    Photographs and videos captured during the detailing process are archived in your private vehicle passport. Occasionally, before-and-after transformation imagery may be showcased on our public portfolio or social channels with license plate numbers digitally obscured or anonymized, unless you have explicitly authorized full display.
                </p>
            </div>

            <div>
                <h2 class="text-2xl font-display font-bold text-teal-deep mb-4">5. Data Retention & Security</h2>
                <p>
                    All diagnostic reports, customer accounts, and service logs are stored on encrypted, access-controlled cloud infrastructure. Vehicle passport records remain accessible to allow lifetime service verification. Financial transactions and GST invoices are retained as required under Indian taxation statutes.
                </p>
            </div>

            <div>
                <h2 class="text-2xl font-display font-bold text-teal-deep mb-4">6. Your Rights Under DPDP Act 2023</h2>
                <p class="mb-3">As a customer, you hold comprehensive rights regarding your digital personal data:</p>
                <ul class="list-disc pl-6 space-y-2">
                    <li><strong class="text-ink">Right to Access:</strong> You can request a copy of all personal and vehicle diagnostic records associated with your mobile number.</li>
                    <li><strong class="text-ink">Right to Correction:</strong> You may update or correct inaccurate personal details at any time.</li>
                    <li><strong class="text-ink">Right to Erasure:</strong> You may request the deletion of your account and marketing contact records, subject to statutory tax and warranty record obligations.</li>
                    <li><strong class="text-ink">Grievance Redressal:</strong> You have the right to contact our Data Protection Officer for prompt resolution of privacy concerns.</li>
                </ul>
            </div>

            <div class="bg-mist p-6 rounded-xl border border-border-light">
                <h2 class="text-xl font-display font-bold text-teal-deep mb-2">7. Contact Data Privacy Officer</h2>
                <p class="text-sm mb-4">
                    For inquiries, data export requests, or erasure notices, reach out to our studio management team:
                </p>
                <div class="text-sm space-y-1 font-mono text-ink">
                    <p><strong>The Drive Clinic Studio</strong></p>
                    <p>Nanak Nagar, Jammu, J&K 180004</p>
                    <p>Email: <a href="mailto:privacy@thedriveclinic.in" class="text-teal hover:underline">privacy@thedriveclinic.in</a></p>
                    <p>WhatsApp / Phone: +91 94191 00000</p>
                </div>
            </div>

        </article>
    </div>
</section>
@endsection
