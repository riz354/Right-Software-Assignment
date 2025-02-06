<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Assignment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>


    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Navbar</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                </ul>
                @if (!Auth::check())
                    <a class="nav-link " href="{{ route('login') }}" tabindex="-1" aria-disabled="true"><button
                            class="btn btn-outline-success me-2" type="submit">Login</button></a>
                @else
                    <a href="#" class="nav-link dropdown-toggle me-2" data-bs-toggle="dropdown">
                        <span class="d-none d-md-inline">{{ Auth::user()->name ?? '' }}</span>
                    </a>
                    <ul class="dropdown-menu  dropdown-menu-end" style="border: none">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger me-2">Log out</button>
                        </form>
                    </ul>
                @endif
                <a class="nav-link " href="{{ route('category.index') }}" tabindex="-1" aria-disabled="true"><button
                        class="btn btn-outline-success" type="submit">Dashbaord</button></a>
            </div>
        </div>
    </nav>

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

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
