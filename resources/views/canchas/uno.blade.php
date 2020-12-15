@extends('layouts.field')

@section('content')
<div class="row">
  <div id="fieldOne" class="col-lg-2 fixed-top p-1 z-index">
  	@include('partials.list_fields')
  </div>
  <div class="col-lg-2 p-0">
  	
  </div>
  <div class="col-lg-10 p-0">
  	<v-app class="">
  		<field-one></field-one>
  	</v-app>
  </div>
</div>
@endsection

@push('scripts')
<script src="js/jquery-1.10.2.js"></script>
<script>
  // console.log(window.screen.width);
  if (window.screen.width < 991) {
      let fieldOne = document.querySelector('#fieldOne');
      let listField = document.querySelector('#listField');

      console.log(listField);
      fieldOne.classList.remove("fixed-top");
      fieldOne.classList.add("fixed-bottom");
      // fieldOne.classList.add("d-none");
      // listField.classList.add("d-none");
  } else {
      let fieldOne = document.querySelector('#fieldOne');
      fieldOne.classList.remove("fixed-bottom");
      fieldOne.classList.add("fixed-top");
      // fieldOne.classList.remove("d-none");
  }

  let width = $(window).width();
  $(window).on('resize', function() { 
      if($(this).width() != width) { 
       width = $(this).width(); 
       // console.log(width);
        if (width < 991 || window.screen.width < 991) {
            let fieldOne = document.querySelector('#fieldOne');
            let listField = document.querySelector('#listField');
            console.log(listField);

            fieldOne.classList.remove("fixed-top");
            fieldOne.classList.add("fixed-bottom");
            // fieldOne.classList.add("d-none");
            listField.classList.add("d-none");
        } else {
            let fieldOne = document.querySelector('#fieldOne');
            fieldOne.classList.remove("fixed-bottom");
            fieldOne.classList.add("fixed-top");
            // fieldOne.classList.remove("d-none");
        }
      } 
  });

  // document.querySelector("#barUp").addEventListener("click", myFunction);
  //   function myFunction () {
  //     // let barUp = document.querySelector('#barUp');
  //     // barUp.classList.remove("d-block");
  //     // barUp.classList.add("d-none");
  //     // console.log('xD');
  //   }


</script>
@endpush