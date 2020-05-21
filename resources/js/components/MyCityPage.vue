<template>
    <VContainer>
        <v-card>
            <v-card-title>
                <span class="headline">Enter your city</span>
            </v-card-title>
            <v-card-text>
                <v-combobox
                    label="City"
                    v-model="cityRequest.city"
                    :items="cityItems.cityNames"
                    prepend-icon="mdi-city"
                    placeholder='"Київ" for example'
                    outlined
                ></v-combobox>
            </v-card-text>
            <v-card-actions>
                <v-spacer/>
                <v-btn
                    color="deep-purple darken-4"
                    large
                    dark
                    @click="citySearch"
                >
                    Click to search!
                </v-btn>
                <v-spacer/>
            </v-card-actions>
        </v-card>
        <v-data-table
            :headers="headers"
            :items="chargingStations"
            sort-by="name"
            class="elevation-1"
        >
            <template v-slot:top>
                <v-toolbar flat color="white">
                    <v-toolbar-title>API service (city)</v-toolbar-title>
                    <v-divider
                        class="mx-4"
                        inset
                        vertical
                    ></v-divider>
                    <v-spacer></v-spacer>
                </v-toolbar>
            </template>

            <template v-slot:no-data>
                <v-alert
                    type="info"
                    color="green darken-1"
                >
                    Loading...
                </v-alert>
            </template>
        </v-data-table>
    </VContainer>
</template>

<script>
    export default {
        name: "MyCityPage",

        data: () => ({
            dialog: false,
            headers: [
                {
                    text: 'Name',
                    align: 'left',
                    sortable: true,
                    value: 'name',
                },
                { text: 'City', value: 'city' },
                { text: 'Open from', value: 'open_from' },
                { text: 'To', value: 'open_to' },
                { text: 'Latitude', value: 'latitude' },
                { text: 'Longitude', value: 'longitude' },
            ],
            chargingStations: [],
            cityRequest: {
                city: '',
            },
            cityItems: {
                cityNames: [
                    'Вінниця',
                    'Луцьк',
                    'Дніпро',
                    'Донецьк',
                    'Житомир',
                    'Ужгород',
                    'Запоріжжя',
                    'Івано-Франківськ',
                    'Київ',
                    'Кропивницький',
                    'Луганськ',
                    'Львів',
                    'Миколаїв',
                    'Одеса',
                    'Полтава',
                    'Рівне',
                    'Суми',
                    'Тернопіль',
                    'Харків',
                    'Херсон',
                    'Хмельницький',
                    'Черкаси',
                    'Чернівці',
                    'Чернігів'
                ],
            }
        }),

        created () {
            this.initialize();
        },

        methods: {
            initialize () {
                this.chargingStations = [
                    {
                        name: '',
                        city: '',
                        open_from: '',
                        open_to: '',
                        latitude: '',
                        longitude: ''
                    },
                ]
            },

            citySearch() {
                axios.post('/api/v1/charging-stations/city', this.cityRequest)
                    .then(res => this.chargingStations = res.data.data)
                    .catch(error => console.log(error.response.data))
            },

        },
    }
</script>

<style scoped>
</style>
