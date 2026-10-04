@php
    $name = \App\Models\Setting::get('business.name', 'The Drive Clinic');
    $address = \App\Models\Setting::get('business.address', 'Nanak Nagar, Jammu, J&K 180004');
    $phone = \App\Models\Setting::get('business.phone', '+91 94191 00000');
    $url = url('/');
    $logoUrl = asset('favicon.svg');
@endphp

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "AutoWash",
  "name": "{{ $name }}",
  "image": "{{ $logoUrl }}",
  "@id": "{{ $url }}",
  "url": "{{ $url }}",
  "telephone": "{{ $phone }}",
  "priceRange": "₹₹",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "Nanak Nagar",
    "addressLocality": "Jammu",
    "addressRegion": "Jammu & Kashmir",
    "postalCode": "180004",
    "addressCountry": "IN"
  },
  "geo": {
    "@type": "GeoCoordinates",
    "latitude": 32.7050,
    "longitude": 74.8720
  },
  "openingHoursSpecification": [
    {
      "@type": "OpeningHoursSpecification",
      "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"],
      "opens": "09:00",
      "closes": "19:00"
    },
    {
      "@type": "OpeningHoursSpecification",
      "dayOfWeek": ["Saturday", "Sunday"],
      "opens": "09:00",
      "closes": "20:00"
    }
  ]
}
</script>
