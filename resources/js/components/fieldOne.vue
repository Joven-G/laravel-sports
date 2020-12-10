<template>
  <v-row block>
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
      <v-sheet  @click="showCreateEvent">
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
            :close-on-content-click="false"
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

              <!-- <span >{{ selectedEvent.hour }} Horas</span> -->

              <!-- Input Name -->
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

              <!-- Date Piker -->

              <v-row>
                <v-col
                  cols="12"
                  sm="6"
                  md="6"
                >
              
                </v-col>
                <!-- <v-spacer></v-spacer> -->
              </v-row>

            </v-card-text>
          </v-card>
        </v-menu>

        <!-- Modal click fecha creada (EDIT) -->
        <v-menu
          v-model="selectedOpen"
            :close-on-content-click="false"
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
                    v-model="menuEdit"
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
                      @input="menuEdit = false"
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
                    v-model="modal1"
                    :return-value.sync="time"
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
                        @click="modal1 = false"
                      >
                        Cancel
                      </v-btn>
                      <v-btn
                        text
                        color="primary"
                        @click="$refs.dialog.save(time); modal1 = false"
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
                    v-model="modal2"
                    :return-value.sync="time"
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
                        @click="modal2 = false"
                      >
                        Cancel
                      </v-btn>
                      <v-btn
                        text
                        color="primary"
                        @click="$refs.dialog.save(time)"
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
                    v-model="menu2"
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
                      @input="menu2 = false"
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
                    v-model="modal1"
                    :return-value.sync="time1"
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
                        @click="modal1 = false"
                      >
                        Cancel
                      </v-btn>
                      <v-btn
                        text
                        color="primary"
                        @click="$refs.dialog.save(time1); modal1 = false"
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
                    v-model="modal2"
                    :return-value.sync="time"
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
                        @click="modal2 = false"
                      >
                        Cancel
                      </v-btn>
                      <v-btn
                        text
                        color="primary"
                        @click="$refs.dialog.save(time)"
                      >
                        OK
                      </v-btn>
                    </v-time-picker>
                  </v-dialog>
                </v-col>
              </v-row>
            </v-card-text>

            <!-- Input Name -->
            <v-row>
              <v-col
                cols="12"
                sm="10"
              >
              <v-row justify="center" align="center">
                <v-col class="shrink" style="min-width: 220px;">
                  <v-text-field v-model="color" hide-details class="ma-0 pa-0" solo>
                    <template v-slot:append>
                      <v-menu v-model="menu" top nudge-bottom="105" nudge-left="16" :close-on-content-click="false">
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
            <!--   <v-btn
                text
                color="secondary"
                @click="closeModalUpdate"
              >
                Cancel
              </v-btn> -->
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

      </v-sheet>
    </v-col>
  </v-row>
</template>

<script>
  
import moment from 'moment';
let user = document.head.querySelector('meta[name="user"]');

export default {
  data: () => ({
    today: new Date().toISOString().substr(0, 10),
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

    time: null, // Recuerda que las horas elegidas se guardan aquí. no estoy seguro. pero hazle un console.log
    time1: null,
    
    modal2: false,
    modal1: false,

    // Date Picker
    date: new Date().toISOString().substr(0, 10),
    menu: false,
    modal: false,
    menu2: false,
    menuEdit : false,
    
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
    let date = new Date();
    let isoDate = new Date(date.getTime() - (date.getTimezoneOffset() * 60000)).toISOString(); 
    console.log(isoDate);
    // console.log(new Date().format('dddd'))
  },
  computed: {
    user() {
      return JSON.parse(user.content);
      // console.log(user.content);
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
        console.log(`${this.selectedOpen} - open`)
        // console.log(`${this.selectedOpenShow} - before show`)
        this.selectedOpenShow = false
        // console.log(`${this.selectedOpenShow} - after show`)
        setTimeout(open, 10)
        // open()
      } else {
        // console.log(`${this.selectedOpen} - open otro`)
        open()
      }

      nativeEvent.stopPropagation()
    },
    showCreateEvent () {
      const open = () => {
        setTimeout(() => {
          this.resetForm();
          // this.selectedOpen = false
          this.createOpen = true
        }, .10)
      }

      if (this.createOpen) {
        // this.selectedOpen = false
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
          console.log(response);
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
        // ...this.datos
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

    // Desabilita las fechas pasadas
    allowedDates(val) {
      return val >= new Date().toISOString().substr(0, 10)
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


