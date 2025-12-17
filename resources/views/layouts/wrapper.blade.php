@php
    $layout = config('watermark.views.layout');
@endphp

@if (View::exists($layout))
    @extends($layout)

    @section('content')
        @yield('watermark-content')
    @endsection
@else
    @yield('watermark-content')
@endif
