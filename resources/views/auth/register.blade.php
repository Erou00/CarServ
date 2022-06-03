@extends('layouts.app')




@section('content')














<!-- Booking Start -->
<div class="container-fluid bg-secondary booking my-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container">
        <div class="row gx-5">
            <div class="col-lg-6 py-5">
                <div class="py-5">
                    <h1 class="text-white mb-4">Certified and Award Winning Car Repair Service Provider</h1>
                    <p class="text-white mb-0">Eirmod sed tempor lorem ut dolores. Aliquyam sit sadipscing kasd ipsum. Dolor ea et dolore et at sea ea at dolor, justo ipsum duo rebum sea invidunt voluptua. Eos vero eos vero ea et dolore eirmod et. Dolores diam duo invidunt lorem. Elitr ut dolores magna sit. Sea dolore sanctus sed et. Takimata takimata sanctus sed.</p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="bg-primary h-100 d-flex flex-column justify-content-center text-center p-5 wow zoomIn" data-wow-delay="0.6s">
                    <h1 class="text-white mb-4">Sign Up</h1>
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="row g-3">


                            <div class="row mb-3">
                                <div class="col-12 col-sm-12">
                                    <div class=""  data-target-input="nearest">
                                        <input type="text" name="first_name"
                                            class="form-control border-0 @error('first_name') is-invalid @enderror "
                                               style="height: 55px;" placeholder="First Name" required>
                                    </div>

                                    @error('first_name')
                                    <span class="invalid-feedback text-white" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>


                            </div>


                            <div class="row mb-3">
                                <div class="col-12 col-sm-12">
                                    <div class=""  data-target-input="nearest">
                                        <input type="text" name="last_name"
                                            class="form-control border-0 @error('last_name') is-invalid @enderror "
                                               style="height: 55px;" placeholder="Last Name" required>
                                    </div>

                                    @error('last_name')
                                    <span class="invalid-feedback text-white" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>


                            </div>

                            <div class="row mb-3">
                                <div class="col-12 col-sm-12">
                                    <div class=""  data-target-input="nearest">
                                        <input type="email" name="email"
                                            class="form-control border-0 @error('email') is-invalid @enderror "
                                               style="height: 55px;" placeholder="Email" required>
                                    </div>

                                    @error('email')
                                    <span class="invalid-feedback text-white" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>


                            </div>


                            <div class="row mb-3">
                                <div class="col-12 col-sm-12">
                                    <div class=""  data-target-input="nearest">
                                        <input type="password" name="password"
                                            class="form-control border-0 @error('password') is-invalid @enderror"
                                               style="height: 55px;" placeholder="Password">
                                    </div>

                                    @error('password')
                                    <span class="invalid-feedback text-white" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="row mb-3">
                                <div class="col-12 col-sm-12">
                                    <div class=""  data-target-input="nearest">
                                        <input type="password" name="password_confirmation"
                                            class="form-control border-0"
                                               style="height: 55px;" placeholder="Confirm Password">
                                    </div>

                                    @error('password')
                                    <span class="invalid-feedback text-white" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>





                            <div class="row">
                                <div class="col-12 col-sm-12">
                                    <button class="btn btn-secondary  py-3 col-12 col-sm-12" type="submit">Sign Up</button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Booking End -->





@endsection
