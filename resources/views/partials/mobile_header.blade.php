<div class="mobile-header-active mobile-header-wrapper-style">
    <div class="mobile-header-wrapper-inner">

        {{-- Top --}}
        <div class="mobile-header-top">
            <div class="mobile-header-logo">
                <a href="javascript:;">
                    <img src="{{ asset("assets/imgs/logo.jpg") }}" alt="logo">
                </a>
            </div>

            
        </div>

        <div class="mobile-header-content-area">

           
            {{-- Categories --}}
            <div class="mobile-menu-wrap mobile-header-border">
                <div class="main-categori-wrap mobile-header-border">
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

                {{-- Menu --}}
                <nav>
                    <ul class="mobile-menu">

                        <li>
                            <a href="javascript:;">
                                <i class="fi-rs-home mr-5"></i> Home
                            </a>
                        </li>

                        <li>
                            <a href="javascript:;">
                                <i class="fi-rs-shopping-bag mr-5"></i> Shop
                            </a>
                        </li>

                        <li>
                            <a href="javascript:;">
                                <i class="fi-rs-info mr-5"></i> About
                            </a>
                        </li>

                        <li>
                            <a href="javascript:;">
                                <i class="fi-rs-phone-call mr-5"></i> Contact
                            </a>
                        </li>

                    </ul>
                </nav>
            </div>

            {{-- User / Info --}}
            <div class="mobile-header-info-wrap mobile-header-border">

               

                {{-- LOGIN BUTTON (TRIGGERS MODAL) --}}
                <div class="single-mobile-header-info">
                    <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#loginModal">
                        <i class="fi-rs-user mr-5"></i> Login / Register
                    </a>
                </div>

               

            </div>

          

        </div>
    </div>
</div>