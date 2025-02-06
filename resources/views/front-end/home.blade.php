@extends('front-end.layout.main-layout')
@section('title', 'Home')
@section('page-css')

@endsection

@section('content')
    <div class="container-fluid pt-5">
        <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class=" pr-3">Categories</span>
        </h2>
        <div class="row px-xl-5 pb-3">

            @if (isset($categories))
                @forelse ($categories as $category)
                    <div class="col-lg-3 col-md-4 col-sm-6 pb-1">
                        <a class="text-decoration-none" href="{{ route('category.products', ['id' => $category->id]) }}">
                            <div class="cat-item d-flex align-items-center mb-4">
                                <div class="overflow-hidden" style="width: 100px; height: 100px;">
                                    <img class="img-fluid" src="img/cat-1.jpg" alt="">
                                </div>
                                <div class="flex-fill pl-3">
                                    <h6>{{ $category->name }}</h6>
                                    <small class="text-body">{{ count($category->products) }} Products</small>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <h6>No Categories Added Yet</h1>
                @endforelse

            @endif

        </div>
        <div class="d-flex justify-content-end">
            {{ $categories->links('pagination::bootstrap-4') }}
        </div>
    </div>
@endsection

@section('page-js')

@endsection
