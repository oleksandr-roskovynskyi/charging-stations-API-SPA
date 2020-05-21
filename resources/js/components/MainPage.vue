<template>
    <VContainer>
        <v-data-table
            :headers="headers"
            :items="chargingStations"
            sort-by="name"
            class="elevation-1"
        >
            <template v-slot:top>
                <v-toolbar flat color="white">
                    <v-toolbar-title>API service (all)</v-toolbar-title>
                    <v-divider
                        class="mx-4"
                        inset
                        vertical
                    ></v-divider>
                    <v-spacer></v-spacer>
                    <v-dialog v-model="dialog" max-width="500px">
                        <template v-slot:activator="{ on }">
                            <v-btn color="success" dark class="mb-2" v-on="on">Add new</v-btn>
                        </template>
                        <v-card>
                            <v-card-title>
                                <span class="headline">{{ formTitle }}</span>
                            </v-card-title>

                            <v-card-text>
                                <v-container>
                                <v-row>
                                    <v-col cols="12">
                                        <v-text-field v-model="editedItem.name" label="Name"></v-text-field>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col cols="12">
                                        <v-text-field v-model="editedItem.city" prepend-icon="place" label="City"></v-text-field>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col cols="6">
                                        <v-text-field v-model="editedItem.open_from" type="time" label="Open from"></v-text-field>
                                    </v-col>
                                    <v-col cols="6">
                                        <v-text-field v-model="editedItem.open_to" type="time" label="To"></v-text-field>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col cols="12">
                                        <v-text-field v-model="editedItem.latitude" type="number" label="Latitude"></v-text-field>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col cols="12">
                                        <v-text-field v-model="editedItem.longitude" type="number" label="Longitude"></v-text-field>
                                    </v-col>
                                </v-row>
                                </v-container>
                            </v-card-text>

                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="green darken-1" text @click="close">Close</v-btn>
                                <v-btn color="green darken-1" text @click="save">Save</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </v-toolbar>
            </template>
            <template v-slot:item.action="{ item }">
                <v-icon
                    small
                    class="mr-2"
                    color="purple darken-4"
                    @click="editItem(item)"
                >
                    edit
                </v-icon>
                <v-icon
                    small
                    @click="deleteItem(item)"
                    color="red accent-4"
                >
                    delete
                </v-icon>
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
        name: "MainPage",

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
                { text: 'Actions', value: 'action', sortable: false },
            ],
            chargingStations: [],
            editedIndex: -1,
            editedItem: {
                name: '',
                city: '',
                open_from: '',
                open_to: '',
                latitude: '',
                longitude: ''
            },
            defaultItem: {
                name: '',
                city: '',
                open_from: '',
                open_to: '',
                latitude: '',
                longitude: ''
            },
        }),

        computed: {
            formTitle () {
                return this.editedIndex === -1 ? 'Add new charging station' : 'Edit charging station'
            },
        },

        watch: {
            dialog (val) {
                val || this.close()
            },
        },

        created () {
            // this.initialize();
            axios.get('/api/v1/charging-stations')
                .then(res => this.chargingStations = res.data.data)
                .catch(error => console.log(error.response.data))
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

            editItem (item) {
                this.editedIndex = this.chargingStations.indexOf(item);
                this.editedItem = Object.assign({}, item);
                this.dialog = true
            },

            deleteItem (item) {
                const index = this.chargingStations.indexOf(item);
                confirm('Are you sure you want to delete this item?') && this.chargingStations.splice(index, 1)
                && axios.delete(`/api/v1/charging-stations/${item.id}`)
                    .then(res => console.log(res.data))
                    .catch(error => console.log(error.response.data))
            },

            close () {
                this.dialog = false;
                setTimeout(() => {
                    this.editedItem = Object.assign({}, this.defaultItem);
                    this.editedIndex = -1
                }, 300)
            },

            save () {
                if (this.editedIndex > -1) {
                    Object.assign(this.chargingStations[this.editedIndex], this.editedItem)
                    && axios.put(`/api/v1/charging-stations/${this.editedItem.id}`, this.editedItem)
                        .then(res => console.log(res.data))
                        // .catch(error => this.error = error.response.data.errors)
                        .catch(error => console.log(error.response.data.errors))
                    // console.log(this.editedItem.id);
                } else {
                    this.chargingStations.push(this.editedItem)
                    && axios.post('/api/v1/charging-stations/', this.editedItem)
                        .then(res => console.log(res.data))
                        // .catch(error => this.error = error.response.data.error)
                        .catch(error => console.log(error.response.data.errors))
                }
                this.close()
            },
        },
    }
</script>

<style scoped>
</style>
