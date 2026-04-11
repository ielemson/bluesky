<footer class="main">
    <section class="newsletter p-30 text-white wow fadeIn animated">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7 mb-md-3 mb-lg-0">
                    <div class="row align-items-center">
                        <div class="col flex-horizontal-center">
                            <img class="icon-email" src="{{ asset('assets/imgs/theme/icons/icon-email.svg') }}" alt="newsletter">
                            <h4 class="font-size-20 mb-0 ml-3">{{ gtrans('Subscribe to Our Newsletter') }}</h4>
                        </div>
                        <div class="col my-4 my-md-0 des">
                            <h5 class="font-size-15 ml-4 mb-0">
                                {{ gtrans('Get updates on new products, special offers, and exclusive deals.') }}
                            </h5>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <form class="form-subcriber d-flex wow fadeIn animated" action="#" method="POST">
                        @csrf
                        <input
                            type="email"
                            name="email"
                            class="form-control bg-white font-small"
                            placeholder="{{ gtrans('Enter your email address') }}"
                            required
                        >
                        <button class="btn bg-dark text-white" type="submit">
                            {{ gtrans('Subscribe') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <div class="container pb-20 wow fadeIn animated">
        <div class="row">
            <div class="col-12 mb-20">
                <div class="footer-bottom"></div>
            </div>

            <div class="col-lg-6">
                <p class="float-md-left font-sm text-muted mb-0">
                    &copy; {{ date('Y') }}, <strong class="text-brand">Bluesky Mart</strong>. {{ gtrans('All rights reserved.') }}
                </p>
            </div>

            <div class="col-lg-6">
                <p class="text-lg-end text-start font-sm text-muted mb-0">
                    {{ gtrans('Powered by Bluesky Mart') }}
                </p>
            </div>
        </div>
    </div>
</footer>