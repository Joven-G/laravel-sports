@extends('layouts.field')

@section('content')
<div class="row">
  <div class="col-lg-2 fixed-top p-1 changeFixed"
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

@push('scripts')
<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
<script>

  $(window).resize(function(){
    // var alto=$(window).height();
    var ancho=$(window).width();
    if (ancho < 991) {
      console.log('Pequeño');
    }
  })
  
</script>
@endpush