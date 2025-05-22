@extends('layouts.app')


@section('title')
    Payment Success
@endsection


@section('content')
    <div class="row my-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body text-center">
                    <h1 class="text-success">
                        Payment Successful
                    </h1>
                    <p>
                        Your payment has been successfully proceeded.
                    </p>
                    <a href="{{ route('home') }}" class="btn btn-primary">
                        Back home
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection