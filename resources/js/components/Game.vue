<template>
    <div class="container">
        <div class="flex flex-col">
            <div v-for="guess in this.game.guesses" class="flex">
                <div v-for="letter in guess" class="p-2 border">
                    {{ letter.letter }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            axios.get('api/game')
                .then((response) => this.game = response.data)
                .catch(function (error) {
                    alert(error.data.error)
                })
        },
        data: function () {
            return {
                game: {
                    guesses: [],
                    status: 'playing',
                    word: null,
                }
            }
        },
        methods: {

        }
    }
</script>
