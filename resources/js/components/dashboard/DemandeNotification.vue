<template>


 <transition name="fade">

    <div v-for="demande in demandes" :key="demande.id" v-if="show">
    <div id="msgbox-area" class="msgbox-area">
        <div class="msgbox-box"><div class="msgbox-content">New Demande ! from {{demande.user.first_name+' '+demande.user.last_name+' at '+ demande.user.adress}} .
        </div>
        <a class="msgbox-close" :href="'/dashboard/demandes/demande-details/'+demande.id">SEE</a>
        <button class="msgbox-close btn btn-danger" @click="show = false">Close</button>
        </div>
    </div>
    </div>

  </transition>


</template>

<script>
    export default ({
    props: ['user'],
     data(){
        return {
            demandes: [],
            show: true,
            u : this.user,
        }
      } ,

      filters : {


    },

    computed: {

    },

    mounted () {
      // Do something useful with the data in the template
      //console.log(this.vidanges)
    },

    methods: {


        hideMessage(){
            this.show = false;
        }
    },


    created() {
        Echo.private('privatedemande.'+ this.u.id)
        .listen('PrivateDemande', (e) => {
        this.demandes.push(e.demande);
        setTimeout(this.hideMessage,8000);
        });
    },


    })
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity .5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}
</style>
