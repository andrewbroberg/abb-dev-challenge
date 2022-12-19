<template>
    <div class="container">
    </div>
</template>

<script>
    export default {
        mounted() {
            axios.get('https://wordle-challenge.aussiebb.dev/game')
        }
    }
</script>
