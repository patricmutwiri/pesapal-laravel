<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pesapal | Register IPN</title>
    <meta name="description" content="@yield('meta_description', 'Pesapal for laravel')">
    <meta name="author" content="@yield('meta_author', 'Patrick Mutwiri')">
    @yield('meta')

    @stack('before-styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
    @stack('after-styles')
</head>
<body>

<div id="app" class="col-12">
        <main>
            <div id="register-url" class="container pesapal register-url">
                <form action="{{ route('pesapal.ipn.register') }}" method="post" class="col-12">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group row">
                        <label for="ipn_url" class="col-md-4 col-form-label text-md-right">@lang('URL')</label>

                        <div class="col-md-8">
                            <input type="text" name="ipn_url" id="ipn_url" class="form-control" value="{{ old('ipn_url') }}" placeholder="{{ __('https://patric.xyz/ipns') }}" required autofocus autocomplete="ipn_url" />
                        </div>
                    </div><!--form-group-->

                    <div class="form-group row">
                        <label for="ipn_method" class="col-md-4 col-form-label text-md-right">@lang('HTTP Method')</label>

                        <div class="col-md-8">
                            <select name="ipn_method" id="ipn_method" class="form-control" required>
                                <option>--SELECT--</option>
                                <option value="GET">GET</option>
                                <option value="POST">POST</option>
                            </select>
                        </div>
                    </div><!--form-group-->

                    <div class="form-group">
                        <button id="save-ipn" type="submit" class="btn text-right btn-default-border-blk">Register</button>
                    </div>
                </form>
            </div>
        </main>
    </div><!--app-->
</body>
</html>