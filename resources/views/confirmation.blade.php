@extends('frontend.layouts.app')

@section('title', __('Pesapal Payment Confirmation'))

@section('content')
    <div id="app" class="col-12">
        <main>
            <div id="confirmation" class="container pesapal confirmation iframe">
                @if(!empty($orderTrackingId))
                    <p>Your Payment has been received. Please wait for confirmation. </p>
                @else
                    <p>We're having trouble getting the status of your payment. Please contact support.</p>   
                @endif
                <p><a href="{{ config('app.url', '/') }}">Home</a></p>
            </div>
        </main>
    </div><!--app-->
@endsection