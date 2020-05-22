<template>
    <v-app-bar
        color="green darken-3"
        dark
    >
        <v-menu
            left
            bottom
        >
            <template v-slot:activator="{ on }">
                <v-btn icon v-on="on">
                    <v-app-bar-nav-icon>
                    </v-app-bar-nav-icon>
                </v-btn>
            </template>

            <v-list>
                <v-list-item
                    v-for="item in items"
                    :key="item.title"
                    :to="item.to"
                >
                    <v-list-item-title>{{ item.title }}</v-list-item-title>
                </v-list-item>
            </v-list>
        </v-menu>
        <v-toolbar-title class="pl-4">
            <span class="logo pr-3">
                <RouterLink to="/">Charging stations</RouterLink>
            </span>
        </v-toolbar-title>
        <v-toolbar-items
            class="hidden-sm-and-down">
            <router-link
                v-for="item in items"
                :key="item.title"
                :to="item.to"
            >
                <v-btn
                    class="mt-4"
                    text
                >
                    <v-icon>
                        {{ item.icon }}
                    </v-icon>
                    {{ item.title }}
                </v-btn>
            </router-link>
        </v-toolbar-items>
        <v-spacer></v-spacer>
    </v-app-bar>
</template>

<script>
    export default {
        name: 'Toolbar',
        data() {
            return {
                items: [
                    { title : 'All', to:'/',    icon: 'mdi-battery-charging-100'},
                    { title : 'My city', to:'/city',    icon: 'mdi-city'},
                    { title : 'Now open', to:'/now-open', icon: 'mdi-clock-fast'},
                    { title : 'Closest now open', to:'/closest-now-open', icon: 'mdi-flash'},
                ],
            }
        }
    }
</script>

<style scoped lang="scss">
    .logo {
        font-weight: 900;
        font-family: 'Play', sans-serif;
        font-size: 22px;
        margin-right: 20vw;

        a {
            color: #fcfcfc;
            text-decoration: none;
        }
    }

    .hidden-sm-and-down a {
        color: #fcfcfc;
        text-decoration: none;
    }
</style>
