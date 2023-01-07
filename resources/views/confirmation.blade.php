@extends('frontend.layouts.app')

@section('title', __('Pesapal Payment Confirmation'))

@section('content')
    <div id="app" class="col-12">
        <main>
            <div id="confirmation" class="container pesapal confirmation iframe">
                <p>Your Payment has been received. </p>
                <a href="{{ config('app.url', '/') }}">Home</a>
            </div>
        </main>
    </div><!--app-->
@endsection