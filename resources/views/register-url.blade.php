@extends('frontend.layouts.app')

@section('title', __('Pesapal Register IPN'))

@section('content')
    <div id="app" class="col-12">
        <main>
            <div id="register-url" class="container pesapal register-url">
                <form action="{{ route('pesapal.ipn.register') }}" method="post" class="col-12">
                    @csrf
                    <div class="form-group row">
                        <label for="ipn_url" class="col-md-4 col-form-label text-md-right">@lang('URL')</label>

                        <div class="col-md-6">
                            <input type="text" name="ipn_url" id="ipn_url" class="form-control" value="{{ old('ipn_url') }}" placeholder="{{ __('https://patric.xyz/ipns') }}" required autofocus autocomplete="ipn_url" />
                        </div>
                    </div><!--form-group-->

                    <div class="form-group row">
                        <label for="ipn_method" class="col-md-4 col-form-label text-md-right">@lang('HTTP Method')</label>

                        <div class="col-md-6">
                            <input type="text" name="ipn_method" id="ipn_method" class="form-control" value="{{ old('ipn_method') }}" placeholder="{{ __('POST') }}" required autofocus autocomplete="ipn_method" />
                        </div>
                    </div><!--form-group-->

                    <div class="form-group">
                        <button id="save-ipn" type="submit" class="btn text-right btn-default-border-blk">Register</button>
                    </div>
                </form>
            </div>
        </main>
    </div><!--app-->
@endsection