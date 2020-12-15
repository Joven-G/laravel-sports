<template>
<!-- <section class="p-2 d-block" style="background-color: #fff;">
  <li class="list-group-item pt-3 pb-2 d-lg-none d-block fixed-bottom">
    <a href="#"
      class="list-group-item border-0
          font-weight-bold h3 d-flex justify-content-between" 
      >
      <div @click="showCreateEvent">
        <svg width="1em" height="1em" viewBox="0 0 16 16"
          class="bi bi-plus-circle" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
          <path fill-rule="evenodd" d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
        </svg>

          Nuevo
      </div>

      <div id="up" @click="showListField">
        <svg width="1em" height="1em" viewBox="0 0 16 16"
          class="bi bi-caret-up" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" d="M3.204 11L8 5.519 12.796 11H3.204zm-.753-.659l4.796-5.48a1 1 0 0 1 1.506 0l4.796 5.48c.566.647.106 1.659-.753 1.659H3.204a1 1 0 0 1-.753-1.659z"/>
        </svg>
      </div>
    </a>
  </li>
</section > -->
<ul class="list-group list-group-flush pr-1">
  <li class="list-group-item pt-3 pb-2">
    <a href="#" class="list-group-item border-0 font-weight-bold h3 d-flex justify-content-between"
    >
      <div @click="showCreateEvent">
        <svg width="1em" height="1em" viewBox="0 0 16 16"
          class="bi bi-plus-circle" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
          <path fill-rule="evenodd" d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
        </svg>

          Nuevo
      </div>

      <div id="up" @click="showListField" class="d-lg-none d-block">
        <svg width="1em" height="1em" viewBox="0 0 16 16"
          class="bi bi-caret-up" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" d="M3.204 11L8 5.519 12.796 11H3.204zm-.753-.659l4.796-5.48a1 1 0 0 1 1.506 0l4.796 5.48c.566.647.106 1.659-.753 1.659H3.204a1 1 0 0 1-.753-1.659z"/>
        </svg>
      </div>
<!--       <span id="down" class="">
        <svg id="down" width="1em" height="1em" viewBox="0 0 16 16"
          class="bi bi-caret-down" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" d="M3.204 5L8 10.481 12.796 5H3.204zm-.753.659l4.796 5.48a1 1 0 0 0 1.506 0l4.796-5.48c.566-.647.106-1.659-.753-1.659H3.204a1 1 0 0 0-.753 1.659z"/>
        </svg>
      </span> -->
    </a>
  </li>

  <div id="listField" class="">
    <li class="list-group-item text-secondary font-weight-bolder">
      Listado de Canchas
    </li>
    <li class="list-group-item p-0">
      <a href="#"
        class="list-group-item border-0" 
      >
        <svg width="1em" height="1em" viewBox="0 0 16 16"
          class="bi bi-asterisk" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" d="M8 0a1 1 0 0 1 1 1v5.268l4.562-2.634a1 1 0 1 1 1 1.732L10 8l4.562 2.634a1 1 0 1 1-1 1.732L9 9.732V15a1 1 0 1 1-2 0V9.732l-4.562 2.634a1 1 0 1 1-1-1.732L6 8 1.438 5.366a1 1 0 0 1 1-1.732L7 6.268V1a1 1 0 0 1 1-1z"/>
        </svg>
        Cancha Uno
      </a>
    </li>
    <li class="list-group-item p-0">
      <a href="#"
        class="list-group-item border-0" 
      >
        <svg width="1em" height="1em" viewBox="0 0 16 16"
          class="bi bi-asterisk" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" d="M8 0a1 1 0 0 1 1 1v5.268l4.562-2.634a1 1 0 1 1 1 1.732L10 8l4.562 2.634a1 1 0 1 1-1 1.732L9 9.732V15a1 1 0 1 1-2 0V9.732l-4.562 2.634a1 1 0 1 1-1-1.732L6 8 1.438 5.366a1 1 0 0 1 1-1.732L7 6.268V1a1 1 0 0 1 1-1z"/>
        </svg>
        Cancha Dos
      </a>
    </li>
    <li class="list-group-item p-0">
      <a href="#"
        class="list-group-item border-0" 
      >
        <svg width="1em" height="1em" viewBox="0 0 16 16"
          class="bi bi-asterisk" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" d="M8 0a1 1 0 0 1 1 1v5.268l4.562-2.634a1 1 0 1 1 1 1.732L10 8l4.562 2.634a1 1 0 1 1-1 1.732L9 9.732V15a1 1 0 1 1-2 0V9.732l-4.562 2.634a1 1 0 1 1-1-1.732L6 8 1.438 5.366a1 1 0 0 1 1-1.732L7 6.268V1a1 1 0 0 1 1-1z"/>
        </svg>
        Cancha Tres
      </a>
    </li>
    <li class="list-group-item p-0">
      <a href="#"
      class="list-group-item border-0" 
      >
        <svg width="1em" height="1em" viewBox="0 0 16 16"
          class="bi bi-house-door-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
          <path d="M6.5 10.995V14.5a.5.5 0 0 1-.5.5H2a.5.5 0 0 1-.5-.5v-7a.5.5 0 0 1 .146-.354l6-6a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 .146.354v7a.5.5 0 0 1-.5.5h-4a.5.5 0 0 1-.5-.5V11c0-.25-.25-.5-.5-.5H7c-.25 0-.5.25-.5.495z"/>
          <path fill-rule="evenodd" d="M13 2.5V6l-2-2V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5z"/>
        </svg>
        Inicio
      </a>
    </li>
  </div>
  <div>
  <h1>Window Stats</h1>
  <span>Width: <span class="width"></span></span>
  <br />
  <span>Height: <span class="height"></span></span>
</div>
</ul>
</template>

<script>

export default {
	data: () => ({
		createOpen: false,

	}),
  computed: {

  },
  mounted () {
    // console.log(this.showListField())
    console.log(window.addEventListener('resize', this.go()));

  },
	methods: {
    showListField() {
      console.log("xD")
    },
		showCreateEvent() {
			EventBus.$emit('open-modal-create', true)
		},
    go(){
      window.addEventListener('resize', function() {
        document.querySelector('.width').innerText = document.documentElement.clientWidth; 
        document.querySelector('.height').innerText = document.documentElement.clientHeight;
      });
    }
	}
}
</script>