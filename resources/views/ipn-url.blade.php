<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
</head>
<body class="font-sans antialiased">
    <div id="app" class="col-12">
        <main>
            <div id="ipn-urls" class="container pesapal ipn-urls">
                <table class="table table-striped">
                    <caption>IPN URL VIEW</caption>
                    <tr>
                        <th>URL</th>
                        <th>Created At</th>
                        <th>IPN ID</th>
                        <th>Error</th>
                        <th>Status</th>
                    </tr>
                    @if(!empty($ipn))
                        <tr>
                            <td>{{ $ipn->url }}</td>
                            <td>{{ $ipn->created_date }}</td>
                            <td>{{ $ipn->ipn_id }}</td>
                            <td>{{ json_encode($ipn->error) }}</td>
                            <td>{{ $ipn->status }}</td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="5">No URL found!</td>
                        </tr>
                    @endif
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