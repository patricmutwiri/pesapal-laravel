@extends('frontend.layouts.app')

@section('title', __('Pesapal Registered IPN'))

@section('content')
    <div id="app" class="col-12">
        <main>
            <div id="ipn-urls" class="container pesapal ipn-urls">
                <table class="table table-striped">
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
                    @endforelse
                    <tr>
                        <td colspan="3"><p><a href="{{ config('app.url', '/') }}">Home</a></p></td>
                        <td colspan="2"><p><a href="{{ route('pesapal.ipn.register.view') }}">Register URL</a></p></td>
                    </tr>
                </table>
            </div>
        </main>
    </div><!--app-->
@endsection