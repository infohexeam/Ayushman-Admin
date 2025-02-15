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
							<h2 class="mb-0 number-font">{{@$totalStaffs}}</h2>
							<p class="text-white mb-0" style="font-size:12px;">Total Staffs </p>
						</div>
						<div class="ml-auto"> <i class="fa fa-users text-white fs-30 mr-2 mt-2"></i> </div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
			<div class="card bg-success img-card box-success-shadow">
				<div class="card-body bg-transparent">
					<div class="d-flex">
						<div class="text-white">
							<h2 class="mb-0 number-font">{{@$lowStockMedicines}}</h2>
							<p class="text-white mb-0" style="font-size:12px;">Low Stock Medicines</p>
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
							<h2 class="mb-0 number-font">{{@$dailySale->daily_sales ?? '0'}}</h2>
							<p class="text-white mb-0" style="font-size:12px;">Today's Sales</p>
						</div>
						<div class="ml-auto">
							<i class="fa fa-dollar text-white fs-30 mr-2 mt-2"></i>
						</div>
					</div>
				</div>
			</div>
		</div><!-- COL END -->
		<div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
			<div class="card bg-success img-card box-success-shadow">
				<div class="card-body bg-transparent">
					<div class="d-flex">
						<div class="text-white">
							<h2 class="mb-0 number-font">{{@$medicineSaleWeekly->weekly_sales ?? '0'}}</h2>
							<p class="text-white mb-0" style="font-size:12px;">Weekly Sales</p>
						</div>
						<div class="ml-auto">
							<i class="fa fa-dollar text-white fs-30 mr-2 mt-2"></i>
						</div>
					</div>
				</div>
			</div>
		</div><!-- COL END -->
		<div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
			<div class="card bg-success img-card box-success-shadow">
				<div class="card-body bg-transparent">
					<div class="d-flex">
						<div class="text-white">
							<h2 class="mb-0 number-font">{{@$medicineSaleMonthly->monthly_sales ?? '0'}}</h2>
							<p class="text-white mb-0" style="font-size:12px;">Monthly Sales</p>
						</div>
						<div class="ml-auto">
							<i class="fa fa-dollar text-white fs-30 mr-2 mt-2"></i>
						</div>
					</div>
				</div>
			</div>
		</div><!-- COL END -->
		<!--<div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">-->
		<!--	<div class="card bg-success img-card box-success-shadow">-->
		<!--		<div class="card-body bg-transparent">-->
		<!--			<div class="d-flex">-->
		<!--				<div class="text-white">-->
		<!--					<h2 class="mb-0 number-font">{{@$totalSales->sales ?? '0'}}</h2>-->
		<!--					<p class="text-white mb-0" style="font-size:12px;">Total Sales</p>-->
		<!--				</div>-->
		<!--				<div class="ml-auto">-->
		<!--					<i class="fa fa-dollar text-white fs-30 mr-2 mt-2"></i>-->
		<!--				</div>-->
		<!--			</div>-->
		<!--		</div>-->
		<!--	</div>-->
		<!--</div><!-- COL END -->
		<div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
			<div class="card bg-success img-card box-success-shadow">
				<div class="card-body bg-transparent">
					<div class="d-flex">
						<div class="text-white">
							<h2 class="mb-0 number-font">{{@$purchases}}</h2>
							<p class="text-white mb-0" style="font-size:12px;">Total Purchases</p>
						</div>
						<div class="ml-auto">
							<i class="fa fa-credit-card text-white fs-30 mr-2 mt-2"></i>
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
							<h2 class="mb-0 number-font">{{Mst_Supplier::count()}}</h2>
							<p class="text-white mb-0" style="font-size:12px;">Total Suppliers</p>
						</div>
						<div class="ml-auto">
							<i class="fa fa-truck text-white fs-30 mr-2 mt-2"></i>
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
							<h2 class="mb-0 number-font">{{@$currentDayLeave}}</h2>
							<p class="text-white mb-0" style="font-size:12px;">Staffs On Leave</p>
						</div>
						<div class="ml-auto">
							<i class="fa fa-calendar text-white fs-30 mr-2 mt-2"></i>
						</div>
					</div>
				</div>
			</div>
		</div><!-- COL END -->
		
	</div>
	<!-- ROW -->

	<div class="row" style="display:none;">
		<div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
			<div class="card">
				<div class="card-header">
					<h3 class="card-title">Earnings</h3>
				</div>
				<div class="card-body">
					<div id="echart1" class="chart-donut chart-dropshadow"></div>
					<div class="mt-4">
						<span class="ml-5"><span class="dot-label bg-info mr-2"></span>Sales</span>
						<span class="ml-5"><span class="dot-label bg-secondary mr-2"></span>Profit</span>
						<span class="ml-5"><span class="dot-label bg-success mr-2"></span>Growth</span>
					</div>
				</div>
			</div>
		</div><!-- COL END -->

		<div class="col-sm-12 col-md-6 col-lg-6 col-xl-6" style="display:none;">
			<div class="card">
				<div class="card-header">
					<h3 class="card-title">Earnings</h3>
				</div>
				<div class="card-body">
					<div id="echart2" class="chart-donut chart-dropshadow"></div>
					<div class="mt-4">
						<span class="ml-5"><span class="dot-label bg-info mr-2"></span>Sales</span>
						<span class="ml-5"><span class="dot-label bg-secondary mr-2"></span>Profit</span>
						<span class="ml-5"><span class="dot-label bg-success mr-2"></span>Growth</span>
					</div>
				</div>
			</div>
		</div><!-- COL END -->
	</div>

	<div class="row" style="display:none;">
		<div class="col-lg-12 col-md-12">
			<div class="card">
				<div class="card-header">
					<h3 class="card-title">Single smooth line chart</h3>
				</div>
				<div class="card-body">
					<div id="echart8" class="chartsh"></div>
				</div>
			</div>
		</div>
	</div>


	<div class="row row-deck" style="display:none;">
		<div class="col-lg-6">
			<div class="card">
				<div class="card-header">
					<div class="card-title">Skill Set</div>
				</div>
				<div class="card-body mt-0">
					<div class="chats-wrap media-list">
						<div class="chat-details mb-1 p-3">
							<h4 class="mb-0">
								<span class="h5 font-weight-normal">Html</span>
								<span class="float-right p-1  btn btn-sm text-default">
									<b>85</b>%</span>
							</h4>
							<div class="progress progress-sm mt-3">
								<div class="progress-bar  bg-primary w-80"></div>
							</div>
						</div>
						<div class="chat-details mb-1 p-3">
							<h4 class="mb-0">
								<span class="h5 font-weight-normal"> Wordpress</span>
								<span class="float-right p-1  btn btn-sm text-default">
									<b>46</b>%</span>
							</h4>
							<div class="progress progress-sm mt-3">
								<div class="progress-bar bg-success w-45"></div>
							</div>
						</div>
						<div class="chat-details mb-1 p-3">
							<h4 class="mb-0">
								<span class="h5 font-weight-normal"> jQuery</span>
								<span class="float-right p-1  btn btn-sm text-default">
									<b>56</b>%</span>
							</h4>
							<div class="progress progress-sm mt-3">
								<div class="progress-bar bg-warning w-65"></div>
							</div>
						</div>
						<div class="chat-details mb-1 p-3">
							<h4 class="mb-0">
								<span class="h5 font-weight-normal"> Photoshop</span>
								<span class="float-right p-1  btn btn-sm text-default">
									<b>90</b>%</span>
							</h4>
							<div class="progress progress-sm mt-3">
								<div class="progress-bar bg-danger w-75"></div>
							</div>
						</div>
						<div class="chat-details mb-0 p-3">
							<h4 class="mb-0">
								<span class="h5 font-weight-normal">Angular js</span>
								<span class="float-right p-1  btn btn-sm text-default">
									<b>30</b>%</span>
							</h4>
							<div class="progress progress-sm mt-3">
								<div class="progress-bar bg-info w-30"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div><!-- COL END -->


		<div class="col-md-12 col-lg-6" style="display:none;">
			<div class="card">
				<div class="card-header">
					<h3 class="card-title">Basic Table</h3>
				</div>
				<div class="table-responsive">
					<table class="table card-table table-vcenter text-nowrap">
						<thead>
							<tr>
								<th>ID</th>
								<th>Name</th>
								<th>Position</th>
								<th>Salary</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th scope="row">1</th>
								<td>Joan Powell</td>
								<td>Associate Developer</td>
								<td>$450,870</td>
							</tr>
							<tr>
								<th scope="row">2</th>
								<td>Gavin Gibson</td>
								<td>Account manager</td>
								<td>$230,540</td>
							</tr>
							<tr>
								<th scope="row">3</th>
								<td>Julian Kerr</td>
								<td>Senior Javascript Developer</td>
								<td>$55,300</td>
							</tr>
							<tr>
								<th scope="row">4</th>
								<td>Cedric Kelly</td>
								<td>Accountant</td>
								<td>$234,100</td>
							</tr>
							<tr>
								<th scope="row">5</th>
								<td>Samantha May</td>
								<td>Junior Technical Author</td>
								<td>$43,198</td>
							</tr>
						</tbody>
					</table>
				</div>
				<!-- table-responsive -->
			</div>
		</div>
	</div>
</div>
@endsection