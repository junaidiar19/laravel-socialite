@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">

                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-muted mb-0">Nama:</p>
                            <p>{{ Auth::user()->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-0">Email:</p>
                            <p>{{ Auth::user()->email }}</p>
                        </div>
                    </div>

                </div>
            </div>

            @if (@$mycourse)
            <h6>Kursus Yang Saya Ikuti</h6>

            <div class="row">
                @foreach ($mycourse['data'] as $d)
                <div class="col-md-6">
                    <div class="card mb-3">
                        <img src="{{ config('auth.gi_host') . '/' . $d['image'] }}" class="img-fluid" alt="">
                        <div class="card-body">
                            <h6>{{ $d['name'] }}</h6>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

        </div>
    </div>
</div>
@endsection
