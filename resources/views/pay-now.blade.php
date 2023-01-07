@extends('frontend.layouts.app')

@section('title', __('Pesapal Pay Now'))

@section('content')
    <div id="app" class="col-12">
        <main>
            <div id="pay-now" class="container pesapal pay-now iframe">
                <iframe src="{{ $data['redirect_url'] }}" width="100%" height="700px">
                    <p>Browser unable to load iFrame</p>
                </iframe>
                <p><a href="{{ config('app.url', '/') }}">Home</a></p>
            </div>
        </main>
    </div><!--app-->
@endsection