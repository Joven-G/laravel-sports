@extends('layouts.field')

@section('content')
<div class="row">
  <div id="fieldTwo" class="col-lg-2 fixed-top p-1 changeFixed"
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
  if (window.screen.width < 991) {
      let fieldTwo = document.querySelector('#fieldTwo');

      fieldTwo.classList.remove("fixed-top");
      fieldTwo.classList.add("fixed-bottom");
  } else {
      let fieldTwo = document.querySelector('#fieldTwo');
      fieldTwo.classList.remove("fixed-bottom");
      fieldTwo.classList.add("fixed-top");
  }
  
  let width = $(window).width();
  $(window).on('resize', function() { 
      if($(this).width() != width) { 
       width = $(this).width(); 
       // console.log(width);
        if (width < 991 || window.screen.width < 991) {
            let fieldTwo = document.querySelector('#fieldTwo');

            fieldTwo.classList.remove("fixed-top");
            fieldTwo.classList.add("fixed-bottom");
        } else {
            let fieldTwo = document.querySelector('#fieldTwo');
            fieldTwo.classList.remove("fixed-bottom");
            fieldTwo.classList.add("fixed-top");
        }
      } 
  }); 

</script>
@endpush