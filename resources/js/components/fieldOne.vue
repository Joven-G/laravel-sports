<template>
  <section>
    <v-col>
      <v-sheet height="64" >
        <v-toolbar flat>
          <v-btn
            outlined
            class="mr-4"
            color="grey darken-2"
            @click="setToday"
          >
            Hoy
          </v-btn>
          <v-btn
            fab
            text
            small
            color="grey darken-2"
            @click="prev"
          >
            <v-icon small>
              mdi-chevron-left
            </v-icon>
          </v-btn>
          <v-btn
            fab
            text
            small
            color="grey darken-2"
            @click="next"
          >
            <v-icon small>
              mdi-chevron-right
            </v-icon>
          </v-btn>
          <v-toolbar-title v-if="$refs.calendar">
            {{ $refs.calendar.title }}
          </v-toolbar-title>
          <v-spacer></v-spacer>
          <v-menu
            bottom
            right
          >
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                outlined
                color="grey darken-2"
                v-bind="attrs"
                v-on="on"
              >
                <span>{{ typeToLabel[type] }}</span>
                <v-icon right>
                  mdi-menu-down
                </v-icon>
              </v-btn>
            </template>
            <v-list>
              <v-list-item @click="type = 'day'">
                <v-list-item-title>Hoy</v-list-item-title>
              </v-list-item>
              <v-list-item @click="type = 'week'">
                <v-list-item-title>Semana</v-list-item-title>
              </v-list-item>
              <v-list-item @click="type = 'month'">
                <v-list-item-title>Mes</v-list-item-title>
              </v-list-item>
              <v-list-item @click="type = '4day'">
                <v-list-item-title>4 días</v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>
        </v-toolbar>
      </v-sheet>
      <v-sheet  class="fg">
        <v-calendar
          ref="calendar"
          v-model="focus"
          color="primary"
          :type="type"
          :now="today"
          :value="today"
          :events="events"
          :event-color="getEventColor"
          @click:event="showEvent"
          locale="es"
        ></v-calendar>

        <!-- Modal show -->
        <v-menu
          v-model="selectedOpenShow"
            :close-on-content-click="true"
            :activator="selectedElement"
            offset-x
          >
            <v-card
              color="grey lighten-4"
              min-width="350px"
              flat
            >
              <v-toolbar
                :color="selectedEvent.color"
                dark
              >
                <v-spacer></v-spacer>
                <div v-if="user_id == indexToUpdate">
                  <v-btn icon>
                    <v-icon
                      @click="selectedOpen = true"
                    >
                      mdi-pencil
                    </v-icon>
                  </v-btn>
                  <v-btn icon>
                    <v-icon
                      @click="deleteEvent"
                    >
                      mdi-delete
                    </v-icon>
                  </v-btn>
                  <v-btn icon>
                    <v-icon>mdi-dots-vertical</v-icon>
                  </v-btn>
                </div>

                <v-btn icon>
                  <v-icon
                    @click="closeModalUpdate"
                  >
                    mdi-close
                  </v-icon>
                </v-btn>
              </v-toolbar>
            <v-card-text>

              <!-- show information event -->
              <v-row>
                <v-col
                  cols="12"
                  sm="10"
                >
                  <v-list-item two-line>
                    <v-avatar
                      :color="selectedEvent.color"
                      size="20"
                      class="mr-4"
                    ></v-avatar>
                    <v-list-item-content>
                      <v-list-item-title
                        style="font-size: 1.5em;
                              color: #3c4043;"
                        >
                        {{ selectedEvent.name }}
                      </v-list-item-title>
                      <v-list-item-subtitle
                        class="ml-3"
                      >
                        {{ getDateFormat(selectedEvent.date) }} <strong>&nbsp;·&nbsp;</strong> {{ getStartFormat(selectedEvent.start) }} - {{ getEndFormat(selectedEvent.end) }}
                      </v-list-item-subtitle>
                    </v-list-item-content>
                  </v-list-item>

                  <v-list-item two-line>
                    <v-icon
                      style="padding-right: .5em"
                    >
                      mdi-alarm-check
                    </v-icon>
                    <v-list-item-content>
                      <v-list-item-title
                        style="font-size: 1.4em;
                              color: #3c4043;"
                        >
                        Tiempo Alquilado
                      </v-list-item-title>
                      <v-list-item-subtitle
                        style="font-size: 1em;
                              color: #3c4043;"
                        class="ml-3"
                        >
                        {{ selectedEvent.hour }}
                      </v-list-item-subtitle>
                    </v-list-item-content>
                  </v-list-item>

                </v-col>
              </v-row>

            </v-card-text>
          </v-card>
        </v-menu>

        <!-- Modal click fecha creada (EDIT) -->
        <v-menu
          v-model="selectedOpen"
            :close-on-content-click="false"
            offset-x
          >
            <v-card
              color="grey lighten-4"
              min-width="350px"
              flat
            >
              <v-toolbar
                :color="selectedEvent.color"
                dark
              >
                <v-toolbar-title v-html="selectedEvent.name"></v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn icon>
                  <v-icon @click="closeModalUpdate">
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
                    v-model="dateEdit"
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
                      @input="dateEdit = false"
                      locale="es"
                    ></v-date-picker>
                  </v-menu>
                </v-col>
                <!-- <v-spacer></v-spacer> -->
              </v-row>

              <!-- Time Picker Start -->
              <v-row>
                <v-col
                  cols="11"
                  sm="5"
                >
                  <v-dialog
                    ref="dialog"
                    v-model="modalTimeStartEdit"
                    :return-value.sync="timeStartEdit"
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
                    <!-- :min="endHoraMinutos" -->
                    <v-time-picker
                      v-model="start"
                      format="24hr"
                    >
                      <v-spacer></v-spacer>
                      <v-btn
                        text
                        color="primary"
                        @click="modalTimeStartEdit = false"
                      >
                        Cancel
                      </v-btn>
                      <v-btn
                        text
                        color="primary"
                        @click="$refs.dialog.save(timeStartEdit); modalTimeStartEdit = false"
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
                    v-model="modalTimeEndEdit"
                    :return-value.sync="timeEndEdit"
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
                      format="24hr"
                    >
                      <v-spacer></v-spacer>
                      <v-btn
                        text
                        color="primary"
                        @click="modalTimeEndEdit = false"
                      >
                        Cancel
                      </v-btn>
                      <v-btn
                        text
                        color="primary"
                        @click="$refs.dialog.save(timeEndEdit)"
                      >
                        OK
                      </v-btn>
                    </v-time-picker>
                  </v-dialog>
                </v-col>
              </v-row>

            </v-card-text>
            <v-card-actions v-if="user_id == indexToUpdate">
              <v-btn
                text
                color="secondary"
                @click="closeModalUpdate"
              >
                Cancel
              </v-btn>
              <v-btn
                text
                color="secondary"
                @click="updateEvent"
              >
                Actualizar
              </v-btn>
            </v-card-actions>
            <v-card-actions v-else>
              <v-btn
                text
                color="secondary"
                @click="closeModalUpdate"
              >
                Cancel
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-menu>

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

      </v-sheet>
    </v-col>
  </section>
