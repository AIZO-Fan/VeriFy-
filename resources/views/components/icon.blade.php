@props(['name'=>'shield'])
<svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    @switch($name)
    @case('cart')
    <path d="M6 9h6l5 24h22l4-17H14M20 40h.1M36 40h.1" />@break
    @case('link')
    <rect x="7" y="6" width="34" height="28" rx="5" />
    <path d="M7 14h34M17 24h12M24 30v12M16 42h16" />@break
    @case('brain')
    <path d="M24 40V9C12 1 3 15 10 22 1 34 14 43 24 36M24 9c12-8 21 6 14 13 9 12-4 21-14 14M14 16l6 6-5 7M34 16l-6 6 5 7" />@break
    @case('lock')
    <rect x="9" y="20" width="30" height="23" rx="5" />
    <path d="M16 20v-8a8 8 0 0 1 16 0v8M24 29v5" />@break
    @case('report')
    <path d="M12 5h24v37H12zM18 13h12M18 20h12M18 27h8M18 34h12" />@break
    @default
    <path d="M24 4L7 11v12c0 11 17 21 17 21s17-10 17-21V11zM16 24l6 6 11-13" />
    @endswitch
</svg>