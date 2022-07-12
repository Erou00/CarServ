<template>
     <a class="nav-item nav-link" href="cart.html">
                <i class="fa fa-shopping-cart"></i> Cart
                <span class="">({{itemCount}})</span>
      </a>
</template>
<script>
export default {

    data() {
        return {
            itemCount:0
        }
    },
    mounted() {
        this.$root.$on('changeInCart',(item)=>{
            this.itemCount = item
        })
    },
    methods: {
           async getItemsnOnPageLoad(){
               let response = await axios.post('/cart');
               this.itemCount = response.data.items;
           }
    },

    created() {
        this.getItemsnOnPageLoad();
    },
}
</script>