</template>

<script>
  
import moment from 'moment';
let user = document.head.querySelector('meta[name="user"]');

export default {
  data: () => ({
    today: null,
    events: [],
    selectedEvent: {},    
    selectedElement: null,
    selectedOpen: false,
    selectedOpenShow: false,

    focus: '',
    type: 'week',
    typeToLabel: {
      month: 'Mes',
      week: 'Semana',
      day: 'Día',
      '4day': '4 Días',
    },

    createEvent: {},
    createElement: null,
    createOpen: false,

    // Hora Time Picker
    // time: null, // Time Edit
    // time1: null, // Time Create
    
    // Hora Time Picker
    timeStartEdit: null,
    timeEndEdit: null,
    timeStartCreate: null,
    timeEndCreate: null,

    // Modals time Picker
    modalTimeStartEdit: false,
    modalTimeEndEdit: false,
    modalTimeStartCreate: false,
    modalTimeEndCreate: false,

    // Date Picker
    date: null,
    menuColor: false,
    // modal: false, borra si no sirve
    dateCreate: false,
    dateEdit : false,
    
    name: null,
    start: null,
    end: null,
    color: '#1976D2FF',
    hour: null,
    field_number: '1',
    user_id: null,

    indexToUpdate: "",
    other: false,
    id: "",

    errors: '',
  }),
  created() {
    this.getEvents();
    // console.log(new Date().toISOString().substr(0, 10));
    // console.log(moment("20111031", "YYYYMMDD").fromNow());
  },
  mounted () {
    this.$refs.calendar.scrollToTime('08:00');
    this.$refs.calendar.checkChange();
    // console.log(new Date().toISOString().substr(0, 10));
    this.getDateToday();
    EventBus.$on('open-modal-create', state => {
      this.createOpen = state;
    });
  },
  computed: {
    user() {
      return JSON.parse(user.content);
    },
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
  methods: {
    getDateFormat(date) {
      moment.locale('es');
      return moment(date).format('dddd[,] D [de] MMMM');
    },
    getStartFormat(start) {
      return moment(start).format('h:mma');
    },
    getEndFormat(end) {
      return moment(end).format('h:mma');
    },    
    showEvent ({ nativeEvent, event }) {
      const open = () => {
        this.selectedEvent = event
        // Obteniendo el ID del usuario auth
        this.indexToUpdate = this.user.id;
        
        this.id = event.id;
        this.name = event.name;
        this.date = event.date;
        this.start = moment(event.start).format('HH:mm');
        this.end   = moment(event.end).format('HH:mm');
        this.color = event.color;
        this.user_id = event.user_id;
        // this.user_id = this.indexToUpdate;

        this.selectedElement = nativeEvent.target
        setTimeout(() => {
          this.selectedOpen = false
          this.selectedOpenShow = true
        }, 10)
      }

      if (this.selectedOpenShow) {
        this.selectedOpenShow = false
        setTimeout(open, 10)
      } else {
        open()
      }

      nativeEvent.stopPropagation()
    },
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
    closeModalUpdate() {
      this.selectedOpenShow = false;
      this.selectedOpen = false;
      this.createOpen = false;
      this.resetForm();
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
          // console.log(response);
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
    getEvents() {
      axios.get("/onefields")
        .then(response => {
          this.events = response.data.data.filter(event => event.field_number === '1')
        })
        .catch(err => console.log(err.response.data));

        // EventBus.$on('event-created', status => {
        //   this.events.push(status);
        // })
    },
    updateEvent() {
      axios.put("/onefields/" + this.id, {
        id: this.id,
        name:  this.name,
        date:  this.date,
        start: this.start,
        end:   this.end,
        color: this.color,
        field_number: this.field_number,
        // user_id: this.user_id
        })
        .then(response => {
          this.getEvents();
          this.selectedOpen = false;
          this.resetForm();
          // console.log(response);
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
    deleteEvent() {
      this.$swal({
        title: '¿Estás seguro?',
        text:  'No podrás revertir los cambios',
        icon:  'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, Eliminalo!'
      }).then((result) => {
        if (result.isConfirmed) {
          axios.delete("/onefields/" + this.id)
          .then(response => {
            this.getEvents();
            this.selectedOpen = false;
            this.selectedOpenShow = false;
            this.resetForm();
            // console.log(response);
            this.$swal({
              title: 'Eliminado!',
              text: 'Tu reserva fue eliminada.',
              icon: 'success'
            });

          })
          .catch(err =>
            console.log("Unable to delete event!", err.response.data)
          )
        }
      })
    },

    resetForm() {
      this.name = '',
      this.start = '',
      this.end = '',
      this.errors = ''
      // this.date = ''
    },
    
    viewDay ({ date }) {
      this.focus = date
      this.type = 'day'
    },
    setToday () {
      this.focus = ''
    },
    prev () {
      this.$refs.calendar.prev()
    },
    next () {
      this.$refs.calendar.next()
    },
    getEventColor(event) {
      return event.color
    },
    getDateToday() {
      let d = new Date(); 
      let m = moment(d).format('Y-M-DD');
      // return m;
      this.today = m;
      this.date = m;
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

<style scoped>
.my-event {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  border-radius: 2px;
  background-color: #1867c0;
  color: #ffffff;
  border: 1px solid #1867c0;
  font-size: 12px;
  padding: 3px;
  cursor: pointer;
  margin-bottom: 1px;
  left: 4px;
  margin-right: 8px;
  position: relative;
}

.my-event.with-time {
  position: absolute;
  right: 4px;
  margin-right: 0px;
}
</style>


