# Schema.org Structured Data Specifications (JSON-LD)

## 1. Master Organization & TravelAgency Schema (Site-wide)
```json
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "TravelAgency",
      "@id": "https://captainsofjawai.com/#organization",
      "name": "Captains of Jawai",
      "url": "https://captainsofjawai.com/",
      "logo": "https://captainsofjawai.com/assets/logo.PNG",
      "image": "https://captainsofjawai.com/assets/og-image.jpg",
      "description": "Bespoke private wildlife expeditions and leopard tracking safaris across the ancient granite hills of Jawai, Rajasthan.",
      "telephone": "+91-CLIENT-PHONE-REQUIRED",
      "email": "contact@captainsofjawai.com",
      "address": {
        "@type": "PostalAddress",
        "addressLocality": "Bera, Sumerpur",
        "addressRegion": "Rajasthan",
        "postalCode": "306126",
        "addressCountry": "IN"
      },
      "geo": {
        "@type": "GeoCoordinates",
        "latitude": 25.1055,
        "longitude": 73.1722
      },
      "areaServed": [
        {"@type": "Place", "name": "Jawai"},
        {"@type": "Place", "name": "Bera"},
        {"@type": "Place", "name": "Jawai Bandh"}
      ],
      "priceRange": "$$$$"
    },
    {
      "@type": "WebSite",
      "@id": "https://captainsofjawai.com/#website",
      "url": "https://captainsofjawai.com/",
      "name": "Captains of Jawai",
      "publisher": {"@id": "https://captainsofjawai.com/#organization"}
    }
  ]
}
```

## 2. TouristAttraction / TouristTrip Schema (`/safaris/leopard-safari/`)
```json
{
  "@context": "https://schema.org",
  "@type": "TouristTrip",
  "name": "Private Jawai Leopard Tracking Expedition",
  "description": "A private, naturalist-led 4x4 open-jeep expedition tracking wild Indian leopards across the prehistoric granite hills of Jawai and Bera.",
  "touristType": ["Wildlife Enthusiasts", "Photographers", "Luxury Travellers"],
  "provider": {"@id": "https://captainsofjawai.com/#organization"},
  "itinerary": {
    "@type": "ItemList",
    "numberOfItems": 2,
    "itemListElement": [
      {
        "@type": "ListItem",
        "position": 1,
        "name": "Dawn Granite Tracking",
        "description": "Tracking leopards on granite boulders during morning basking hours."
      },
      {
        "@type": "ListItem",
        "position": 2,
        "name": "Twilight & Boulder Sundowner",
        "description": "Late afternoon tracking followed by scenic bush sundowner overlooking the kopjes."
      }
    ]
  }
}
```

## 3. Strict Rule Against Fake Reviews
- We strictly **do not** inject fake AggregateRating schema or fabricated 5-star customer reviews. Review markup is only added once authentic, third-party verifiable client reviews (Google Business / TripAdvisor) are published.
