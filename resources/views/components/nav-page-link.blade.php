@props(['active' => false])

<a {{ $attributes }} class="relative transition-colors duration-200 hover:text-white hover:[text-shadow:0_0_1px_rgba(255,255,255,0.8)] {{ $active ? 'underline underline-offset-4 decoration-1' : '' }} "aria-current="{{ $active ? 'page' : false }}">
       {{ $slot }}
</a>
