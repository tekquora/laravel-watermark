@if (View::exists(config('watermark.views.layout')))
    @extends(config('watermark.views.layout'))
@endif

@if (isset($slot))
    {{ $slot }}
@else
    @yield('content')
@endif
