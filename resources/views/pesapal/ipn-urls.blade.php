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
                <caption>Registered IPNs</caption>
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
                        <td>{{ $ipn->error }}</td>
                        <td>{{ $ipn->status }}</td>
                    </tr>
                @empty
                    <p>No URLs found!</p>
                @endforelse
                <p><a href="{{ config('app.url', '/') }}">Home</a></p>
            </table>
        </div>
    </main>
</div><!--app-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>
</html>