<template>
  <div class="container mt-3">


   <div class="row ">
    <div class="col-2">
        <label class="rad-label">
        <input type="radio" class="rad-input" name="rad" @change="onChange($event)" value="" v-model="etat1">
        <div class="rad-design"></div>
        <div class="rad-text"></div>
        </label>
    </div>
      <div class="col-2">
        <label class="rad-label px-0">
            <input type="radio" class="rad-input" name="rad" @change="onChange($event)" value="En cours" v-model="etat1">
            <div class="rad-design"></div>
            <div class="rad-text">En cours</div>
        </label>
    </div>
    <div class="col-2">
        <label class="rad-label">
            <input type="radio" class="rad-input"  name="rad" @change="onChange($event)"  value="Validée" v-model="etat1">
            <div class="rad-design"></div>
            <div class="rad-text">Validée</div>
        </label>
    </div>
    <div class="col-2">
        <label class="rad-label">
            <input type="radio" class="rad-input" name="rad" @change="onChange($event)" value="Achevée" v-model="etat1">
            <div class="rad-design"></div>
            <div class="rad-text">Achevée</div>
        </label>
    </div>
     <div class="col-2">
        <label class="rad-label">
            <input type="radio" class="rad-input" @change="onChange($event)"  name="rad" value="Refusée" v-model="etat1">
            <div class="rad-design"></div>
            <div class="rad-text">Refusée</div>
        </label>
    </div>
  <div class="col-2">
        <label class="rad-label lb">

            <button class="rad-input"  @click="resetAll()" ></button>
            <div class="rad-text ">Reset</div>
        </label>
    </div>


  </div>

    <div class="row mb-0 mt-2">
    <div class="col-sm">

          <input type="text" class="form-control" id="" placeholder="" v-model="search">
    </div>
    <div class="col-sm">
      <input type="date" class="form-control" id="" placeholder="" v-model="startDate">
    </div>
    <div class="col-sm">
      <input type="date" class="form-control" id="" placeholder="" v-model="endDate">
    </div>
  </div>

  <div class="row mt-3">

  <div class="container-fluid">

    <div class="row">
    <div class="col-lg-4 col-md-6" v-for="(demande,index) in pageOfItems" :key="index">
      <div class="box">
                <div class="box-head">
                    <div class="info">
                        <h3 class="">

                        <strong>{{demande.car.marque.name | capitalFl}}</strong>  <br>
                        {{demande.car.model.model | capitalFl}} <br>


                        </h3>

                            <p class="badge " :class="{'badge-danger': demande.etat == 'Refusée','badge-dark': demande.etat == 'Achevée' ,'badge-primary': demande.etat == 'Nouvelle' || demande.etat == 'En cours','badge-success': demande.etat == 'PEC' || demande.etat == 'Affectée'}">
                               {{(demande.etat == "En cours" || demande.etat == "Nouvelle" )  ? "En cours":((demande.etat == "PEC" || demande.etat == "Affectée") ? "Validée" : ((demande.etat == "Achevée" || demande.etat == "Refusée") ? demande.etat : '')  )}}
                            </p>

                        <p class="v-info"><i class="fa fa-calendar" aria-hidden="true"></i> {{demande.date | formatDate}}<i class="fas fa-clock mx-1"></i>{{ demande.date | formatDateHour}}</p>

                    </div>
                   <div>

                    <div class="img-area">
                        <img :src="'http://127.0.0.1:8000/uploads/cars_logo/'+demande.car.marque.logo" alt="" srcset="" width="250" height="140">

                    </div>
                    <!-- <h5 class="text-center"><strong>Montant :{{' '+demande.products.reduce((acc, item) => acc + item.price, 0)}}Dh</strong></h5> -->
                </div>
                </div>
                <div class="box-end ">

                        <label class="m-0" style="font-size: 15px; font-weight: 900;color: #fff" >
                            Type :
                        </label><br>

                            <!-- <div v-for="(vt,index) in demande.products" :key="index" style="">
                            <label style="color: #fff ">
                            <i class="fa fa-check mr-1" aria-hidden="true" style="color: #00a65a"></i>
                            <Strong>{{vt.type.name}}</Strong>
                            </label>
                            <br>
                            </div> -->



                        <div class="v-footer ">
                            <div>

                            </div>
                            <div>
                             <a href="" class="btn " style="font-size: 16px;
                                        background-color: #D81324;
                                        height: 38px;
                                        " >
                                        <i class="fas fa-comment-dots" style="color: #fff;"></i>
                                        </a>
                            </div>

                    </div>



                </div>
            </div>
    </div>
   </div>

  </div>
  </div>
    <div class="row  text-center">
         <div class=" px-3 pb-0 pt-3 my-4">
            <jw-pagination :pageSize=6 :items="filteredData" @changePage="onChangePage" :labels="customLabels">
            </jw-pagination>
        </div>
    </div>
</div>

</template>

<script>
export default ({
    props : ['demandes'],
    data() {
        return {
            startDate: null,
            endDate: null,
            data:this.demandes,
            pageOfItems: [],
            needle : [""],
            search:'',
            etat1:'',
            customLabels :   {
                first: 'First',
                last: 'Last',
                previous: 'previous',
                next: 'next'
        },
        }
    },

          filters : {

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


        filteredData(){
        var vm = this
        var startDate = vm.startDate;
        var endDate = vm.endDate;
        var etat = vm.etat1
        var search = vm.search
        if (vm.startDate == null) {
            return vm.demandes.filter(v => {
            return vm.needle.some(i => v.etat.includes(i))  && (v.car.marque.name.toLowerCase().includes(search.toLowerCase()) || v.car.model.model.toLowerCase().includes(search.toLowerCase()))
        }
        )
        }
        return _.filter(vm.demandes, (function (data) {
        if ((_.isNull(startDate) && _.isNull(endDate))) {
        return true
        } else {
            var date = data.date;
            return (date >= startDate && date <= endDate) && (vm.needle.some(i => data.etat.includes(i))) && (data.car.marque.name.toLowerCase().includes(search.toLowerCase()) || data.car.model.model.toLowerCase().includes(search.toLowerCase())) ;
        }
        }))
    }
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
            this.needle  =  [""]
            this.search = ''
            this.etat1 = ''
      },
      onChange(event) {
              var data = event.target.value;
              if (data =="") {
                 this.needle = [""]
              }
              else if (data =="En cours") {
                 this.needle = ["Nouvelle","En cours"]
              }
              else if (data =="Validée") {
                 this.needle = ["Affectée","PEC"]
              }else if(data =="Achevée"){
                  this.needle = ["Achevée"]
              }else if(data =="Refusée"){
                  this.needle = ["Refusée"]
              }



          },


  },

    created() {
      //console.log(this.amount());
       // console.log(this.demandes);
    },
})
</script>


<style scoped>
input[type=text],input[type=date]{

    outline: none;
    box-shadow: none;
    background-color: #ffe4e6;
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

  background:#ad0f1d;
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
  transition: .3s;
}

.rad-input:checked+.rad-design::before {
  transform: scale(0);
}

.rad-text {
  color: hsl(0, 0%, 0%);
  margin-left: 14px;
  letter-spacing: 3px;
  text-transform: uppercase;
  font-size: 18px;
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
</style>
