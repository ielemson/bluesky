<div class="row align-items-center">
        	<div class="col-md-6">
                <div class="page-title">
            		<h1>{{$header_1 ?? ""}}</h1>
                </div>
            </div>
            <div class="col-md-6">
                <ol class="breadcrumb justify-content-md-end">
                    <li class="breadcrumb-item"><a href="{{route("home")}}">Home</a></li>
                    {{-- <li class="breadcrumb-item"><a href="#">Pages</a></li> --}}
                    <li class="breadcrumb-item active">{{$header_2 ?? ""}}</li>
                </ol>
            </div>
        </div>