@extends('layouts.public')

@section('title', 'Terms of Service — The Drive Clinic')
@section('meta_description', 'Studio terms, booking policies, paint correction guidelines, and vehicle care agreements at The Drive Clinic, Nanak Nagar, Jammu.')

@section('content')
<section class="bg-gradient-to-b from-mist to-white py-16 lg:py-24 border-b border-border-light">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Breadcrumb --}}
        <nav class="flex items-center space-x-2 text-xs font-mono tracking-wider uppercase text-teal-deep/60 mb-6">
            <a href="{{ route('home') }}" class="hover:text-teal transition-colors">Home</a>
            <span>/</span>
            <span class="text-teal font-medium">Terms of Service</span>
        </nav>

        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-display font-black text-teal-deep tracking-tight mb-4">
            Studio Terms & Service Agreement
        </h1>
        <p class="text-base sm:text-lg text-slate-deep leading-relaxed">
            Effective Date: October 2026 &bull; The Drive Clinic, Nanak Nagar, Jammu, J&K.
        </p>
    </div>
</section>

<section class="py-16 lg:py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <article class="prose prose-slate max-w-none text-slate-deep space-y-10 leading-relaxed">
            
            <div>
                <h2 class="text-2xl font-display font-bold text-teal-deep mb-4">1. Agreement to Terms</h2>
                <p>
                    By booking an appointment, checking in a vehicle, purchasing a Drive Club membership, or commissioning detailing, foam washing, or ceramic protection services at The Drive Clinic ("Studio", "we", "us"), you agree to the terms, conditions, and operational policies detailed herein.
                </p>
            </div>

            <div>
                <h2 class="text-2xl font-display font-bold text-teal-deep mb-4">2. Digital Car Health Check & Pre-Service Walkaround</h2>
                <p class="mb-3">
                    Every vehicle entering our facility undergoes a standardized 40-point photographic condition inspection and clear coat depth measurement before work commences:
                </p>
                <ul class="list-disc pl-6 space-y-2">
                    <li><strong class="text-ink">Pre-existing Damage:</strong> Pre-existing stone chips, scratches penetrated beyond clear coat, dented bodywork, compromised rubber weatherstrips, hairline windscreen cracks, or oxidized trims are digitally catalogued and shared with you via WhatsApp.</li>
                    <li><strong class="text-ink">Cosmetic Scope Disclaimer:</strong> The Drive Health Score evaluates aesthetic and exterior/interior condition. It is not an engineering, mechanical, powertrain, or regulatory roadworthiness inspection.</li>
                    <li><strong class="text-ink">Client Confirmation:</strong> Confirmation of the walkaround report authorizes our studio technicians to proceed with the agreed scope of treatment.</li>
                </ul>
            </div>

            <div>
                <h2 class="text-2xl font-display font-bold text-teal-deep mb-4">3. Paint Correction, Polishing & Detailing Limits</h2>
                <p class="mb-3">
                    We practice conservation-first detailing to preserve the structural longevity of your car's factory clear coat:
                </p>
                <ul class="list-disc pl-6 space-y-2">
                    <li>Polishing will only remove defects within the clear coat layer. Scratches that catch a fingernail or expose base primer/metal require bodyshop respray rather than studio polishing.</li>
                    <li>Vehicles with sub-80 micron paint depth readings or repainted aftermarket panels with solvent pop will be treated using non-aggressive micro-abrasives to prevent clear coat burn-through.</li>
                </ul>
            </div>

            <div>
                <h2 class="text-2xl font-display font-bold text-teal-deep mb-4">4. Ceramic & Graphene Coating Warranties</h2>
                <p class="mb-3">
                    Our multi-year ceramic and graphene protective coatings carry structured studio performance warranties:
                </p>
                <ul class="list-disc pl-6 space-y-2">
                    <li>Warranty coverage guarantees hydrophobic contact angle retention, UV resistance, and chemical protection under normal road use.</li>
                    <li><strong class="text-ink">Annual Maintenance Inspection:</strong> To maintain warranty validity, the vehicle must undergo an annual ceramic decontamination wash and booster application at The Drive Clinic studio within 30 days of the anniversary date.</li>
                    <li>Damage resulting from automated brush washes, acidic roadside borewell water etching, or accidental abrasions is excluded from coating warranty terms.</li>
                </ul>
            </div>

            <div>
                <h2 class="text-2xl font-display font-bold text-teal-deep mb-4">5. Bay Booking, Punctuality & Cancellation</h2>
                <ul class="list-disc pl-6 space-y-2">
                    <li><strong class="text-ink">Arrival Window:</strong> Reserved bays are held for 15 minutes past your scheduled time. Arrivals beyond 15 minutes may be rescheduled to prevent bay workflow disruptions.</li>
                    <li><strong class="text-ink">Cancellation:</strong> We request at least 2 hours advance notice for cancellations or rescheduling. Cancellations can be executed via WhatsApp or phone.</li>
                </ul>
            </div>

            <div>
                <h2 class="text-2xl font-display font-bold text-teal-deep mb-4">6. Valuables & Handover Protocol</h2>
                <p>
                    Please remove cash, toll tags, jewelry, electronic devices, and personal belongings from gloveboxes, center consoles, and boot compartments before handing over vehicle keys. While our studio is under 24/7 CCTV surveillance, The Drive Clinic is not liable for unsecured personal property left inside vehicles.
                </p>
            </div>

            <div>
                <h2 class="text-2xl font-display font-bold text-teal-deep mb-4">7. Pricing, Vehicle Classes & Invoicing</h2>
                <p>
                    Standard service pricing is categorized by vehicle classification (Hatchback/Compact Sedan, Mid-size SUV/Executive Sedan, and Full-size SUV/Luxury). Full payment is due upon vehicle completion and post-treatment inspection prior to studio release. We accept UPI, debit/credit cards, and bank transfers. All invoices include statutory GST.
                </p>
            </div>

            <div class="bg-mist p-6 rounded-xl border border-border-light">
                <h2 class="text-xl font-display font-bold text-teal-deep mb-2">8. Studio Contact & Inquiries</h2>
                <p class="text-sm mb-4">
                    For questions regarding these terms or your service agreement:
                </p>
                <div class="text-sm space-y-1 font-mono text-ink">
                    <p><strong>The Drive Clinic Studio Operations</strong></p>
                    <p>Nanak Nagar, Jammu, J&K 180004</p>
                    <p>Phone / WhatsApp: +91 94191 00000</p>
                    <p>Email: <a href="mailto:contact@thedriveclinic.in" class="text-teal hover:underline">contact@thedriveclinic.in</a></p>
                </div>
            </div>

        </article>
    </div>
</section>
@endsection
