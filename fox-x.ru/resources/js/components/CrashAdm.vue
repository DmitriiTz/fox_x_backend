<template>

</template>

<script>
    export default {
        props:[
            'user',
        ],
        data (){
            return {
                messages: [],
                textMessage: ''
            }
        },
        mounted() {
            window.Echo.channel('bet-adm').listen('AdmCrash', ({game}) => {
                admGame(game)
            })
        },
        methods: {
            sendMessage() {
                axios.post('/crash/new-bet', {body: this.textMessage});
            }
        }
    }

    function admGame(game){
        var i = game;
        function intervalS(){
            i = i + 0.025;
            if(i < co){
                initGraph(i);
            }else{
                clearInterval(intS);
            }
        }
        intS = setInterval(intervalS, 25);

        getLastBets();
    }
</script>
