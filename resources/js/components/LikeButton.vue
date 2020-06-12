<template>
    <div id="app">
        <button class="btn btn-primary mx-auto pb-2" @click="likeArticle" v-text="btnTxt">like</button>
    </div>
</template>

<script>
import { METHODS } from 'http';
    export default {

        props: ['articleId', 'likes'],

        mounted() {
            console.log('Component mounted.')
        },

        data: function(){
            return{
                status: this.likes,
            }
        },

        methods: {
            likeArticle(){
                axios.post('/like/' + this.articleId)
                    .then(res => {
                        this.status = !this.status;
                        console.log(res.data);
                    })
            }
        },

        computed: {
            btnTxt(){
                return (this.status) ? 'like' : "unlike";
                
                /*if(this.status)
                    return "like";
                else
                    return "unlike";*/
            }
        }
    }
</script>
