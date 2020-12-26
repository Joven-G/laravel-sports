@extends('layouts.appTwo')

@section('content')
<div class="row">
{{--   <div id="fieldOne" class="col-lg-2 fixed-top p-1 changeFixed"
    style="z-index: 1;padding-top: 0px !important;"
  >
  	@include('partials.list_fields')
  </div>
  <div class="col-lg-2 p-0">
  	
  </div> --}}
  {{-- <div class="col-lg-10 p-0"> --}}
	<v-app>
		<field-three></field-three>
	</v-app>
  {{-- </div> --}}
</div>
@endsection