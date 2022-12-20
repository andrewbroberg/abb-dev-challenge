<template>
    <div class="container max-w-xs flex flex-col">
        <div v-if="game.word">The correct word is: {{ game.word }}</div>
        <input type="text" v-model="guess" @keyup.enter="submitGuess" placeholder="Guess">
        <div class="flex flex-col justify-center">
            <div v-for="guess in this.game.guesses" class="flex flex-row mt-2 items-center">
                <div v-for="letter in guess" class="flex flex-1 p-2 border justify-center" :class="letterClass(letter)">
                    {{ letter.letter }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    mounted() {
        this.apiToken = prompt("Enter your API token", this.apiToken)

        if (!this.apiToken) {
            return alert("No token provided, refresh and try again.")
        }

        axios.get('game', {
            headers: {
                'Authorization': "Bearer " + this.apiToken
            }
        })
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
            guess: '',
            apiToken: ''
        }
    },
    methods: {
        submitGuess() {
            axios.post('guesses', {"guess": this.guess}, {
                headers: {
                    'Authorization': "Bearer " + this.apiToken
                }
            })
                .then((response) => {
                    this.game = response.data
                    this.guess = ''
                }).catch((error) => {
                alert(error.response.data.message)
            })
        },
        letterClass(letter) {
            if (letter.status === 'correct') {
                return 'bg-green-700 text-white';
            }

            if (letter.status === 'incorrect') {
                return 'bg-gray-700 text-white';
            }

            if (letter.status === 'wrong_location') {
                return 'bg-yellow-700 text-white';
            }
        }
    }
}
</script>
