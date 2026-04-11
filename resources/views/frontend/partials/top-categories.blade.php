<div class="section pb_20 small_pt">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="cat_overlap radius_all_5">
                    <div class="row align-items-center">
                        <div class="col-lg-3 col-md-4">
                            <div class="text-center text-md-start">
                                <h4>{{ gtrans('Top Categories') }}</h4>
                                <p class="mb-2">
                                    {{ gtrans('Browse our most popular product categories') }}
                                </p>
                                <a href="{{ route('page.products.shop') }}" class="btn btn-line-fill btn-sm">
                                    {{ gtrans('Browse All Products') }}
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-9 col-md-8">
                            <div class="cat_slider mt-4 mt-md-0 carousel_slider owl-carousel owl-theme nav_style5"
                                data-loop="true" data-dots="false" data-nav="true" data-margin="30"
                                data-responsive='{"0":{"items": "1"}, "380":{"items": "2"}, "991":{"items": "3"}, "1199":{"items": "4"}}'>
                                @foreach ($topCategories as $category)
                                    <div class="item">
                                        <div class="categories_box">
                                            <a
                                                href="{{ route('page.products.shop', ['category' => $category->slug]) }}">
                                                @if ($category->image)
                                                    <img src="{{ asset($category->image) }}" alt="{{ $category->name }}"
                                                        class="category-icon">
                                                @else
                                                    <i class="flaticon-category"></i>
                                                @endif
                                                <span> {{ gtrans($category->name) }}</span>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
