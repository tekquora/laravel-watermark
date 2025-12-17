@php
    $layout = config('watermark.views.layout');
@endphp

@if ($layout === 'layouts.app' && View::exists('components.app-layout'))
    {{-- Component-based layout (Breeze / Jetstream) --}}
    <x-app-layout>
        <div class="py-6">
            {{ $slot ?? '' }}
            @yield('content')
        </div>
    </x-app-layout>
@elseif (View::exists($layout))
    {{-- Classic Blade layout --}}
    @extends($layout)

    @section('content')
        {{ $slot ?? '' }}
        @yield('content')
    @endsection
@else
    {{-- Fallback --}}
    {{ $slot ?? '' }}
    @yield('content')
@endif
