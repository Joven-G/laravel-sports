@extends('layouts.field')

@section('content')
<div class="row">
 {{--    <li  class="list-group-item pt-3 pb-2 d-lg-none d-block fixed-bottom">
      <a href="#"
      class="list-group-item border-0
          font-weight-bold h3 d-flex justify-content-between" 
      >
        <div>
          <svg width="1em" height="1em" viewBox="0 0 16 16"
            class="bi bi-plus-circle" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
            <path fill-rule="evenodd" d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
          </svg>

          Nuevo
        </div>

        <div id="up">
          <svg width="1em" height="1em" viewBox="0 0 16 16"
            class="bi bi-caret-up" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M3.204 11L8 5.519 12.796 11H3.204zm-.753-.659l4.796-5.48a1 1 0 0 1 1.506 0l4.796 5.48c.566.647.106 1.659-.753 1.659H3.204a1 1 0 0 1-.753-1.659z"/>
          </svg>
        </div>
      </a>
    </li> --}}
{{-- d-none d-lg-block --}}
  <div id="fieldOne" class="col-lg-2 fixed-top p-1 
    "
    style="z-index: 1;" 
  >
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
<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
<script>
  console.log(window.screen.width);
  if (window.screen.width < 991) {
      let fieldOne = document.querySelector('#fieldOne');

      fieldOne.classList.remove("fixed-top");
      fieldOne.classList.add("fixed-bottom");
      // fieldOne.classList.add("d-none");
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

            fieldOne.classList.remove("fixed-top");
            fieldOne.classList.add("fixed-bottom");
            // fieldOne.classList.add("d-none");
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