@extends('layouts.app')
@section('styles')

    <style>
h1 {
    text-align: center;
}
h2 {
    margin: 0;
}
#multi-step-form-container {
    margin-top: 5rem;
}
.text-center {
    text-align: center;
}
.mx-auto {
    margin-left: auto;
    margin-right: auto;
}
.pl-0 {
    padding-left: 0;
}
.button {
    padding: 0.7rem 1.5rem;
    border: 1px solid #d81324;
    background-color: #d81324;
    color: #fff;
    border-radius: 5px;
    cursor: pointer;
}
.submit-btn {
    border: 1px solid #0e9594;
    background-color: #0e9594;
}
.mt-3 {
    margin-top: 2rem;
}
.d-none {
    display: none;
}
.form-step {
    border: 1px solid rgba(0, 0, 0, 0.1);
    border-radius: 20px;
    padding: 3rem;
}
.font-normal {
    font-weight: normal;
}
ul.form-stepper {
    counter-reset: section;
    margin-bottom: 3rem;
}
ul.form-stepper .form-stepper-circle {
    position: relative;
}
ul.form-stepper .form-stepper-circle span {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translateY(-50%) translateX(-50%);
}
.form-stepper-horizontal {
    position: relative;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: justify;
    -ms-flex-pack: justify;
    justify-content: space-between;
}
ul.form-stepper > li:not(:last-of-type) {
    margin-bottom: 0.625rem;
    -webkit-transition: margin-bottom 0.4s;
    -o-transition: margin-bottom 0.4s;
    transition: margin-bottom 0.4s;
}
.form-stepper-horizontal > li:not(:last-of-type) {
    margin-bottom: 0 !important;
}
.form-stepper-horizontal li {
    position: relative;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-flex: 1;
    -ms-flex: 1;
    flex: 1;
    -webkit-box-align: start;
    -ms-flex-align: start;
    align-items: start;
    -webkit-transition: 0.5s;
    transition: 0.5s;
}
.form-stepper-horizontal li:not(:last-child):after {
    position: relative;
    -webkit-box-flex: 1;
    -ms-flex: 1;
    flex: 1;
    height: 1px;
    content: "";
    top: 32%;
}
.form-stepper-horizontal li:after {
    background-color: #dee2e6;
}
.form-stepper-horizontal li.form-stepper-completed:after {
    background-color: #d81324;
}
.form-stepper-horizontal li:last-child {
    flex: unset;
}
ul.form-stepper li a .form-stepper-circle {
    display: inline-block;
    width: 40px;
    height: 40px;
    margin-right: 0;
    line-height: 1.7rem;
    text-align: center;
    background: rgba(0, 0, 0, 0.38);
    border-radius: 50%;
}
.form-stepper .form-stepper-active .form-stepper-circle {
    background-color: #d81324 !important;
    color: #fff;
}
.form-stepper .form-stepper-active .label {
    color: #d81324 !important;
    font-weight: bold;
}
.form-stepper .form-stepper-active .form-stepper-circle:hover {
    background-color: #d81324 !important;
    color: #fff !important;
}
.form-stepper .form-stepper-unfinished .form-stepper-circle {
    background-color: #f8f7ff;
}
.form-stepper .form-stepper-completed .form-stepper-circle {
    background-color: #0e9594 !important;
    color: #fff;
}
.form-stepper .form-stepper-completed .label {
    color: #0e9594 !important;
}
.form-stepper .form-stepper-completed .form-stepper-circle:hover {
    background-color: #0e9594 !important;
    color: #fff !important;
}
.form-stepper .form-stepper-active span.text-muted {
    color: #fff !important;
}
.form-stepper .form-stepper-completed span.text-muted {
    color: #fff !important;
}
.form-stepper .label {
    font-size: 1rem;
    margin-top: 0.5rem;
}
.form-stepper a {
    cursor: default;
}

.htmlbtn{
 width: 33%;
 height:auto;
 padding: 30px;
 background-size: cover;
 border: none;

 min-height: 300px

 }
 .htmlbtn.active{
    border: 2px solid #d81324;
 }

