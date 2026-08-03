@props(['id' => 1, 'color' => 'text-red-600'])

@switch($id)
    @case(1)
        {{-- 1. Simplify Your Booking Experience --}}
        <svg class="w-11 h-11 relative z-10 {{ $color }}" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="13" y="6" width="22" height="36" rx="4" fill="#FEE2E2" stroke="currentColor" stroke-width="2.5"/>
            <rect x="16" y="10" width="16" height="23" rx="2" fill="#FFFFFF" stroke="currentColor" stroke-width="2"/>
            <path d="M20 22L23 25L28 18" stroke="#DC2626" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M30 28H40C41.1 28 42 28.9 42 30V32C40.9 32 40 32.9 40 34C40 35.1 40.9 36 42 36V38C42 39.1 41.1 40 40 40H30C28.9 40 28 39.1 28 38V36C29.1 36 30 35.1 30 34C30 32.9 29.1 32 28 32V30C28 28.9 28.9 28 30 28Z" fill="#DC2626" stroke="#B91C1C" stroke-width="1.5"/>
            <circle cx="33" cy="34" r="1.5" fill="#FFFFFF"/>
            <path d="M36 32V36" stroke="#FFFFFF" stroke-width="1.5" stroke-linecap="round"/>
        </svg>
        @break

    @case(2)
        {{-- 2. Wide Selection of Travel Products --}}
        <svg class="w-11 h-11 relative z-10 {{ $color }}" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="24" cy="24" r="15" fill="#FCE7F3" stroke="currentColor" stroke-width="2.5"/>
            <path d="M9 24H39" stroke="currentColor" stroke-width="2" stroke-dasharray="2 2"/>
            <path d="M24 9C28.5 15 28.5 33 24 39C19.5 33 19.5 15 24 9Z" stroke="currentColor" stroke-width="2"/>
            <path d="M28 29L38 21L41 22L36 29L40 33L37 35L31 32L26 36L25 34L28 29Z" fill="#DB2777" stroke="#BE185D" stroke-width="1.5" stroke-linejoin="round"/>
            <circle cx="16" cy="18" r="3" fill="#DC2626"/>
            <path d="M16 21V25" stroke="#DC2626" stroke-width="2" stroke-linecap="round"/>
        </svg>
        @break

    @case(3)
        {{-- 3. Exclusive Deals & Promotions --}}
        <svg class="w-11 h-11 relative z-10 {{ $color }}" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M14 10H28L40 22L28 34L16 22V10H14Z" fill="#FEE2E2" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"/>
            <circle cx="21" cy="16" r="2.5" fill="#DC2626"/>
            <path d="M23 28L31 20" stroke="#DC2626" stroke-width="2.5" stroke-linecap="round"/>
            <circle cx="25" cy="21" r="2" fill="#DC2626"/>
            <circle cx="29" cy="27" r="2" fill="#DC2626"/>
            <path d="M36 10L37 13L40 14L37 15L36 18L35 15L32 14L35 13L36 10Z" fill="#F59E0B"/>
            <path d="M12 28L13 30L15 31L13 32L12 34L11 32L9 31L11 30L12 28Z" fill="#F59E0B"/>
        </svg>
        @break

    @case(4)
        {{-- 4. Trusted Booking Expert Since 2017 --}}
        <svg class="w-11 h-11 relative z-10 {{ $color }}" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M24 6L38 12V23C38 32.5 32 39.5 24 43C16 39.5 10 32.5 10 23V12L24 6Z" fill="#D1FAE5" stroke="#059669" stroke-width="2.5" stroke-linejoin="round"/>
            <path d="M24 11L33 15V23C33 30 29 35.5 24 38C19 35.5 15 30 15 23V15L24 11Z" fill="#10B981" fill-opacity="0.2"/>
            <path d="M19 23L23 27L30 18" stroke="#059669" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"/>
            <circle cx="24" cy="14" r="1.5" fill="#059669"/>
        </svg>
        @break

    @case(5)
        {{-- 5. Affectionate Customer Support --}}
        <svg class="w-11 h-11 relative z-10 {{ $color }}" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="24" cy="18" r="7" fill="#DBEAFE" stroke="currentColor" stroke-width="2.5"/>
            <path d="M12 38C12 32 17 28 24 28C31 28 36 32 36 38" fill="#DBEAFE" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
            <path d="M14 18C14 12.5 18.5 8 24 8C29.5 8 34 12.5 34 18" stroke="#2563EB" stroke-width="2.5" stroke-linecap="round"/>
            <rect x="12" y="16" width="3" height="6" rx="1.5" fill="#2563EB"/>
            <rect x="33" y="16" width="3" height="6" rx="1.5" fill="#2563EB"/>
            <path d="M13 22V25C13 27 16 29 20 29" stroke="#2563EB" stroke-width="2" stroke-linecap="round"/>
            <circle cx="21" cy="29" r="2" fill="#2563EB"/>
            <circle cx="36" cy="13" r="6" fill="#2563EB"/>
            <text x="34" y="16" fill="#FFFFFF" font-size="9" font-weight="bold">?</text>
        </svg>
        @break

    @case(6)
        {{-- 6. Seamless Local Payment & Ticketing --}}
        <svg class="w-11 h-11 relative z-10 {{ $color }}" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M14 16C16.5 12 21 10 26 10C33 10 38 15 38 22" stroke="#F59E0B" stroke-width="2.5" stroke-linecap="round"/>
            <path d="M35 18L38 22L42 19" stroke="#F59E0B" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M34 32C31.5 36 27 38 22 38C15 38 10 33 10 26" stroke="#F59E0B" stroke-width="2.5" stroke-linecap="round"/>
            <path d="M13 30L10 26L6 29" stroke="#F59E0B" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
            <circle cx="20" cy="26" r="8" fill="#FEF3C7" stroke="#D97706" stroke-width="2.5"/>
            <text x="17" y="30" fill="#D97706" font-size="11" font-weight="bold">₱</text>
            <circle cx="30" cy="20" r="7" fill="#F59E0B" stroke="#B45309" stroke-width="2"/>
            <text x="27.5" y="23.5" fill="#FFFFFF" font-size="9" font-weight="bold">✓</text>
        </svg>
        @break
@endswitch
