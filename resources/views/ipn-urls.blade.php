<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pesapal | Registered IPNs</title>
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
            <div id="ipn-urls" class="container pesapal ipn-urls">
                <table class="table table-striped">
                    <caption>Registered IPN URLs</caption>
                    <tr>
                        <th>URL</th>
                        <th>Created At</th>
                        <th>IPN ID</th>
                        <th>Error</th>
                        <th>Status</th>
                    </tr>
                    @forelse ($ipns as $ipn)
                        <tr>
                            <td>{{ $ipn->url }}</td>
                            <td>{{ $ipn->created_date }}</td>
                            <td>{{ $ipn->ipn_id }}</td>
                            <td>{{ json_encode($ipn->error) }}</td>
                            <td>{{ $ipn->status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No URLs found!</td>
                        </tr>
                    @endforelse
                    <tr>
                        <td colspan="3"><p><a href="{{ config('app.url', '/') }}">Home</a></p></td>
                        <td colspan="2"><p><a href="{{ route('pesapal.ipn.register.view') }}">Register URL</a></p></td>
                    </tr>
                </table>
            </div>
        </main>
    </div><!--app-->
</body>
</html>