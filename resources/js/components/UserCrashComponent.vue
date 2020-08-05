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
            window.Echo.channel('bet').listen('JoinCrash', ({info, cashout, bet}) => {
                addBet(info, cashout, bet)
            })
        },
        methods: {
            sendMessage() {
                axios.post('/crash/new-bet', {body: this.textMessage});

                this.messages.push(this.textMessage);

                this.textMessage = '';
            }
        }
    }

    function addBet(info, cashout, bet){
        $('.block-state-crash').append(`
                            <div class="crash-table-body" data-cashout="`+cashout+`" data-bet="`+bet+`">
                                <div class="crash-body-title img">
                                    <img src="`+info.image+`" alt="avatar">
                                    <div class="name-user">`+info.name+`</div>
                                </div>
                                <div class="crash-body-title bet">`+bet+`</div>
                                <div class="crash-body-title cash">-</div>
                                <div class="crash-body-title profit">-</div>
                            </div>`);

        var ut = $('#users_total_bet').text();
        var uc = $('#users_count_bet').text();

        $('#users_total_bet').html(parseInt(ut) + parseInt(bet));
        $('#users_count_bet').html(parseInt(uc) + 1);
    }
</script>