</style>
@endsection
@section('content')
<div class="container mt-5">
    <div class="main-body">

        <div>
            <h1>CREATE A NEW DEMANDE</h1>
            <div id="multi-step-form-container">
                <!-- Form Steps / Progress Bar -->
                <ul class="form-stepper form-stepper-horizontal text-center mx-auto pl-0">
                    <!-- Step 1 -->
                    <li class="form-stepper-active text-center form-stepper-list" step="1">
                        <a class="mx-2">
                            <span class="form-stepper-circle">
                                <span>1</span>
                            </span>
                            <div class="label">Choose a service</div>
                        </a>
                    </li>
                    <!-- Step 2 -->
                    <li class="form-stepper-unfinished text-center form-stepper-list" step="2">
                        <a class="mx-2">
                            <span class="form-stepper-circle text-muted">
                                <span>2</span>
                            </span>
                            <div class="label text-muted">Choose a car</div>
                        </a>
                    </li>
                    <!-- Step 3 -->
                    <li class="form-stepper-unfinished text-center form-stepper-list" step="3">
                        <a class="mx-2">
                            <span class="form-stepper-circle text-muted">
                                <span>3</span>
                            </span>
                            <div class="label text-muted">Confirm demande</div>
                        </a>
                    </li>
                </ul>

                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
               @endif
                <!-- Step Wise Form Content -->
                <form id="userAccountSetupForm" name="userAccountSetupForm" enctype="multipart/form-data" method="POST"
                action="{{route('demandes.storeDemandes')}}">
                    <!-- Step 1 Content -->
                    @csrf


                    <section id="step-1" class="form-step">
                        <h2 class="font-normal">Choose a service</h2>
                        <!-- Step 1 input fields -->
                        <div class="mt-3" id="services">


                            @foreach ($services as $service)
                                <button id="htmlbtn{{$service->id}}" class="htmlbtn" value="{{$service->id}}">

                                    <h4>{{$service->name}}</h4>
                                    <p style="word-break: break-all">{{$service->description}}
                                    Lorem ipsum dolor sit, amet consectetur adipisicing elit.
                                    Quam, eligendi odit excepturi neque consectetur itaque explicabo
                                    beatae debitis dolores consequatur magnam placeat fugit repellendus
                                    inventore fugiat numquam distinctio repudiandae ex.
                                    </p>
                                    <hr>

                                    <p class="fh5co-property-specification">
                                        <span><strong>Price : </strong>{{$service->price}}MAD </span>||
                                        <span>Home Service : <strong>

                                            @if ($service->home_service)
                                                <i class="fa fa-check" style="color: rgb(0, 173, 0)"></i>
                                            @else
                                                No
                                            @endif
                                        </strong></span>

                                    </p>

                                </button>
                            @endforeach


                        </div>
                        <div class="mt-3">
                            <button class="button btn-navigate-form-step" type="button" step_number="2">Next</button>
                        </div>
                    </section>
                    <!-- Step 2 Content, default hidden on page load. -->
                    <section id="step-2" class="form-step d-none">
                        <h2 class="font-normal">Choose a car
                            <a class="btn btn-dark" href="{{route('cars.create')}}">
                                {{ __('Add New Car') }}
                            </a>
                        </h2>
                        <!-- Step 2 input fields -->
                        <div class="mt-3">

                                <div class="form-group row">
                                    <label for="mark" class="col-md-5 col-form-label"><strong>Choisissez une Vehicule</strong></label>
                                    <div class="col-md-7">
                                        <select class="form-select form-control mb-2" name="car_id" id="mark"
                                         aria-label="Default select example" required >
                                        <option option value="" >Choose</option>

                                        @foreach (Auth::user()->cars as $car)

                                        <option value="{{$car->id}}" >{{$car->marque->name}}</option>
                                        @endforeach
                                        </select>
                                     </div>
                                </div>

                                <div class="form-group row">
                                    <label for="demande" class="col-md-5 col-form-label"><strong>Demande Date</strong></label>
                                    <div class="col-md-7">
                                      <input type="datetime-local" class="form-control mb-2" id="demande" name="date" required min="{{date('Y-m-d')}}">

                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="adress" class="col-md-5 col-form-label"><strong>Address</strong></label>
                                    <div class="col-md-7">
                                        <textarea name="address" class="form-control mb-2" id=""
                                        cols="30" rows="3" required style="
                                        font-weight: 500;
                                    ">{{Auth::user()->adress}}</textarea>

                                    </div>
                                 </div>

                                 <div class="form-group row">
                                    <label for="comment" class="col-md-5 col-form-label">
                                        <strong>Comment</strong></label>
                                    <div class="col-md-7">
                                        <textarea name="comment" class="form-control mb-2" id=""
                                        cols="30" rows="3" required style="
                                        font-weight: 500;
                                    "></textarea>

                                    </div>
                                 </div>




                        </div>
                        <div class="mt-3">
                            <button class="button btn-navigate-form-step" type="button" step_number="1">Prev</button>
                            <button class="button btn-navigate-form-step" type="button" step_number="3">Next</button>
                        </div>
                    </section>
                    <!-- Step 3 Content, default hidden on page load. -->
                    <section id="step-3" class="form-step d-none">
                        <h2 class="font-normal">Confirm demande</h2>
                        <!-- Step 3 input fields -->
                        <div class="mt-3">
                            You will receive an e-mail after confirming your demande
                        </div>
                        <div class="mt-3">
                            <button class="button btn-navigate-form-step" type="button" step_number="2">Prev</button>
                            <button class="button submit-btn" type="submit">Save</button>
                        </div>
                    </section>
                </form>
            </div>
        </div>


    </div>
