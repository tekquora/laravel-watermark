@php
    $layout = config('watermark.layout');
@endphp

@if ($layout['type'] === 'component')
    <x-dynamic-component :component="$layout['component']">
        {{ $slot }}
    </x-dynamic-component>

@elseif ($layout['type'] === 'blade')
    @extends($layout['view'])

    @section('content')
        {{ $slot }}
    @endsection

@else
    {{ $slot }}
@endif
