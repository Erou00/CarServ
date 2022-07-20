
<template>
  <div class="container">

  <div class="row mb-0 mt-2">

        <div class="col-sm">

        <button type="button" class="btn border-secondary" id="fClient" @click="show = true"
         style="width:100% ;height: 42px;">
            <div v-if="!selectedItem"><strong>Filter with client ▼</strong></div>
            <div v-else>
            <!-- :src="(selectedItem.image == null)?'http://cedamus.com/vidhom/images/user.png':'http://cedamus.com/vidhom/storage/'+selectedItem.image -->
            <img :src="(selectedItem.image == null) ? '/uploads/users_images/default.png' : '/uploads/users_images/'+selectedItem.image" style="height:30px;">
            <span class="mr-1"><strong>{{selectedItem.first_name | capitalFl}} {{selectedItem.last_name | capitalFl}}</strong></span>
            </div>
        </button>

        </div>

     <div class="col-sm">
        <input type="text" class="form-control" id="searchField" placeholder="" v-model="search">
    </div>
    <div class="col-sm">
      <input type="date" class="form-control" id="" placeholder="" v-model="startDate">
    </div>
    <div class="col-sm">
      <input type="date" class="form-control" id="" placeholder="" v-model="endDate">
    </div>

  </div>

  <div class="row mt-4 mt-3">
    <div class="col-2">
         <label class="rad-label">
        <input type="radio" class="rad-input" name="rad" value="" v-model="etat1">
        <div class="rad-design"></div>
        <div class="rad-text"></div>
        </label>
    </div>

<div class="col">
        <label class="rad-label">
            <input type="radio" class="rad-input" name="rad" @change="onChange($event)" value="In progress" v-model="etat1">
            <div class="rad-design"></div>
            <div class="rad-text">In progress</div>
        </label>
    </div>

      <div class="col">
       <label class="rad-label">
            <input type="radio" class="rad-input" name="rad" value="Affected" v-model="etat1">
            <div class="rad-design"></div>
            <div class="rad-text">Affected</div>
        </label>
    </div>


    <div class="col">
        <label class="rad-label">
            <input type="radio" class="rad-input" name="rad" value="Completed" v-model="etat1">
            <div class="rad-design"></div>
            <div class="rad-text">Completed</div>
        </label>
    </div>

     <div class="col">
        <label class="rad-label">
            <input type="radio" class="rad-input" name="rad" value="Handling" v-model="etat1">
            <div class="rad-design"></div>
            <div class="rad-text">Handling</div>
        </label>
    </div>

    <div class="col">
         <label class="rad-label">
            <input type="radio" class="rad-input" name="rad" value="Refused" v-model="etat1">
            <div class="rad-design"></div>
            <div class="rad-text">Refused</div>
        </label>
    </div>



  <div class="col my-3">
        <label class="rad-label lb">

            <button class="rad-input"  @click="resetAll()" ></button>
            <div class="rad-text ">Reset</div>
        </label>
    </div>


  </div>


  <div class="row">
  <div class="container-fluid">

    <div class="row">
    <div class="col-lg-4 col-md-6" v-for="demande in pageOfItems" :key="demande.id">
      <div class="box">
                <div class="box-head">
                    <div class="info">
                        <h3 class="">

                        <strong>{{demande.user.first_name | capitalFl}}</strong>  <br>
                        {{demande.user.last_name | capitalFl}} <br>


                        </h3>

                            <p class="badge " :class="{'bg-primary': demande.etat == 'In progress','bg-success': demande.etat == 'Affected','bg-danger': demande.etat == 'Completed','badge-warning': demande.etat == 'Handling','badge-dark': demande.etat == 'Refused'}">
                                {{demande.etat}}
                            </p>

                        <p class="v-info"><i class="fa fa-calendar" aria-hidden="true"></i> {{demande.date | formatDate}}<i class="fas fa-clock mx-1"></i>{{ demande.date | formatDateHour}}</p>
                        <p class="v-info"><i class="fas fa-phone-square-alt mr-2"></i>{{ demande.user.phone_number }}</p>

                    </div>
                   <div>

                    <div class="img-area">
                        <img :src="'/uploads/cars_logo/'+demande.car.marque.logo" alt="" srcset="" width="250" height="140">

                    </div>
                    <h4 class="text-center"><strong>{{demande.car.model.model}}</strong></h4>
                </div>
                </div>
                <div class="box-end ">

                        <label class="m-0" style="font-size: 15px; font-weight: 900;color: #ffff00" ><i class="fas fa-map-marker-alt" style=" color: #ffff00" aria-hidden="true"></i>
                            Adresse :
                        </label><br>




                            <label style="color: #fff " ><Strong>{{demande.address}}</Strong>
                            </label>



                        <div class="v-footer ">
                            <div>

                        <a :href="'/dashboard/mechanics/'+demande.mechanic.id" class="btn" style="font-size: 16px;
                            background-color: #D81324;
                            height: 38px;
                            color: #fff;

                            " v-if="demande.mechanic">
                          <i class="fas fa-wrench ml-2"></i> <strong>{{demande.mechanic.first_name | capitalFl}} {{demande.mechanic.last_name | capitalFl}} </strong>
                        </a>

                    </div>
                        <div>


                                <a :href="'/dashboard/demandes/demande-details/'+demande.id" class="btn " style="font-size: 16px;
                                    background-color: #D81324;
                                    height: 38px;
                                    color: #fff;
                                    " >
                                    <i class="fas fa-eye"></i></a>

                                    <a :href="'/dashboard/demandes/invoice/'+demande.id" class="btn " style="font-size: 16px;
                                        background-color: #D81324;
                                        height: 38px;
                                        color: #fff;
                                        " v-if="demande.mechanic">
                                        <i class="fas fa-file-invoice"></i></a>
                        </div>

                    </div>



                </div>
            </div>
    </div>
   </div>

  </div>
  </div>
    <div class="row justify-content-center">
         <div class=" px-3 pb-0 pt-3 my-4">
            <jw-pagination :pageSize=6 :items="filteredData" @changePage="onChangePage" :labels="customLabels">
            </jw-pagination>
        </div>
    </div>


  <Transition name="modal">
    <div v-if="show" class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container">
          <div class="modal-header">
            <h2 class="header text-center">Please select</h2>
          </div>

          <div class="modal-body">
              <input type="text" class="form-control" id="" placeholder="filtre ..." v-model="filterUser">

            <table class="table table-striped">

            <tr v-for="item in filteredUsers"
                :id="'item-'+ item.id"
                style="cursor:pointer;"
                @click="selectItem(item)">
              <td class="align-top">
                <img :src="(item.image == null) ? '/uploads/users_images/default.png' : '/uploads/users_images/'+item.image" width="40" height="40">
              </td>
              <td class="pr-3 align-top" style="width:100%;">
                <h5 v-text="item.title"></h5>
                <p style="white-space:pre-wrap;" class="text-secondary" ><strong>{{item.first_name | capitalFl}} {{item.last_name | capitalFl}}</strong></p>
              </td>
            </tr>
          </table>
          </div>

          <div class="modal-footer">
            <slot name="footer">
              <button
                class="modal-default-button btn btn-primary"
                @click="show = false"
              >OK</button>
            </slot>
          </div>
        </div>
      </div>
    </div>
  </Transition>


