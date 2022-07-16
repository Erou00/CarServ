<template>
  <div class="container">
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card chat-app">
            <div id="plist" class="people-list">
                <div class="input-group">

                    <input type="text" class="form-control" placeholder="Search...">
                </div>
                <ul class="list-unstyled chat-list mt-2 mb-0">

                    <li class="clearfix" v-for="car in filteredList" :key="car.id"
                    @click="activeCar=car" >
                        <img :src="'/uploads/cars_logo/'+car.marque.logo" alt="avatar">
                        <div class="about">
                            <div class="name">{{car.title}}</div>
                            <div class="status">{{car.marque.name}} </div>
                        </div>
                    </li>


                </ul>
            </div>
            <div class="chat">
                <div class="chat-header clearfix">
                    <div class="row">

                    </div>
                </div>
                <div class="chat-history">
                    <div class="messages-content" id="privateClientMessageBox">



            <div class="message-feed" v-for="(message , index) in allMessages"  :key="index"
              :class="{'right': user.id != message.from_user_id,'left':user.id == message.from_user_id }">
                <div class="msg-body">
                    <img  src=""
                    alt="User name" width="60" height="60">
                    <div class="mf-content">
                        {{message.message}}
                    </div>
                    <small class="mf-date"><i class="fa fa-clock-o mr-1"></i>{{message.created_at | formatDate }} </small>
                </div>
            </div>


            </div>
                </div>
                <div class="chat-message clearfix">
                    <div class="input-group mb-0">
                        <button class="btn btn-primary" @click="sendMessage">
                            <i class="fa fa-send"></i>
                        </button>
                        <input type="text" class="form-control " v-model="message" placeholder="Enter text here...">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</template>

<script>
export default {

 props : ['Cars','User'],
data() {
    return {
        allMessages : [],
        cars : this.Cars,
        activeCar : null,
        user : this.User,
        message:null,
        to_user:null

    }
},

computed: {

        filteredList() {
        return this.cars.filter(client => {
            return car.title.toLowerCase().includes(this.search.toLowerCase())
        })
        }
        },

watch: {
 activeCar(val){
                console.log(this.activeCar);
                this.fetchMessages();

            },
},

filters : {
        formatDate  (value) {
            if (value) {
                return moment(String(value)).format('MM/DD/YYYY hh:mm');
            }
        }
    },

methods: {
    fetchMessages() {
            axios.get('/chat/private-user-messages/'+this.activeCar.user_id+'/'+this.activeCar.id).then(response => {
                this.allMessages = response.data;
                console.log(this.allMessages);
            });
    },
    sendMessage(){
            axios.post('/chat/private-user-messages/'+this.activeCar.id,
            {
                message: this.message,
                car : this.activeCar.messages
            }).then(response => {
                    this.message=null;
                    this.allMessages.push(response.data.message);
                    setTimeout(this.scrollToEnd,100);

            });
        },
},

created() {
    console.log(this.cars);
      Echo.private('privatechat.'+ this.user.id)
        .listen('PrivateMessage', (e) => {
        this.allMessages.push(e.message);
        });
},

}
</script>

<style>

</style>
