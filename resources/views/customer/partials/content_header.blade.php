	<div class="content-header">
		<div class="d-flex align-items-center">
			<div class="mr-auto w-p50">
				<h3 class="page-title">{{$header_1 ?? ""}}</h3>
				<div class="d-inline-block align-items-center">
					<nav>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
							<li class="breadcrumb-item active" aria-current="page">{{$header_2 ?? ""}}</li>
						</ol>
					</nav>
				</div>
			</div>
			<div class="right-title w-170">
				<span class="subheader_daterange font-weight-600" id="dashboard_daterangepicker">
					<span class="subheader_daterange-label">
						<span class="subheader_daterange-title"></span>
						<span class="subheader_daterange-date text-primary">{{$header_1 ?? ""}}</span>
					</span>
					<a href="#" class="btn btn-sm btn-primary">
						<i class="fa fa-angle-down"></i>
					</a>
				</span>
			</div>
		</div>
	</div>
