<svg viewBox="0 0 96 96" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" {{ $attributes }}>
    <defs>
        <linearGradient id="logo-rim" x1="16" y1="10" x2="80" y2="86" gradientUnits="userSpaceOnUse">
            <stop offset="0" stop-color="#b9f5ff"/>
            <stop offset="0.45" stop-color="#72cde8"/>
            <stop offset="1" stop-color="#184763"/>
        </linearGradient>
        <radialGradient id="logo-core" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(34 24) rotate(48) scale(64)">
            <stop offset="0" stop-color="#163d57"/>
            <stop offset="0.55" stop-color="#0a2135"/>
            <stop offset="1" stop-color="#040b16"/>
        </radialGradient>
        <linearGradient id="logo-metal" x1="29" y1="22" x2="67" y2="71" gradientUnits="userSpaceOnUse">
            <stop offset="0" stop-color="#eefcff"/>
            <stop offset="0.3" stop-color="#a7eefd"/>
            <stop offset="0.68" stop-color="#5baecb"/>
            <stop offset="1" stop-color="#204860"/>
        </linearGradient>
        <linearGradient id="logo-metal-dark" x1="33" y1="25" x2="69" y2="68" gradientUnits="userSpaceOnUse">
            <stop offset="0" stop-color="#d7f9ff"/>
            <stop offset="0.45" stop-color="#77bed9"/>
            <stop offset="1" stop-color="#0d314b"/>
        </linearGradient>
        <radialGradient id="logo-dot" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(48 73) rotate(90) scale(8)">
            <stop offset="0" stop-color="#d9fbff"/>
            <stop offset="0.35" stop-color="#82def0"/>
            <stop offset="1" stop-color="#15394f"/>
        </radialGradient>
        <filter id="logo-shadow" x="-20%" y="-20%" width="140%" height="160%">
            <feDropShadow dx="0" dy="4" stdDeviation="4" flood-color="#07121f" flood-opacity="0.32"/>
        </filter>
    </defs>

    <g filter="url(#logo-shadow)">
        <circle cx="48" cy="48" r="44" fill="url(#logo-rim)"/>
        <circle cx="48" cy="48" r="40.5" fill="url(#logo-core)"/>
        <path d="M20 48c4-14 12-25 27-31-11 9-18 19-21 31 3 12 10 22 21 31-15-6-23-17-27-31Z" fill="#0b2032" opacity="0.62"/>
        <path d="M76 48c-4-14-12-25-27-31 11 9 18 19 21 31-3 12-10 22-21 31 15-6 23-17 27-31Z" fill="#0b2032" opacity="0.62"/>
        <path d="M48 17c11 0 20 8 20 18 0 6-3 10-7 15L50 63h13v6H42V58l14-15c4-4 6-7 6-11 0-7-6-13-14-13-7 0-12 4-15 10h-7c4-12 12-18 22-18Z" fill="url(#logo-metal-dark)"/>
        <path d="M34 28h32l-4 8H34l4-8Z" fill="url(#logo-metal)"/>
        <path d="M27 43h23v-9h9v9h11l-5 9H56v19h-8V52H22l5-9Z" fill="url(#logo-metal)"/>
        <path d="M28 43h20v3H26.4L28 43Zm28 0h13l-1.6 3H56v-3Zm-8-9h8v36h-4V38h-4v-4Z" fill="#e8fcff" opacity="0.45"/>
        <circle cx="48" cy="73" r="7.5" fill="url(#logo-dot)"/>
        <circle cx="45.2" cy="70.6" r="2.2" fill="#eefcff" opacity="0.75"/>
        <circle cx="48" cy="48" r="39.5" fill="none" stroke="#b6efff" stroke-opacity="0.18"/>
    </g>
</svg>
