<template>
  <v-row>
    <v-col>
      <v-sheet height="64">
        <v-toolbar flat>
          <v-btn
            outlined
            class="mr-4"
            color="grey darken-2"
            @click="setToday"
          >
            Today
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
                <v-list-item-title>Day</v-list-item-title>
              </v-list-item>
              <v-list-item @click="type = 'week'">
                <v-list-item-title>Week</v-list-item-title>
              </v-list-item>
              <v-list-item @click="type = 'month'">
                <v-list-item-title>Month</v-list-item-title>
              </v-list-item>
              <v-list-item @click="type = '4day'">
                <v-list-item-title>4 days</v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>
        </v-toolbar>
      </v-sheet>
      <v-sheet height="600" @click="showCreateEvent">
        <v-calendar
          ref="calendar"
          v-model="focus"
          :type="type"
          :now="today"
          :value="today"
          :events="events"          
          color="primary"
          
          @click:event="showEvent"
        ></v-calendar>

        <!-- Modal click fecha creada -->
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
                <v-btn icon>
                  <v-icon>mdi-pencil</v-icon>
                </v-btn>
                <v-toolbar-title v-html="selectedEvent.name"></v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn icon>
                  <v-icon>mdi-heart</v-icon>
                </v-btn>
                <v-btn icon>
                  <v-icon>mdi-dots-vertical</v-icon>
                </v-btn>
              </v-toolbar>
            <v-card-text>
              <span v-html="selectedEvent.details"></span>
            </v-card-text>
            <v-card-actions>
              <v-btn
                text
                color="secondary"
                @click="selectedOpen = false"
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
                  <v-icon>mdi-heart</v-icon>
                </v-btn>
                <v-btn icon>
                  <v-icon>mdi-dots-vertical</v-icon>
                </v-btn>
              </v-toolbar>
            <v-card-text>

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
                      @input="menu2 = false"
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
            <v-card-actions>
              <v-btn
                text
                color="secondary"
                @click="createOpen = false"
              >
                Cancel
              </v-btn>
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

export default {
  data: () => ({
    today: new Date().toISOString().substr(0, 10),
    events: [
          {
            name: 'Weekly Meeting',
            start: '2019-01-07 09:00',
            end: '2019-01-07 10:00',
          },
          {
            name: 'Dos Weekly Meeting',
            start: '2019-01-07 17:00',
            end: '2019-01-07 19:00',
          },
          {
            name: 'Thomas\' Birthday',
            start: '2019-01-10',
          },
          {
            name: 'Mash Potatoes',
            start: '2019-01-09 12:30',
            end: '2019-01-09 15:00',
          },
          {
            name: 'Jugar Pelota',
            start: '2019-01-09 10:00',
            end: '2019-01-09 12:00',
            horas: '',
          },
    ],
    selectedEvent: {},
    selectedElement: null,
    selectedOpen: false,

    focus: '',
    type: 'week',
    typeToLabel: {
      month: 'Month',
      week: 'Week',
      day: 'Day',
      '4day': '4 Days',
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
    
    name: null,
    start: null,
    end: null,
  }),
  created() {
    this.getEvents();
  },
  mounted () {
    this.$refs.calendar.scrollToTime('08:00'),
    this.$refs.calendar.checkChange()
  },
  methods: {
    show() {
      let end = new Date(this.events[2].end);
      let start = new Date(this.events[2].start);
      console.log(end.getHours() - start.getHours());
      // Sería mejor usar ghetTime() y convertilo a horas o minutos. getHours redondea a horas
    },
    showEvent ({ nativeEvent, event }) {
      const open = () => {
        this.selectedEvent = event
        this.selectedElement = nativeEvent.target
        setTimeout(() => {
          this.selectedOpen = true
        }, 10)
      }

      if (this.selectedOpen) {
        this.selectedOpen = false
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
        }, 10)
      }

      if (this.createOpen) {
        this.createOpen = false
        setTimeout(open, 10)
      } else {
        open()
      }

    },
    addNewEvent() {
      axios.post("/campo-uno", {
        name: this.name,
        date: this.date,
        start: this.start,
        end: this.end
        })
        .then(data => {
          // this.getEvents();
          // this.createOpen = false;
          // this.resetForm();
          // this.$swal({
          //   title: 'Muy Bien!',
          //   text: 'Tu reserva está hecha.',
          //   icon: 'success',
          // })
        })
        .catch(err =>
          console.log("Unable to add new event!", err.response.data)
        );

      // console.log(`${this.name} - Nombre`);
      // console.log(`${this.date} - Fecha Hoy`);
      // console.log(`${this.start} - Hora inicio`);
      // console.log(`${this.end} - Hora fin`);
    },
    getEvents() {
      axios.get("/campo-uno")
        .then(resp => (this.events = resp.data.data))
        .catch(err => console.log(err.resp.data));
    },
    resetForm() {
      this.name = '',
      this.start = '',
      this.end = ''
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
