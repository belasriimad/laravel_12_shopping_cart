@extends('layouts.app')

@section('content')
    <div class="row my-5">
        <div class="row my-3">
            <div class="col-md-12 d-flex justify-content-between">
                @if(session()->has('cart'))
                    <a href="{{ route('cart.index') }}"
                        class="text-decoration-none text-black"
                    >
                        <span class="fs-4 fw-bold">
                            <i class="fas fa-shopping-cart fa-xl"></i>
                            ({{ count(session()->get('cart')) }})  
                        </span> 
                    </a>
                    <span class="fs-4 text-danger fw-bold">
                        ${{ session()->get('cartItemsTotal') }}
                    </span>
                @else
                    <span class="fs-4 fw-bold">
                        <i class="fas fa-shopping-cart fa-xl"></i>(0) 
                    </span>
                @endif
            </div>
        </div>
        @foreach ($products as $product)
            <div class="col-md-4 mb-2">
                <div class="card h-100">
                    <img src="{{ asset($product->image) }}" alt="Product Image" class="card-img-top">
                    <div class="card-body">
                        <div class="card-title">
                            {{ $product->name }}
                        </div>
                        <p class="card-text">
                            {{ $product->description }}
                        </p>
                        <p>
                            <span class="fw-bold text-danger">
                                ${{ $product->price }}
                            </span>
                        </p>
                        <form action="{{ route('cart.add') }}" method="post">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-shopping-cart"></i> add to cart
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection