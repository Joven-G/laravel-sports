<template>
  <v-col @click="closeModalUpdate()">
    <v-sheet height="64" >
      <v-toolbar flat>
        <v-btn
          outlined
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
        <v-btn
          outlined
          color="grey darken-2"
          class="mr-4"
          @click="showCreateEvent"
        >
          Reservar
        </v-btn>
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
    <v-sheet height="600">
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
    </v-sheet>
  </v-col>
</template>

<script>
  export default {
    props: ['events', 'showEvent'],
    data: () => ({
      today: null,
      focus: '',
      type: 'week',
      typeToLabel: {
        month: 'Mes',
        week: 'Semana',
        day: 'Día',
        '4day': '4 Días',
      },
    }),
    created() {
      this.closeModalUpdate();
    },
    mounted () {
      this.$refs.calendar.scrollToTime('08:00');
      this.$refs.calendar.checkChange();
    },
    methods: {
      showCreateEvent() {
        EventBus.$emit('open-modal-create', true)
      },
      closeModalUpdate() {
        EventBus.$emit('close-modal-update', false);
        // EventBus.$emit('close-modal-show-event', false);
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
    }
  }
</script>