<template>
    
</template>

<script>
    export default {
        data (){
            return {
                messages: [],
                textMessage: ''
            }
        },
        mounted() {
            window.Echo.channel('crash-stop').listen('StopCrash', ({game}) => {
                admGameStop(game)
            })
        },
        methods: {
            sendMessage() {
                axios.post('/crash/new-bet', {body: this.textMessage});
            }
        }
    }

    function admGameStop(game){
        clearInterval(intervalID);
        crushGraph(i_int, 7000);
        console.log('stop');
    }
</script>
