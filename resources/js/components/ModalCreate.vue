<template>
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
          :color="color"
          dark
        >
          <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
              <v-icon
                class="mr-4"
                dark
                v-bind="attrs"
                v-on="on"
                @click="addNewEvent"
              >
                mdi-check
              </v-icon>
            </template>
            <span>Agendar reserva</span>
          </v-tooltip>
          <v-toolbar-title v-html="name"></v-toolbar-title>
          <v-spacer></v-spacer>
          <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
              <v-icon
                dark
                v-bind="attrs"
                v-on="on"
                @click="closeModalUpdate"
              >
                mdi-close
              </v-icon>
            </template>
            <span>Cerrar</span>
          </v-tooltip>
        </v-toolbar>
      <v-card-text>

        <!-- Mensajes de validación -->
        <ul class="text-danger">
          <li v-for="error in errors">
            {{ error[0] }}
          </li>
        </ul>

        <!-- Input Name -->
        <v-row class="pb-4 mb-2">
          <v-col
            cols="12"
            sm="10"
          >
            <v-text-field
              label="Nombre del evento"
              v-model="name"
              hide-details="auto"
              prepend-icon="mdi-clipboard-text-multiple-outline "
              
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
              v-model="dateCreate"
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
                @input="dateCreate = false"
                locale="es"
              ></v-date-picker>
            </v-menu>
          </v-col>
          <!-- <v-spacer></v-spacer> -->
        </v-row>

        <!-- Time Picker Start -->
        <v-row>
          <v-col
            cols="12"
            md="5"
            sm="12"
          >
            <v-dialog
              ref="dialog"
              v-model="modalTimeStartCreate"
              :return-value.sync="timeStartCreate"
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
                  @click="modalTimeStartCreate = false"
                >
                  Cancel
                </v-btn>
                <v-btn
                  text
                  color="primary"
                  @click="$refs.dialog.save(timeStartCreate); modalTimeStartCreate = false"
                >
                  OK
                </v-btn>
              </v-time-picker>
            </v-dialog>
          </v-col>

          <!-- Time Picker End -->

          <v-col
            cols="12"
            md="5"
            sm="12"
          >
            <v-dialog
              ref="dialog"
              v-model="modalTimeEndCreate"
              :return-value.sync="timeEndCreate"
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
                  @click="modalTimeEndCreate = false"
                >
                  Cancel
                </v-btn>
                <v-btn
                  text
                  color="primary"
                  @click="$refs.dialog.save(timeEndCreate)"
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
    </v-card>
  </v-menu>
</template>

<script>
  import moment from 'moment';
  export default {
    props: ['field_number', 'getEvents', 'allowedDates'],
    data: () => ({
      createEvent: {},
      createElement: null,
      createOpen: false,

      // Date Picker
      date: null,
      menuColor: false,
      dateCreate: false,

      // Hora Time Picker
      timeStartCreate: null,
      timeEndCreate: null,

      // Modals time Picker
      modalTimeStartCreate: false,
      modalTimeEndCreate: false,

      name: null,
      start: null,
      end: null,
      color: '#1976D2FF',
      hour: null,

      errors: '',
    }),
    computed: {
      swatchStyle() {
        const { color, menu } = this
        return {
          backgroundColor: color,
          cursor: 'pointer',
          height: '30px',
          width: '30px',
          borderRadius: menu ? '50%' : '4px',
          transition: 'border-radius 200ms ease-in-out'
        }
      }
    },
    mounted () {
      this.getDateToday();
      EventBus.$on('open-modal-create', state => {
        this.createOpen = state;
      });
    },
    methods: {
      showCreateEvent () {
        const open = () => {
          setTimeout(() => {
            this.createOpen = true
          }, .10)
        }

        if (this.createOpen) {
          this.createOpen = false
          setTimeout(open, .10)
        } else {
          open()
        }
        
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
          // user_id: this.user.id
          })
          .then(response => {
            // console.log(response);
            this.getEvents();
            EventBus.$emit('close-modal-update', false)
            this.createOpen = false;
            this.resetForm();
            this.$swal({
              title: response.data.title,
              text:  response.data.message,
              icon:  response.data.icon,
            })
          })
          .catch(err => {
            // console.log("Unable to add new event!", err.response.data)
            this.resetForm();
            this.errors = err.response.data.errors
          });
      },
      getDateToday() {
        let d = new Date(); 
        let m = moment(d).format('Y-M-DD');
        // return m;
        this.today = m;
        this.date = m;
      },
      resetForm() {
        this.name = '',
        this.start = '',
        this.end = '',
        this.errors = ''
        // this.date = ''
      },
      closeModalUpdate() {
        this.selectedOpenShow = false;
        this.selectedOpen = false;
        this.createOpen = false;
        this.resetForm();
      },
      // allowedDates(val) {
      //   let d = new Date(); 
      //   // return val >= new Date().toISOString().substr(0, 10)
      //   return val >= moment(d).format('Y-M-DD')
      // },
    }
  }
</script>