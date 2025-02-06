@extends('front-end.layout.main-layout')
@section('title', 'Products')
@section('page-css')
@endsection

@section('content')
    <div class="container-fluid pt-5">
        <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class=" pr-3">Products</span>
        </h2>
        <div class="row px-xl-5 pb-3">
            @if (isset($products))
                @forelse ($products as $product)
                    <div class="col-lg-4 col-md-6 col-sm-6 pb-1">
                        <div class="product-item bg-light mb-4">
                            <div class="product-img position-relative overflow-hidden">
                                @php
                                    $path = $product->images->first()->image_path;
                                @endphp
                                <img class="img-fluid w-100 h-25"
                                    src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt=""
                                    height="200px" style="max-height: 300px;min-height:250px">
                            </div>

                            <div class="text-center py-4">
                                <a class="h6 text-decoration-none text-truncate"
                                    href="{{ route('category.product.detail', ['category_id' => $product->category->id, 'product_id' => $product->id]) }}">{{ $product->name }}</a>
                                <div class="d-flex align-items-center justify-content-center mt-2">
                                    <h5>{{ $product->price ?? '-' }}</h5>
                                </div>

                            </div>
                        </div>
                    </div>
                @empty
                    <h6>No Products Aded Yet</h6>
                @endforelse
            @endif
            <div class="d-flex justify-content-end">
                {{ $products->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
@endsection

@section('page-js')
@endsection
