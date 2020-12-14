<template>
<section class="p-2 d-block" style="background-color: #fff;">
  <li @click="showCreateEvent" class=" ">
  	<a href="#" class="list-group-item border-0
        font-weight-bold h3 d-flex text-secondary"
       
    >
    	<svg width="1em" height="1em" viewBox="0 0 16 16"
        class="bi bi-plus-circle" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
        <path fill-rule="evenodd" d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
    	</svg>

      Reservar
    </a>
  </li>

    <!-- Modal click nueva fecha -->
    <v-menu
      v-model="createOpen"
        :close-on-content-click="false"
        :activator="createElement"
        offset-x
      >
        <v-card
          color="grey lighten-4"
          min-width="350px"
          flat
        >
          <v-toolbar
            :color="createEvent.color"
            dark
          >
            <v-btn icon>
              <v-icon>mdi-pencil</v-icon>
            </v-btn>
            <v-toolbar-title v-html="createEvent.name"></v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn icon>
              <v-icon
                @click="closeModalUpdate"
              >
                mdi-close
              </v-icon>
            </v-btn>
          </v-toolbar>
        <v-card-text>

          <!-- Mensajes de validación -->
          <ul class="text-danger">
            <li v-for="error in errors">
              {{ error[0] }}
            </li>
          </ul>

          <!-- Input Name -->
          <v-row>
            <v-col
              cols="12"
              sm="10"
            >
              <v-text-field
                label="Nombre"
                v-model="name"
                hide-details="auto"
                prepend-icon="mdi-inbox"
                
              ></v-text-field>
            </v-col>
          </v-row>

          <!-- Date Piker -->
          <v-row>
            <v-col
              cols="12"
              sm="6"
              md="6"
            >
              <v-menu
                v-model="datePiker"
                :close-on-content-click="false"
                :nudge-right="40"
                transition="scale-transition"
                offset-y
                min-width="290px"
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    v-model="date"
                    label="Elije Fecha"
                    prepend-icon="mdi-calendar"
                    readonly
                    v-bind="attrs"
                    v-on="on"
                    required
                  ></v-text-field>
                </template>
                <v-date-picker
                  v-model="date"
                  :allowed-dates="allowedDates"
                  @input="datePiker = false"
                  locale="es"
                ></v-date-picker>
              </v-menu>
            </v-col>
          </v-row>

          <!-- Time Picker Start -->
          <v-row>
            <v-col
              cols="11"
              sm="5"
            >
              <v-dialog
                ref="dialog"
                v-model="timePikerStart"
                :return-value.sync="timeStart"
                persistent
                width="290px"
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    v-model="start"
                    label="Hora Inicio"
                    prepend-icon="mdi-clock-time-four-outline"
                    readonly
                    v-bind="attrs"
                    v-on="on"
                    required
                  ></v-text-field>
                </template>
                <v-time-picker
                  v-model="start"
                  :min="end"
                  format="24hr"
                >
                  <v-spacer></v-spacer>
                  <v-btn
                    text
                    color="primary"
                    @click="timePikerStart = false"
                  >
                    Cancel
                  </v-btn>
                  <v-btn
                    text
                    color="primary"
                    @click="$refs.dialog.save(timeStart); timePikerStart = false"
                  >
                    OK
                  </v-btn>
                </v-time-picker>
              </v-dialog>
            </v-col>

            <!-- Time Picker End -->
            <v-col
              cols="11"
              sm="5"
            >
              <v-dialog
                ref="dialog"
                v-model="timePikerEnd"
                :return-value.sync="timeEnd"
                persistent
                width="290px"
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    v-model="end"
                    label="Hora Final"
                    prepend-icon="mdi-clock-time-four-outline"
                    readonly
                    v-bind="attrs"
                    v-on="on"
                    required='required'
                  ></v-text-field>
                </template>
                <v-time-picker
                  v-model="end"
                  :min="start"
                  format="24hr"
                >
                  <v-spacer></v-spacer>
                  <v-btn
                    text
                    color="primary"
                    @click="timePikerEnd = false"
                  >
                    Cancel
                  </v-btn>
                  <v-btn
                    text
                    color="primary"
                    @click="$refs.dialog.save(timeEnd)"
                  >
                    OK
                  </v-btn>
                </v-time-picker>
              </v-dialog>
            </v-col>
          </v-row>
        </v-card-text> 

        <!-- Input Color -->
        <v-row>
          <v-col
            cols="12"
            sm="10"
          >
          <v-row justify="center" align="center">
            <v-col class="shrink" style="min-width: 220px;">
              <v-text-field v-model="color" hide-details class="ma-0 pa-0" solo>
                <template v-slot:append>
                  <v-menu v-model="menuColor" top nudge-bottom="105" nudge-left="16" :close-on-content-click="false">
                    <template v-slot:activator="{ on }">
                      <div :style="swatchStyle" v-on="on" />
                    </template>
                    <v-card>
                      <v-card-text class="pa-0">
                        <v-color-picker v-model="color" hide-inputs flat />
                      </v-card-text>
                    </v-card>
                  </v-menu>
                </template>
              </v-text-field>
            </v-col>
          </v-row>
          </v-col>
        </v-row>

        <!-- Buttons -->
        <v-card-actions>
          <v-btn
            text
            color="primary"
            @click="addNewEvent"
          >
            Guardar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-menu>
