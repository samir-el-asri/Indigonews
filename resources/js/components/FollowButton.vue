<template>
    <div id="app">
        <button class="btn btn-primary mx-auto pb-2" @click="followProfile" v-text="btnTxt">follow</button>
    </div>
</template>

<script>
import { METHODS } from 'http';
    export default {

        props: ['profileId', 'follows'],

        mounted() {
            console.log('Component mounted.')
        },

        data: function(){
            return{
                status: this.follows,
            }
        },

        methods: {
            followProfile(){
                axios.post('/follow/' + this.profileId)
                    .then(res => {
                        this.status = !this.status;
                        console.log(res.data);
                    })
            }
        },

        computed: {
            btnTxt(){
                return (this.status) ? 'follow' : "unfollow";
                
                /*if(this.status)
                    return "follow";
                else
                    return "unfollow";*/
            }
        }
    }
</script>
