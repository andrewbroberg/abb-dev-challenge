<template>
    <div class="container">
        Wordle Dev Challenge
    </div>
</template>

<script>
    export default {
        mounted() {
            axios.get('api/game')
        }
    }
</script>
