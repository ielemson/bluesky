 <div class="header-middle header-middle-ptb-1 d-none d-lg-block">
        <div class="container">
            <div class="header-wrap">
                <div class="logo logo-width-1">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('assets/imgs/logo.jpg') }}" alt="logo">
                    </a>
                </div>

                <div class="header-right">
                    <div class="search-style-2">
                        <form action="" method="GET">
                            <select class="select-active" name="category">
                                <option value="">{{ gtrans('All Categories') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->slug }}">{{ gtrans($category->name) }}</option>
                                @endforeach
                            </select>
                            <input type="text" name="search" placeholder="{{ gtrans('Search for items...') }}">
                        </form>
                    </div>

                    <div class="header-action-right">
                        <div class="header-action-2">
                           {{-- <div class="header-action-icon-2">
                            <a href="javascript:;" class="wishlist-trigger">
                                <img class="svgInject" alt="wishlist" src="{{ asset('assets/imgs/theme/icons/icon-user-add.svg') }}">
                              
                            </a>
                        </div> --}}

                            <div class="header-action-icon-2">
                                {{-- <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#loginModal">
                                    <img alt="cart" src="{{ asset('assets/imgs/theme/icons/icon-user-add.svg') }}">
                                </a> --}}
{{-- 
                                <div class="cart-dropdown-wrap cart-dropdown-hm2">
                                    @include('components.cart-dropdown', [
                                        'cartItems' => \Cart::getContent(),
                                        'cartTotal' => \Cart::getTotal(),
                                    ])
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>


    {{-- BOTTOM HEADER --}}
    <div class="header-bottom header-bottom-bg-color sticky-bar">
        <div class="container">
            <div class="header-wrap header-space-between position-relative main-nav">

                <div class="logo logo-width-1 d-block d-lg-none">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('assets/imgs/logo.jpg') }}" alt="logo">
                    </a>
                </div>

                <div class="header-nav d-none d-lg-flex">
                    <div class="main-categori-wrap d-none d-lg-block">
                        <a class="categori-button-active" href="#">
                            <span class="fi-rs-apps"></span> {{ gtrans('Browse Categories') }}
                        </a>

                        <div class="categori-dropdown-wrap categori-dropdown-active-large">
                            <ul>
                                @foreach ($categories->take(10) as $category)
                                    <li class="{{ $category->children && $category->children->count() ? 'has-children' : '' }}">
                                        <a href="{{ route('page.products.category', $category->slug) }}">
                                            @if(!empty($category->image))
                                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" style="width:20px;height:20px;object-fit:cover;border-radius:50%;margin-right:8px;">
                                            @endif
                                            {{ gtrans($category->name) }}
                                        </a>

                                        @if ($category->children && $category->children->count())
                                            <div class="dropdown-menu">
                                                <ul class="mega-menu d-lg-flex">
                                                    <li class="mega-menu-col col-lg-7">
                                                        <ul class="d-lg-flex">
                                                            <li class="mega-menu-col col-lg-6">
                                                                <ul>
                                                                    <li><span class="submenu-title">{{ gtrans('Subcategories') }}</span></li>
                                                                    @foreach ($category->children->take(8) as $child)
                                                                        <li>
                                                                            <a class="dropdown-item nav-link nav_item"
                                                                               href="{{ route('page.products.category', ['category' => $child->slug]) }}">
                                                                                {{ gtrans($child->name) }}
                                                                                @if(isset($child->products_count) && $child->products_count > 0)
                                                                                    <span class="badge bg-light text-dark float-end">{{ $child->products_count }}</span>
                                                                                @endif
                                                                            </a>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </li>

                                                            <li class="mega-menu-col col-lg-6">
                                                                <ul>
                                                                    <li><span class="submenu-title">{{ gtrans('Explore') }}</span></li>
                                                                    <li>
                                                                        <a class="dropdown-item nav-link nav_item"
                                                                           href="{{ route('page.products.category', ['category' => $category->slug]) }}">
                                                                            {{ gtrans('View All') }} {{ gtrans($category->name) }}
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </li>
                                                        </ul>
                                                    </li>

                                                    <li class="mega-menu-col col-lg-5">
                                                        <div class="header-banner2">
                                                            @if(!empty($category->image))
                                                                <img src="{{ asset($category->image) }}" alt="{{ $category->name }}">
                                                            @else
                                                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}">
                                                            @endif
                                                            <div class="banne_info">
                                                                <h6>{{ gtrans('Shop Now') }}</h6>
                                                                <h4>{{ gtrans($category->name) }}</h4>
                                                                <a href="{{ route('page.products.category', ['category' => $category->slug]) }}">
                                                                    {{ gtrans('Discover More') }}
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        @endif
                                    </li>
                                @endforeach

                                @if($categories->count() > 10)
                                    <li>
                                        <ul class="more_slide_open" style="display: none;">
                                            @foreach ($categories->slice(10) as $category)
                                                <li>
                                                    <a href="{{ route('page.products.category', ['category' => $category->slug]) }}">
                                                        {{ gtrans($category->name) }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                    <li>
                                        <div class="more_categories">{{ gtrans('Show more...') }}</div>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    <div class="main-menu main-menu-padding-1 main-menu-lh-2 d-none d-lg-block">
                        <nav>
                            <ul>
                                <li>
                                    <a class="active" href="{{ route('home') }}">{{ gtrans('Home') }}</a>
                                </li>
                                {{-- <li>
                                    <a href="{{ route('home') }}">{{ gtrans('About') }}</a>
                                </li> --}}
                                <li>
                                    <a href="{{ route('page.products.shop') }}">{{ gtrans('Shop') }}</a>
                                </li>
                                {{-- <li>
                                    <a href="#">{{ gtrans('Contact') }}</a>
                                </li> --}}
                            </ul>
                        </nav>
                    </div>
                </div>

               

                <div class="header-action-right d-block d-lg-none">
                   
                        
             <div class="header-action-icon-2">
    <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#loginModal" class="d-flex align-items-center">
        <img alt="Account" width="20" height="20" src="{{ asset('assets/imgs/theme/icons/icon-user.svg') }}">
        <span class="ms-1 small fw-bold">Account</span>
    </a>
</div>
                   
                </div>

            </div>
        </div>
    </div>