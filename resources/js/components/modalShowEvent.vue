<template>
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
                @click="openModalUpdate"
              >
                mdi-pencil
              </v-icon>
            </v-btn>
            <!-- <v-btn icon>
              <v-icon
                @click="deleteEvent"
              >
                mdi-delete
              </v-icon>
            </v-btn> -->
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
</template>

<script>
	import moment from 'moment';
	let user = document.head.querySelector('meta[name="user"]');
	export default {
		props: ['selectedEvent', 'selectedElement', 'selectedOpenShow', 'selectedEvent', 'closeModalUpdate'],
		data: () => ({
	    user_id: null,

	    indexToUpdate: "",
	    isAdmin: null,
	    other: false,
		}),
    mounted() {
      EventBus.$on('close-modal-show-event', state => {
        this.selectedOpenShow = state;
      });
    },
		computed: {
	    user() {
	      return JSON.parse(user.content);
	    },
      addValueShow() {

        this.indexToUpdate = this.user.id;
        this.isAdmin = this.user.is_admin;

        // this.id = this.selectedEvent.id;
        this.user_id = this.selectedEvent.user_id;
      },
		},
		watch: {
      addValueShow() {

        this.indexToUpdate = this.user.id;
        this.isAdmin = this.user.is_admin;

        // this.id = this.selectedEvent.id;
        this.user_id = this.selectedEvent.user_id;
      },
		},
		methods: {
			openModalUpdate() {
				EventBus.$emit('open-modal-update', true);
				// EventBus.$emit('close-modal-show-event', false);
			},
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
		}
	}
</script>