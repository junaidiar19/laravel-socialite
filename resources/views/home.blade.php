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

            <h6>Kursus Yang Saya Ikuti</h6>

            <div class="card">
                <div class="card-header"></div>
                <div class="card-body">
                    
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
