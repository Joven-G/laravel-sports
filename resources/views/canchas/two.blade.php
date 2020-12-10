@extends('layouts.field')

@section('content')
<div class="row">
  <div class="col-lg-2 fixed-top p-1"
    style="z-index: 1;"
  >
  	@include('partials.list_fields')
  </div>
  <div class="col-lg-2 p-0">
  	
  </div>
  <div class="col-lg-10 p-0">
	<v-app class="">
		<field-two></field-two>
	</v-app>
  </div>
</div>
@endsection