<div class="verify-activity-empty">
    <span class="verify-activity-empty-icon" aria-hidden="true">
        <svg
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="1.6"
            stroke-linecap="round"
            stroke-linejoin="round"
        >
            <path d="M3 11a9 9 0 1 1 2.6 6.4" />
            <path d="M3 4v7h7" />
            <path d="M12 7v5l3 2" />
        </svg>
    </span>

    <span class="verify-activity-empty-label">{{ $label }}</span>

    <h3>{{ $title }}</h3>

    <p>{{ $description }}</p>

    <a class="verify-activity-action" href="{{ $url }}">
        {{ $button }}
        <span aria-hidden="true">→</span>
    </a>
</div>