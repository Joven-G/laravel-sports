<template>
  <v-row>
    <v-col>
      <v-sheet height="400" @click="show">
        <v-calendar
          ref="calendar"
          :now="today"
          :value="today"
          :events="events"
          color="primary"
          type="week"
          @click:event="showEvent"
        ></v-calendar>

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
          </v-sheet>
    </v-col>
  </v-row>
</template>

<script>
// import Date from "./Date";
  export default {
    data: () => ({
      today: '2019-01-08',
      events: [
            {
              name: 'Weekly Meeting',
              start: '2019-01-07 09:00',
              end: '2019-01-07 10:00',
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
    }),
    mounted () {
      this.$refs.calendar.scrollToTime('08:00')
      // console.log(this.events.start);
      // this.show();
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
