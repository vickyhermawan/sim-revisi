<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Sistem Informasi Klinik</title>

  <!-- Global stylesheets -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
  <link href="{{url('/')}}/template/global_assets/css/icons/icomoon/styles.min.css" rel="stylesheet" type="text/css">
  <link href="{{url('/')}}/template/layout_1/LTR/default/full/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="{{url('/')}}/template/layout_1/LTR/default/full/assets/css/bootstrap_limitless.min.css" rel="stylesheet" type="text/css">
  <link href="{{url('/')}}/template/layout_1/LTR/default/full/assets/css/layout.min.css" rel="stylesheet" type="text/css">
  <link href="{{url('/')}}/template/layout_1/LTR/default/full/assets/css/components.min.css" rel="stylesheet" type="text/css">
  <link href="{{url('/')}}/template/layout_1/LTR/default/full/assets/css/colors.min.css" rel="stylesheet" type="text/css">
  <!-- /global stylesheets -->

  <!-- Core JS files -->
  <script src="{{url('/')}}/template/global_assets/js/main/jquery.min.js"></script>
  <script src="{{url('/')}}/template/global_assets/js/main/bootstrap.bundle.min.js"></script>
  <script src="{{url('/')}}/template/global_assets/js/plugins/loaders/blockui.min.js"></script>
  <!-- /core JS files -->

  <!-- Theme JS files -->
  <script src="{{url('/')}}/template/global_assets/js/plugins/visualization/d3/d3.min.js"></script>
  <script src="{{url('/')}}/template/global_assets/js/plugins/visualization/d3/d3_tooltip.js"></script>
  <script src="{{url('/')}}/template/global_assets/js/plugins/forms/styling/switchery.min.js"></script>
  <script src="{{url('/')}}/template/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js"></script>
  <script src="{{url('/')}}/template/global_assets/js/plugins/ui/moment/moment.min.js"></script>
  <script src="{{url('/')}}/template/global_assets/js/plugins/pickers/daterangepicker.js"></script>

  <script src="{{url('/')}}/template/layout_1/LTR/default/full/assets/js/app.js"></script>
  <script src="{{url('/')}}/template/global_assets/js/demo_pages/dashboard.js"></script>
  <!-- /theme JS files -->

</head>

<body>	
	<div class="card-body">
		<!-- Form inputs -->
			<center>
				<div class="row">
					<div class="col-lg-3"></div>
						<div class="col-lg-6">
							<div class="card pt-4 px-3">	
							<img src="https://indok3ll.com/wp-content/uploads/2018/12/kesehatan-icon.png" class="mb-4" width="120px">							
								<h1 class="font-weight-semibold ">LOGIN SISTEM INFORMASI KLINIK</h1>
								<div class="card-body">
									<form method="POST" action="{{ route('login') }}">
										@csrf
										<fieldset class="mb-0">
											<div class="form-group">
												<div class="form-group form-group-feedback form-group-feedback-left">
									
													<input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus  placeholder="Username">
													<div class="form-control-feedback form-control-feedback-lg">
														<i class="icon-user"></i>
													</div>
													@error('email')
													<span class="invalid-feedback" role="alert">
														<strong>{{ $message }}</strong>
													</span>
												@enderror
												</div>
												<div class="form-group form-group-feedback form-group-feedback-left">
													<input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" required autocomplete="current-password"  placeholder="Password">
													<div class="form-control-feedback form-control-feedback-lg">
															<i class="icon-lock	"></i>
													</div>
													@error('password')
														<span class="invalid-feedback" role="alert">
															<strong>{{ $message }}</strong>
														</span>
													@enderror
												</div>
												<a href="{{ route('password.request') }}"><p align="right"><i class="icon-unlocked small"></i> Lupa Password</p></a>
												
												<button type="submit" class="btn btn-primary btn-lg px-5 py-2 font-weight-bold">
													{{ __('Login') }}
												</button><br /><br/>
												
												<p>KOMSI | Universitas Gadjah Mada 2019</p>
											</div>
										</fieldset>
									</form>
								</div>
							</div>
						</div>
					<div class="col-lg-3"></div>
				</div>
		</center>
	</div>
</body>
</html>