</div>

</template>

<script>
export default ({

    props : ['demandes'],

    data() {
        return {
            show: false,
            filterUser:'',
            items: [],
            selectedItem: null,
            startDate: null,
            endDate: null,
            data:this.demandes,
            pageOfItems: [],
            showModal:false,
            lngLat:null,

            search:'',
            etat1:'',
            customLabels :   {
                first: '<<',
                last: '>>',
                previous: '<',
                next: '>'
        },
        }
    },

      watch: {

    },

          filters : {

         cutFun  (value) {

                return value.str.substr(1, 4);

         },

        formatDate  (value) {
            if (value) {
                return moment(String(value)).format('YYYY/MM/DD');
            }
         },

         formatDateHour  (value) {
            if (value) {
                return moment(String(value)).format('H:mm');
            }
         },

          capitalFl  (value) {
            return value.charAt(0).toUpperCase() + value.slice(1);
         },
         },
    computed: {
        filteredUsers() {
        return this.items.filter(i => {
            return i.first_name.toLowerCase().includes(this.filterUser.toLowerCase()) || i.last_name.toLowerCase().includes(this.filterUser.toLowerCase())
        })

        },
   filteredData(){
        var vm = this
        var startDate = vm.startDate;
        var endDate = vm.endDate;
        var etat = vm.etat1
        var search = vm.search
        var sc = vm.selectedItem

        if (vm.startDate == null) {
            if (sc != null) {

                return vm.demandes.filter(v => {
                return (v.user.last_name.toLowerCase().includes(sc.last_name.toLowerCase()) && v.user.first_name.toLowerCase().includes(sc.first_name.toLowerCase())
                 && (v.car.marque.name.toLowerCase().includes(search.toLowerCase()) || v.car.model.model.toLowerCase().includes(search.toLowerCase())) && v.etat.includes(etat))
                }

                )
                }
            else if(sc == null){
            return vm.demandes.filter(v => {
            return v.etat.includes(etat) && ((v.user.last_name.toLowerCase().includes(search.toLowerCase()) || v.user.first_name.toLowerCase().includes(search.toLowerCase()))
                || (v.car.marque.name.toLowerCase().includes(search.toLowerCase()) || v.car.model.model.toLowerCase().includes(search.toLowerCase())))
                }
                )
            }


        }
        return _.filter(vm.demandes, (function (data) {
        if ((_.isNull(startDate) && _.isNull(endDate))) {
        return true
        } else {
            var date = data.date;
            if (sc != null) {
            return (data.user.last_name.toLowerCase().includes(sc.last_name.toLowerCase()) && data.user.first_name.toLowerCase().includes(sc.first_name.toLowerCase())

                    && (date >= startDate && date <= endDate) && (data.etat.includes(etat)) && (data.car.marque.name.toLowerCase().includes(search.toLowerCase()) || data.car.model.model.toLowerCase().includes(search.toLowerCase())))

            }
            else if (sc == null){
            return (date >= startDate && date <= endDate) && (data.etat.includes(etat)) && ((data.user.last_name.toLowerCase().includes(search.toLowerCase()) || data.user.first_name.toLowerCase().includes(search.toLowerCase())) || (data.car.marque.name.toLowerCase().includes(search.toLowerCase()) || data.car.model.model.toLowerCase().includes(search.toLowerCase()))) ;
            }

        }
        }))
    }
    },

    mounted() {
        axios.get('/dashboard/users').then(res => {
        this.items = res.data;
        });

            // Adjust modal height・・・ ④
           const modalBody = this.$refs['modal-body'];
    modalBody.style.maxHeight = parseInt(window.innerHeight*0.7) +'px';

    // Move to selected position when modal is displayed ・・・ ⑤
    $('#image-select-modal').on('shown.bs.modal', () => {

      const top = (this.selectedItem)
      ? $('#item-'+ this.selectedItem.id).position().top
      : 0;
      $('#modal-body').scrollTop(top);

    });

    },
     methods: {

    onChangePage(pageOfItems) {
            console.log(pageOfItems)
            // update page of items
            this.pageOfItems = pageOfItems;
        },

    resetAll(){
            this.startDate =  null
            this.endDate =  null
            this.selectedItem = null
            this.search = ''
            this.etat1 = ''
      },

    selectItem(item) { // Select from list ・・・ ①
        this.selectedItem = item;
        this.show = false


    },
    clearItem() { // Deselect・・・
        this.selectedItem = null;
        jQuery('#image-select-modal').modal('hide');
        console.log('tst');

    },


     },

    created() {

    },
})
</script>