</div>
@endsection


@section('scripts')
@foreach ($services as $service)
<script>

    $("#htmlbtn{{$service->id}}").on("click",function(e){
      e.preventDefault();

      if (!$(this).hasClass('active')) {
            $(this).addClass('active')
            var values = $( "#htmlbtn{{$service->id}}" ).val();
            $('#services').append('<input type="hidden" name="services_id[]" id="service{{$service->id}}" value="{{$service->id}}" />')

      }
      else {
        $(this).removeClass('active');
        $( "#service{{$service->id}}" ).remove();
      }



    });
    </script>
@endforeach

<script>
    /**
 * Define a function to navigate betweens form steps.
 * It accepts one parameter. That is - step number.
 */
const navigateToFormStep = (stepNumber) => {
    /**
     * Hide all form steps.
     */
    document.querySelectorAll(".form-step").forEach((formStepElement) => {
        formStepElement.classList.add("d-none");
    });
    /**
     * Mark all form steps as unfinished.
     */
    document.querySelectorAll(".form-stepper-list").forEach((formStepHeader) => {
        formStepHeader.classList.add("form-stepper-unfinished");
        formStepHeader.classList.remove("form-stepper-active", "form-stepper-completed");
    });
    /**
     * Show the current form step (as passed to the function).
     */
    document.querySelector("#step-" + stepNumber).classList.remove("d-none");
    /**
     * Select the form step circle (progress bar).
     */
    const formStepCircle = document.querySelector('li[step="' + stepNumber + '"]');
    /**
     * Mark the current form step as active.
     */
    formStepCircle.classList.remove("form-stepper-unfinished", "form-stepper-completed");
    formStepCircle.classList.add("form-stepper-active");
    /**
     * Loop through each form step circles.
     * This loop will continue up to the current step number.
     * Example: If the current step is 3,
     * then the loop will perform operations for step 1 and 2.
     */
    for (let index = 0; index < stepNumber; index++) {
        /**
         * Select the form step circle (progress bar).
         */
        const formStepCircle = document.querySelector('li[step="' + index + '"]');
        /**
         * Check if the element exist. If yes, then proceed.
         */
        if (formStepCircle) {
            /**
             * Mark the form step as completed.
             */
            formStepCircle.classList.remove("form-stepper-unfinished", "form-stepper-active");
            formStepCircle.classList.add("form-stepper-completed");
        }
    }
};
/**
 * Select all form navigation buttons, and loop through them.
 */
document.querySelectorAll(".btn-navigate-form-step").forEach((formNavigationBtn) => {
    /**
     * Add a click event listener to the button.
     */
    formNavigationBtn.addEventListener("click", () => {
        /**
         * Get the value of the step.
         */
        const stepNumber = parseInt(formNavigationBtn.getAttribute("step_number"));
        /**
         * Call the function to navigate to the target form step.
         */
        navigateToFormStep(stepNumber);
    });
});
</script>
@endsection
