@extends('layouts.app')

@section('content')

@php
use App\Models\Mst_Branch;
use App\Models\Mst_Staff;
use App\Models\Mst_Supplier;
use App\Models\Trn_Consultation_Booking;
use App\Models\Trn_Medicine_Stock;

@endphp

<style>
	.bg-primary {
		background: #5e2dd8 !important;
	}

	.bg-secondary {
		background: #d43f8d !important;
	}

	.bg-success {
		background: #09ad95 !important;
	}

	.bg-info {
		background: #0774f8 !important;
	}
</style>
@if ($message = Session::get('error'))
<div class="alert alert-danger">
	<p>{{$message}}</p>
</div>
@endif
<div class="container">
	<div class="row">
		<div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
			<div class="card bg-success img-card box-success-shadow">
				<div class="card-body bg-transparent">
					<div class="d-flex">
						<div class="text-white">
							<h2 class="mb-0 number-font">{{@$currentDayBooking}}</h2>
							<p class="text-white mb-0" style="font-size:12px;">Current Day Bookings</p>
						</div>
						<div class="ml-auto">
							<i class="fa fa-bar-chart text-white fs-30 mr-2 mt-2"></i>
						</div>
					</div>
				</div>
			</div>
		</div><!-- COL END -->
		<div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
			<div class="card bg-success img-card box-success-shadow">
				<div class="card-body bg-transparent">
					<div class="d-flex">
						<div class="text-white">
							<h2 class="mb-0 number-font">{{@$upComingBooking}}</h2>
							<p class="text-white mb-0" style="font-size:12px;">Upcoming Bookings</p>
						</div>
						<div class="ml-auto">
							<i class="fa fa-bar-chart text-white fs-30 mr-2 mt-2"></i>
						</div>
					</div>
				</div>
			</div>
		</div><!-- COL END -->
		<div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
			<div class="card bg-success img-card box-success-shadow">
				<div class="card-body bg-transparent">
					<div class="d-flex">
						<div class="text-white">
							<h2 class="mb-0 number-font">{{@$pendingBooking}}</h2>
							<p class="text-white mb-0" style="font-size:12px;">Pending Bookings</p>
						</div>
						<div class="ml-auto">
							<i class="fa fa-bar-chart text-white fs-30 mr-2 mt-2"></i>
						</div>
					</div>
				</div>
			</div>
		</div><!-- COL END -->
	</div>
		<div class="row">
		<div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
			<div class="card">
				<a class="" href="{{ url('/doctor/consultation/index') }}">
				<div class="card-header">
					<h3 class="card-title">Consultation Bookings</h3>
					<div class="card-options">	
						<i class="fa fa-arrow-circle-o-right text-colored"></i>
					</div>
				</div>
			</a>
			</div>
		</div><!-- COL END -->
	</div>
	<!-- ROW -->
	<!-- ROW -->
</div>
@endsection