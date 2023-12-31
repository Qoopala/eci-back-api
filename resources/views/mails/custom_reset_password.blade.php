@extends('vendor.notifications.email')

@section('content')
    <div style="background-color: #f5f5f5; padding: 20px;">
        {{-- Contenido personalizado --}}
        # {{ $greeting }}
        <h1>Custom File</h1>
        @foreach ($introLines as $line)
            {{ $line }}
        @endforeach

        @isset($actionText)
            <div style="margin-top: 20px;">
                <a href="{{ $actionUrl }}" style="background-color: #007BFF; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
                    {{ $actionText }}
                </a>
            </div>
        @endisset

        @foreach ($outroLines as $line)
            {{ $line }}
        @endforeach

        @if (! empty($salutation))
            <p>{{ $salutation }}</p>
        @else
            <p>@lang('Regards'),<br>{{ config('app.name') }}</p>
        @endif
    </div>
@endsection
