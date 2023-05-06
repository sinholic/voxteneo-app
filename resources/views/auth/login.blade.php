@include('layouts.head')

<body class="form-membership">

    <!-- begin::preloader-->
    <div class="preloader">
        <div class="preloader-icon"></div>
    </div>
    <!-- end::preloader -->

    <div class="form-wrapper dark">

        <!-- logo -->
        <div id="logo">
            <img class="logo" src="assets/media/image/logo.svg" alt="image">
        </div>
        <!-- ./ logo -->

        <h5 class="pe-inverse">Sign in</h5>

        <!-- form -->
        <form method="POST" action="{{ route('login') }}">
            @csrf
            @if(session()->has("danger"))
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close text-white" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {{ session()->get("danger") }}
                    </div>
                </div>
            </div>
        @endif
            <div class="form-group">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group d-flex justify-content-between">
                <a class="pe-inverse" href="{{ route('register') }}">Register</a>
            </div>
            <button class="btn btn-primary btn-block">Sign in</button>
        </form>
        <!-- ./ form -->

    </div>

    <!-- Plugin scripts -->
    <script src="{{ asset('vendors/bundle.js') }}"></script>

    <!-- App scripts -->
    <script src="{{ asset('assets/js/app.js') }}"></script>
</body>

</html>