<style scoped>
#searchField{
    border: 1px solid;
}
.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: table;
  transition: opacity 0.3s ease;
}

.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
}

.modal-container {
  width: 50%;
  margin: 0px auto;
  /* padding: 20px 30px; */
  background-color: #fff;
  border-radius: 2px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
  transition: all 0.3s ease;
}

.modal-header h3 {
  margin-top: 0;
  color: #42b983;
}


.modal-body {

    padding: 0rem 1rem;
}

.modal-default-button {
  float: right;
}

/*
 * The following styles are auto-applied to elements with
 * transition="modal" when their visibility is toggled
 * by Vue.js.
 *
 * You can easily play with the modal transition by editing
 * these styles.
 */

.modal-enter-from {
  opacity: 0;
}

.modal-leave-to {
  opacity: 0;
}

.modal-enter-from .modal-container,
.modal-leave-to .modal-container {
  -webkit-transform: scale(1.1);
  transform: scale(1.1);
}
.table>:not(caption)>*>* {
     padding: 0rem 0rem;
     }

input[type=text],input[type=date]{

    outline: none;
    box-shadow: none;
    border: none;
    height: 42px;
    font-weight: bold;

}
.rad-label {
  display: flex;
  align-items: center;

  border-radius: 100px;
  padding: 14px 16px;
  margin: -30px 0 0 0;

  cursor: pointer;
  transition: .3s;
}

.rad-label:hover,
.rad-label:focus-within {
  background: hsla(0, 0%, 80%, .14);
}

.rad-input {
  position: absolute;
  left: 0;
  top: 0;
  width: 1px;
  height: 1px;
  opacity: 0;
  z-index: -1;
}
.rad-label.lb {
 background-color: darkred;
}

.rad-label.lb .rad-text {
color: white;
}

.rad-design {
  width: 22px;
  height: 22px;
  border-radius: 100px;

  background:rgb(250, 11, 11);
  position: relative;
}

.rad-design::before {
  content: '';

  display: inline-block;
  width: inherit;
  height: inherit;
  border-radius: inherit;

  background: hsl(0, 0%, 90%);
  transform: scale(1.1);
  transition: .3s all;
}

.rad-input:checked+.rad-design::before {
  transform: scale(0);
}

.rad-text {
  color: hsl(0, 0%, 0%);
  margin-left: 10px;
  letter-spacing: 0px;
  text-transform: uppercase;
  font-size: 12px;
  font-weight: 900;

  transition: .3s;
}

.rad-input:checked~.rad-text {
  color: hsl(0, 0%, 40%);
}


/* ABS */
/* ====================================================== */
.abs-site-link {
  position: fixed;
  bottom: 40px;
  left: 20px;
  color: hsla(0, 0%, 0%, .5);
  font-size: 16px;
}

#overlay{
  position: fixed;
  width: 100%;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  background: rgba(0, 0, 0, .6);
  z-index: 100;
}

.modal-header,.modal-footer{
    background: black;
    color: #fff;
}
.modal-title {
    font-weight: bold;
}
.modal-footer a{
   padding: 10px;
   background-color: #ffff00;
   text-decoration: none;
   color: black;
   font-weight: bold;
   border-radius: 2px;

}
.close:hover {
    color: #ffff00;
    text-decoration: none;
}
</style>
