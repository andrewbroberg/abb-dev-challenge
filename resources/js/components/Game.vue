<template>
    <div class="container">
        <div v-if="game.word">The correct word is: {{ game.word }}</div>
        <input type="text" v-model="guess" @keyup.enter="submitGuess">
        <div class="flex flex-col">
            <div v-for="guess in this.game.guesses" class="flex mb-2">
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
        axios.get('game')
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
            },
            guess: ''
        }
    },
    methods: {
        submitGuess() {
            axios.post('guesses', {"guess": this.guess})
                .then((response) => {
                    this.game = response.data
                    this.guess = ''
                }).catch((error) => {
                    alert(error)
            })
        }
    }
}
</script>
