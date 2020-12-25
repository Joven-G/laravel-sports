<template>
  <section>

    <calendar :events="events" :showEvent="showEvent">
    </calendar>

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
            <div v-if="user_id == indexToUpdate || isAdmin == 1">
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

    <modal-update
      :closeModalUpdate="closeModalUpdate"
      :allowedDates="allowedDates"
      :selectedOpen="selectedOpen"      
      :selectedEvent="selectedEvent"
      :field_number="field_number"
      :getEvents="getEvents"
      :resetForm="resetForm"
    >
    </modal-update>

    <!-- Modal click nueva fecha -->
    <modal-create
      :field_number="field_number"
      :allowedDates="allowedDates"
      :getEvents="getEvents"
    >
    </modal-create>
  </section>
</template>

<script>
  
import moment from 'moment';
import calendar from './calendar.vue';
import modalCreate from './modalCreate.vue';
import modalUpdate from './modalUpdate.vue';
let user = document.head.querySelector('meta[name="user"]');
export default {
  
  components: { calendar, modalCreate, modalUpdate },
  data: () => ({
    today: null,
    events: [],
    selectedEvent: {},    
    selectedElement: null,
    selectedOpen: false,
    selectedOpenShow: false,
    nativeEvent: null,
    event: null,
    createEvent: {},
    field_number: '1',
    user_id: null,
    indexToUpdate: "",
    isAdmin: null,
    other: false,
    id: "",
  }),
  created() {
    this.getEvents();
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
    },
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
        this.isAdmin = this.user.is_admin;
        // Para eliminar
        this.id = this.selectedEvent.id;
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
    getEvents() {
      axios.get("/onefields")
        .then(response => {
          this.events = response.data.data.filter(event => event.field_number === '1')
        })
        .catch(err => console.log(err.response.data));
        EventBus.$on('close-modal-update', status => {
          this.selectedOpen = status;
        })
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
    closeModalUpdate() {
      this.selectedOpenShow = false;
      this.selectedOpen = false;
      this.createOpen = false;
      this.resetForm();
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
