<template>
        <div >
            <button class="add-to-cart btn btn-default" type="button" @click.prevent="addToCart()">
            add to cart</button>
        </div>
</template>

<script>
    export default {

        data() {
            return {

            }
        },
        props : ['product','user'],
        methods : {
              async addToCart(){
                if (this.user == 0) {
                    this.$toastr.e("you need to login if you want add to cart") ;
                    return;
                }

                let response = await axios.post('/cart', {
                    'product_id' : this.product
                });

                this.$root.$emit('changeInCart',response.data.items);
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
