<template>
    <div>
        <h1>Hi.</h1>
        <router-view></router-view>
    </div>
</template>

<script>
    export default {
        name: "App",
        mounted() {
            console.log('App.vue log: Component mounted.')
        }
    }
</script>

<style scoped>
    
</style>
