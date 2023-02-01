<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>GESTION DE STOCK</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/login.css')}}">

</head>
<body>
	<div class="full-page">
		<div class="heading  p-auto text-dark top-0 start-0 py-4">
			<!-- <img src="../Capture1.PNG" alt="" width="100%"> -->
			<h2 class="my-0">
                <img src="{{ asset('assets/images/logo2.jpg') }}" alt="" srcset=""
                style="width: 300px;height: 100px;">
            </h2>
			<h3 class="my-0"></h3>
		</div>
		<div class="body-content">
			<!-- <div class="body-h pt-5">
				<h2 class=""></h2>
			</div> -->

			<div class="body-msg-bar  text-light py-1">
				<p class="my-0">AUTENTIFICATION</p>
				<p class="my-0"></p>
			</div>

			<!-- <div class="body-form-container container"> -->
				<form action="{{ route('login') }}" method="post" class="body-form  card border p-0 mt-5">
					@csrf


                    <div class="py-4">
                        <div class="form-control border-0 ">
                            <label for="user" class="">
                                Compte utilisateur :
                            </label>
                            <input type="email" class="@error('email') is-invalid @enderror" id="user" name="email" style="height: 18px;">
                            @error('email')
                            <span class="invalid-feedback ms-5" role="alert">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                        <div class="form-control border-0">
                            <label for="" class="">
                                Mot de passe :
                            </label>
                            <input type="password" class="@error('password') is-invalid @enderror" id="pss" name="password" style="height: 18px;">
                        </div>

                    </div>
					<div class="form-btn container form-control  rounded-0 rounded-bottom text-center">
						<!-- <div class=" border-0"> -->

									<button type="submit" class="btn border py-0 bg-light" id="" name="" >
										<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-unlock-fill" viewBox="0 0 16 16" style="color: #dcb131;">
											<path d="M11 1a2 2 0 0 0-2 2v4a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h5V3a3 3 0 0 1 6 0v4a.5.5 0 0 1-1 0V3a2 2 0 0 0-2-2z"/>
										</svg>
										Connexion
									</button>



						<!-- </div> -->
					</div>
				</form>
			<!-- </div> -->

			<footer class="bg-light fixed-bottom text-center p-3">
				<a href="{{ route('password.request') }}" class="text-dark mx-3">Mot de passe oublié ?</a>
                {{-- {{ route('password.request') }} --}}
            </footer>


		</div>
	</div>


</body>
</html>
