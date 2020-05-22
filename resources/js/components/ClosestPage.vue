<template>
    <VContainer>
        <v-card>
            <v-card-title>
                <span class="headline">Enter your coordinates</span>
            </v-card-title>
            <v-card-text>
                <v-row>
                    <v-col cols="6">
                        <v-text-field
                            v-model="dataRequest.latitude"
                            placeholder="46.55126100"
                            type="number"
                            label="Latitude"
                            prepend-icon="mdi-latitude"
                        ></v-text-field>
                    </v-col>
                    <v-col cols="6">
                        <v-text-field
                            v-model="dataRequest.longitude"
                            placeholder="30.17438800"
                            type="number"
                            label="Longitude"
                            prepend-icon="mdi-longitude"
                        ></v-text-field>
                    </v-col>
                </v-row>
                <small>
                    <a href="https://www.maps.ie/coordinates.html" target="_blank">
                        To find the exact GPS latitude and longitude coordinates of a point on a map: https://www.maps.ie
                    </a>
                </small>
            </v-card-text>
            <v-card-actions>
                <v-spacer/>
                <v-btn
                    color="deep-purple darken-4"
                    large
                    dark
                    @click="searchClosest"
                >
                    Click to search closest now open!
                </v-btn>
                <v-spacer/>
            </v-card-actions>
        </v-card>
        <v-data-table
            :headers="headers"
            :items="chargingStations"
            sort-by="distance"
            class="elevation-1"
        >
            <template v-slot:top>
                <v-toolbar flat color="white">
                    <v-toolbar-title>API service (closest now open)</v-toolbar-title>
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
                    No data...
                </v-alert>
            </template>
        </v-data-table>
    </VContainer>
</template>

<script>
    export default {
        name: "ClosestPage",

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
                { text: 'Closes', value: 'open_to' },
                { text: 'Latitude', value: 'latitude' },
                { text: 'Longitude', value: 'longitude' },
                { text: 'Distance, km', value: 'distance' },
            ],
            chargingStations: [],
            dataRequest: {
                latitude: '',
                longitude: '',
            },
        }),

        methods: {
            searchClosest() {
                axios.post('/api/v1/charging-stations/closest-now-open', this.dataRequest)
                    .then(res => this.chargingStations = res.data.data)
                    .catch(error => console.log(error.response.data))
            },

        },
    }
</script>

<style scoped>
</style>