</section >
</template>

<script>
import moment from 'moment';
let user = document.head.querySelector('meta[name="user"]');

export default {
	data: () => ({
		events: [],
		createOpen: false,
		createElement: null,
		createEvent: {},

		// Date Pickers
		datePiker: false,
		timePikerStart: false,
		timePikerEnd: false,
		timeStart: null,
		timeEnd: null,
		
		menuColor: false, // Show modal color

		date: null,
    name: null,
    start: null,
    end: null,
    color: '#1976D2FF',
    hour: null,
    field_number: '1',
    user_id: null,

		errors: '',
	}),
  computed: {
    user() {
      return JSON.parse(user.content);
    },
    swatchStyle() {
      const { color, menuColor } = this
      return {
        backgroundColor: color,
        cursor: 'pointer',
        height: '30px',
        width: '30px',
        borderRadius: menuColor ? '50%' : '4px',
        transition: 'border-radius 200ms ease-in-out'
      }
    }
  },
  created() {
    // this.getEvents();
  },
  mounted () {
    this.getDateToday();
  },
	methods: {
		showCreateEvent() {
			EventBus.$emit('open-modal-create', true)
		},
    // showCreateEvent () {
    //   const open = () => {
    //     setTimeout(() => {
    //       this.resetForm();
    //       this.createOpen = true
    //     }, .10)
    //   }

    //   if (this.createOpen) {
    //     this.createOpen = false
    //     setTimeout(open, .10)
    //   } else {
    //     open()
    //   }
    // },
    getEvents() {
      axios.get("/onefields")
        .then(response => {
          this.events = response.data.data.filter(event => event.field_number === '1')
        })
        .catch(err => console.log(err.response.data));
    },
    addNewEvent() {
      axios.post("/onefields", {
        name:  this.name,
        date:  this.date,
        start: this.start,
        end:   this.end,
        color: this.color,
        field_number: this.field_number,
        // user_id: this.user_id antes de poner this.user.id
        user_id: this.user.id
        })
        .then(response => {
          console.log(response);
          // EventBus.$emit('event-created', response.data)
          this.getEvents();
          this.createOpen = false;
          this.resetForm();
          this.$swal({
            title: response.data.title,
            text:  response.data.message,
            icon:  response.data.icon,
          })
        })
        .catch(err =>
          // console.log("Unable to add new event!", err.response.data)
          this.errors = err.response.data.errors
        );
    },
    closeModalUpdate() {
      this.selectedOpenShow = false;
      this.selectedOpen = false;
      this.createOpen = false;
      this.resetForm();
    },
    resetForm() {
      let d = new Date();
      this.name = '',
      this.start = '',
      this.end = '',
      this.errors = '',
      this.date = ''
    },
    getDateToday() {
      let d = new Date(); 
      this.date = moment(d).format('Y-M-DD');
    },
    // Desabilita las fechas pasadas
    allowedDates(val) {
      let d = new Date(); 
      // return val >= new Date().toISOString().substr(0, 10)
      return val >= moment(d).format('Y-M-DD')
    },
	}
}
</script>