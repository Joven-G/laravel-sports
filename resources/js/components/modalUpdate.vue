<template>
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
          <div v-if="user_id == indexToUpdate || isAdmin == 1">
            <v-tooltip bottom>
              <template v-slot:activator="{ on, attrs }">
                <v-icon
                  class="mr-4"
                  dark
                  v-bind="attrs"
                  v-on="on"
                  :disabled="disabled"
                  @click="updateEvent"
                >
                  mdi-pencil
                </v-icon>
              </template>
              <span>Guardar cambios</span>
            </v-tooltip>
          </div>
          <v-toolbar-title v-html="selectedEvent.name"></v-toolbar-title>
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
                v-model="selectedEvent.date"
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
<!--       <v-card-actions v-if="user_id == indexToUpdate || isAdmin == 1">
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
      </v-card-actions> -->
<!--       <v-card-actions v-else>
        <v-btn
          text
          color="secondary"
          @click="closeModalUpdate"
        >
          Cancel
        </v-btn>
      </v-card-actions> -->
    </v-card>
  </v-menu>
</template>

<script>
  import moment from 'moment';
  let user = document.head.querySelector('meta[name="user"]');
  export default {
    props: ['field_number', 'getEvents', 'closeModalUpdate', 'allowedDates', 'selectedOpen', 'selectedEvent', 'getEvents', 'resetForm'],
    data: () => ({
      // selectedEvent: {},
      selectedElement: null,
      // selectedOpen: false,
      selectedOpenShow: false,
      // Hora Time Picker
      timeStartEdit: null,
      timeEndEdit: null,
      // Modals time Picker
      modalTimeStartEdit: false,
      modalTimeEndEdit: false,
      disabled: false,
     // Date Picker
      date: null,
      menuColor: false,
      dateCreate: false,
      dateEdit : false,
      name: null,
      start: null,
      end: null,
      color: '#1976D2FF',
      hour: null,
      user_id: null,
      indexToUpdate: "",
      isAdmin: null,
      other: false,
      id: "",
      errors: '',
    }),
    computed: {
      user() {
        return JSON.parse(user.content);
      },
      addValueR() {
        this.indexToUpdate = this.user.id;
        this.isAdmin = this.user.is_admin;
        this.id = this.selectedEvent.id;
        this.name = this.selectedEvent.name;
        this.date = this.selectedEvent.date;
        this.start = moment(this.selectedEvent.start).format('HH:mm');
        this.end   = moment(this.selectedEvent.end).format('HH:mm');
        this.color = this.selectedEvent.color;
        this.user_id = this.selectedEvent.user_id;
      },
    },
    watch: {
      addValueR() {
        this.indexToUpdate = this.user.id;
        this.isAdmin = this.user.is_admin;
        this.id = this.selectedEvent.id;
        this.name = this.selectedEvent.name;
        this.date = this.selectedEvent.date;
        this.start = moment(this.selectedEvent.start).format('HH:mm');
        this.end   = moment(this.selectedEvent.end).format('HH:mm');
        this.color = this.selectedEvent.color;
        this.user_id = this.selectedEvent.user_id;
      },
    },
    methods: {
      updateEvent() {
        this.disabled = true;

        axios.put("/onefields/" + this.id, {
          id: this.id,
          name:  this.name,
          date:  this.date,
          start: this.start,
          end:   this.end,
          color: this.color,
          field_number: this.field_number,
          user_id: this.user_id
          })
          .then(response => {
            this.getEvents();
            this.disabled = false;
            this.errors = '';
            // this.selectedOpen = false;
            EventBus.$emit('close-modal-update', false)
            this.resetForm();
            // console.log(response);
            this.$swal({
              title: response.data.title,
              text:  response.data.message,
              icon:  response.data.icon,
            })
          })
          .catch(err => {
            this.errors = err.response.data.errors;
            this.disabled = false;
          });
      },
    }
  }
</script>
<style>
.theme--light.v-btn.v-btn--disabled:not(.v-btn--flat):not(.v-btn--text):not(.v-btn-outlined) {
  color: fuchsia !important;
}
</style>