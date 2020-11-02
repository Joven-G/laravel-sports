<template>
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
</template>

<script>
  export default {
    props: ['createOpenModal'],
    data: () => ({
      eveents: [],
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
      color: '#1976D2FF',
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
    mounted() {
      // console.log(this.createOpenModal);
    },
    created() {
      this.geetEvents();
    },
    methods: {
    addNewEvent() {
      axios.post("/campo-uno", {
        name:  this.name,
        date:  this.date,
        start: this.start,
        end:   this.end,
        color: this.color
        })
        .then(response => {
          this.geetEvents();
          this.createOpen = false;
          this.resetForm();
          this.$swal({
            title: response.data.title,
            text:  response.data.message,
            icon:  response.data.icon,
          })
        })
        .catch(err =>
          console.log("Unable to add new event!", err.response.data)
        );

      // console.log(`${this.name} - Nombre`);
      // console.log(`${this.date} - Fecha Hoy`);
      // console.log(`${this.start} - Hora inicio`);
      // console.log(`${this.end} - Hora fin`);
    },
    geetEvents() {
      axios.get("/campo-uno")
        .then(response => {
          this.eveents = response.data.data
        })
        .catch(err => console.log(err.resp.data));
    },
    resetForm() {
      this.name = '',
      this.start = '',
      this.end = '',
      this.color = ''
    },
    }
  }
</script>