<template>
    <div>
        <div class="container checkoutBox">
    <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-12">
                    <div class="box">
                        <h3 class="text-primary text-uppercase mb-3">Products in your cart</h3>
                        <div class="plan-selection" v-for="item in items" :key="item.id">
                            <div class="plan-data" v-if="item.name">
                                <label for="question1">{{item.name}}</label>
                                    <span class="plan-price text-primary">
                                        Price: {{item.price * item.quantity}} MAD
                                    </span>
                                <p class="plan-text">

                                    <input type="button"
                                     class="btn btn-primary btn-sm" @click="decrementValue(item)"
                                      value="-"  />
                                    <input type="number" readonly name="quantity" class=""
                                     :value="item.quantity" max="5" min="1" id="number" style="height: 36px;" />
                                    <input type="button" class="btn btn-primary btn-sm"
                                     @click="incrementValue(item)" value="+" />
                                </p>

                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12">
                    <div class="widget">

                        <h4 class="text-primary text-uppercase">Order Summary</h4>
                        <div class="summary-block" v-for="summaryItem in items" :key="summaryItem.id">
                            <div class="summary-content" v-if="summaryItem.name">
                                <div class="summary-head">
                                <h5 class="summary-title">
                                {{summaryItem.name}}
                                </h5></div>
                                <div class="summary-price">
                                    <p class="summary-text text-primary">
                                         {{summaryItem.price * summaryItem.quantity}} MAD
                                    </p>
                                    <span class="summary-small-text text-primary pull-right">
                                        Q   {{summaryItem.quantity}} x
                                        P   {{summaryItem.price}}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="summary-block">
                            <div class="summary-content">
                               <div class="summary-head"> <h5 class="summary-title">Total</h5></div>
                                <div class="summary-price">
                                    <p class="summary-text text-primary">{{ totalAmount }} MAD</p>
                                    <span class="summary-small-text pull-right"></span>

                                </div>
                            </div>

                            <button class="btn btn-primary" @click="placeOrder()">Place Order</button>
                        </div>
                    </div>

                </div>
            </div>
    </div>
    </div>
</template>
<script>
    export default {
       data(){
           return {
               items: [],
               totalAmount:0,
               firstName:'',
               lastName:'',
               address:'',
               city:'',
               state:'',
               zipCode:'',
               email:'',
               phone:'',
               country:'',
               cardType: '',
               expirationMonth:'',
               expirationYear:'',
               cvv:'',
               cardNumber:''
           }
       },
       computed: {

       },
       methods:{

        incrementValue(item){

            if (item.quantity < 5) {
                 item.quantity = item.quantity + 1;
                this.totalAmount = this.totalAmount + item.price
            }

        },

        decrementValue(item){


            if (item.quantity > 1) {
                item.quantity = item.quantity - 1;
                this.totalAmount = this.totalAmount - item.price
            }


        },
          async placeOrder(){
             await axios.post('/place-order', {
                    'items' : this.items
                });
            window.location.href = '/check';

           },
           async getCartItems(){
                 let response = await axios.get('/checkout/get/items');
                 this.items = response.data.finalData;
                 this.totalAmount = response.data.amount
                 console.log(response.data.finalData);
           },
           async getUserAddress(){
               if(this.firstName != '' && this.address != '' && this.cardNumber && this.cvv)
               {
                   // Process payment.
                    let response = await axios.post('/process/user/payment', {
                        // 'firstName':this.firstName,
                        // 'lastName':this.lastName,
                        // 'address':this.address,
                        // 'city':this.city,
                        // 'state':this.state,
                        // 'zipCode':this.zipCode,
                        // 'email':this.email,
                        // 'phone':this.phone,
                        // 'country':this.country,
                        // 'cardType': this.cardType,
                        // 'expirationMonth':this.expirationMonth,
                        // 'expirationYear':this.expirationYear,
                        // 'cvv':this.cvv,
                        // 'cardNumber':this.cardNumber,
                        'amount': this.items.totalAmount,
                        'orders': this.items,
                    });

                    if(response.data.success){
                        this.$toastr.s(response.data.success);
                    }else{
                        this.$toastr.e(response.data.error);
                    }

                    setTimeout(()=> {
                        window.location.href= '/';
                    }, 2500);

                    console.log(response.data);
               }
               else
               {
                   this.$toastr.e('User info incomplete');
               }
           }
       },
       created(){
           this.getCartItems();

       }
    }
</script>
