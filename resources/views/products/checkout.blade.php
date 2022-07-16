@extends('layouts.app')
@section('styles')
<link rel="stylesheet" type="text/css" href="{{asset('checkout/css/styles.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/demo.css')}}">
    <style>

    </style>
@endsection
@section('content')

<div class="container mt-5">
    <div class="main-body">

        <div class="container">

            <div class="row">
                <div class="col-md-4 order-md-2 mb-4">
                    <h4 class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Your cart</span>
                        <span class="badge badge-secondary badge-pill">3</span>
                    </h4>
                    <ul class="list-group mb-3">

                        @foreach ($finalData as $item)
                            <li class="list-group-item d-flex justify-content-between lh-condensed">
                            <div>
                                <h6 class="my-0">{{$item['name']}}</h6>
                                <small class="text-muted">Quantity : {{$item['quantity']}}</small>
                            </div>
                            <span class="text-muted">{{$item['price']}}MAD</span>
                        </li>
                        @endforeach



                        <li class="list-group-item d-flex justify-content-between">
                            <span>Total (MAD)</span>
                            <strong>{{$amount}}MAD</strong>
                        </li>
                    </ul>

                </div>
                <div class="col-md-8 order-md-1">
                    <h4 class="mb-3">Billing address</h4>
                    <form class="needs-validation" novalidate="" id="check-form"
                     action="{{route('processPayment')}}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="firstName">First name</label>
                                <input type="text" class="form-control" id="firstName"
                                placeholder="" value="" name="firstName" required >
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="lastName">Last name</label>
                                <input type="text" class="form-control" id="lastName"
                                placeholder="" value="" name="lastName" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email">Email <span class="text-muted"></span></label>
                            <input type="email" class="form-control" id="email"
                            placeholder="you@example.com" name="email" required>
                        </div>

                        <div class="mb-3">
                            <label for="phone">Phone <span class="text-muted"></span></label>
                            <input type="tel" class="form-control" id="phone"
                            placeholder="phone" name="phone" required>
                        </div>

                        <div class="mb-3">
                            <label for="address">Address</label>
                            <input type="text"
                            class="form-control" id="address" placeholder="1234 Main St" name="address" required>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="state">State</label>
                                <select class="form-control d-block w-100" id="state" name="state" required>
                                    <option value="">Choose...</option>
                                    <option>California</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="state">City</label>
                                <select class="form-control d-block w-100" id="state" name="city" required>
                                    <option value="">Choose...</option>
                                    <option>California</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="zip">Zip</label>
                                <input type="text" class="form-control" id="zip"
                                placeholder="" name="zipCode" required>
                            </div>
                        </div>
                        <hr class="mb-4">
                        <h4 class="mb-3">Payment</h4>
                        <div class="creditCardForm">
                            <div class="payment">


                                    <div class="form-group owner">
                                        <label for="owner">Owner</label>
                                        <input type="text" class="form-control" id="owner" name="cardOwner">
                                        <span id="ownerName" class="badge badge-danger" style="color: rgb(223, 8, 8);display: none">Wrong owner name</span>
                                    </div>
                                    <div class="form-group CVV">
                                        <label for="cvv">CVV</label>
                                        <input type="text" class="form-control" id="cvv" name="cvv">
                                        <span id="cvvnum" class="badge badge-danger" style="color: rgb(223, 8, 8);display: none">Wrong cvv</span>

                                    </div>
                                    <div class="form-group" id="card-number-field">
                                        <label for="cardNumber">Card Number</label>
                                        <input type="text" class="form-control" id="cardNumber" name="cardNumber">
                                        <span id="cardNum" class="badge badge-danger" style="color: rgb(223, 8, 8);display: none">Wrong card number</span>

                                    </div>
                                    <div class="form-group" id="expiration-date">
                                        <label>Expiration Date</label>
                                        <select name="expirationMonth" id="expirationMonth">
                                            <option value="01">January</option>
                                            <option value="02">February </option>
                                            <option value="03">March</option>
                                            <option value="04">April</option>
                                            <option value="05">May</option>
                                            <option value="06">June</option>
                                            <option value="07">July</option>
                                            <option value="08">August</option>
                                            <option value="09">September</option>
                                            <option value="10">October</option>
                                            <option value="11">November</option>
                                            <option value="12">December</option>

                                        </select>
                                        <select name="expirationYear" id="expirationYear">

                                            {{ $last=  now()->year + 5 }}
                                            {{ $now = now()->year }}
                                            @for ($i = $now; $i <= $last; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                         @endfor
                                        </select>

                                        <span id="expireYM" class="badge badge-danger" style="color: rgb(223, 8, 8);display: none">Wrong Date</span>

                                    </div>
                                    <div class="form-group" id="credit_cards">
                                        <img src="{{asset('checkout/images/visa.jpg')}}" id="visa">
                                        <img src="{{asset('checkout/images/mastercard.jpg')}}" id="mastercard">
                                        <img src="{{asset('checkout/images/amex.jpg')}}" id="amex">
                                    </div>
                                    <div class="form-group" id="pay-now">
                                    </div>

                                    <input type="hidden" name="amount" value="{{$amount}}">

                                </form>
                            </div>
                        </div>
                        <hr class="mb-4">
                        <button type="submit" class="btn btn-primary btn-lg btn-block" id="confirm-purchase">Confirm</button>


                    </form>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection

@section('scripts')
<script src="{{asset('checkout/js/jquery.payform.min.js')}}" charset="utf-8"></script>
<script src="{{asset('chekckout/js/script.js')}}"></script>
<script>

var expireMonth= $('#expirationMonth'),expireYear= $('#expirationYear')
 var owner = $('#owner'),
    cardNumber = $('#cardNumber'),
    cardNumberField = $('#card-number-field'),
    CVV = $("#cvv"),
    mastercard = $("#mastercard"),
    confirmButton = $('#confirm-purchase'),
    visa = $("#visa"),
    amex = $("#amex");
    cardNumber.payform('formatCardNumber');
CVV.payform('formatCardCVC');

cardNumber.keyup(function() {
    amex.removeClass('transparent');
    visa.removeClass('transparent');
    mastercard.removeClass('transparent');

    if ($.payform.validateCardNumber(cardNumber.val()) == false) {
        cardNumberField.removeClass('has-success');
        cardNumberField.addClass('has-error');
    } else {
        cardNumberField.removeClass('has-error');
        cardNumberField.addClass('has-success');
    }

    if ($.payform.parseCardType(cardNumber.val()) == 'visa') {
        mastercard.addClass('transparent');
        amex.addClass('transparent');
    } else if ($.payform.parseCardType(cardNumber.val()) == 'amex') {
        mastercard.addClass('transparent');
        visa.addClass('transparent');
    } else if ($.payform.parseCardType(cardNumber.val()) == 'mastercard') {
        amex.addClass('transparent');
        visa.addClass('transparent');
    }
});

confirmButton.click(function(e) {
    e.preventDefault();

    var isCardValid = $.payform.validateCardNumber(cardNumber.val());
    var isCvvValid = $.payform.validateCardCVC(CVV.val());
    var isNotExpire = $.payform.validateCardExpiry(expireMonth.val(),expireYear.val())

    if(owner.val().length < 5){
        $('#ownerName').css("display", "block");
    } else if (!isCardValid) {
        $('#cardNum').css("display", "block");
    } else if (!isCvvValid) {
        $('#cvvnum').css("display", "block");
    } else if (!isNotExpire) {
        $('#expireYM').css("display", "block");
    }else {
        // Everything is correct. Add your form submission code here.
        $('#check-form').submit();
    }
});
</script>

@endsection
