<template>
<div class="mecanicien-demande">

    <div class="row ">
        <div class="row justify-content-center">
            <input type="text"  placeholder="Mark ..."
                class="form-control mx-1 col" v-model="search">
            <input type="date" name="" id="" class="form-control mx-1 col" v-model="startDate">
            <input type="date" name="" id="" class="form-control mx-1 col" v-model="endDate">
        </div>

         <div class="row justify-content-center mt-4">
          <label class="ct col">All
            <input type="radio" checked="checked"  value="" v-model="etat1">
            <span class="checkmark"></span>
            </label>
           <label class="ct col">Affected
            <input type="radio" checked="checked"  value="Affected" v-model="etat1">
            <span class="checkmark"></span>
            </label>
            <label class="ct col">Completed
            <input type="radio"  value="Completed" v-model="etat1">
            <span class="checkmark"></span>
            </label>
            <label class="ct col">Handling
            <input type="radio"  value="Handling" v-model="etat1">
            <span class="checkmark"></span>
            </label>

            <button class="btn btn-primary" @click="reset">Reset</button>


        </div>

        <div class="demandes-list container px-4">
            <ul class="widget-demandes row" >
                <li class="col-md-6 v-area d-flex" v-for="v in pageOfItems" :key="v.id">
                    <div class="img mr-3">
                     <img :src="'/uploads/cars_logo/'+v.car.marque.logo" class="img-responsive" alt="">
                    </div>
                    <div class="details">
                        <div class="name">
                        <h3 >{{v.car.marque.name}} </h3>
                         <h3>{{v.car.model.model}} </h3>
                        </div>
                        <div class="time">

                            <label class="text-muted" style="font-size: 15px; font-weight: 900">
                            <i class="fas fa-oil-can" aria-hidden="true">
                            </i>
                            Type : </label><br>

                            <div v-for="(vt,index) in v.services" :key="index">
                            <label >
                            <i class="fa fa-check mr-1" aria-hidden="true" style="color: #00a65a"></i>
                            <Strong>{{vt.name}}</Strong>
                            </label>
                            <br>
                            </div>
                             <i class="fa fa-calendar" aria-hidden="true"></i> {{v.date | formatDate}}<i class="fas fa-clock mx-1"></i>{{ v.date | formatDateHour}}
                        </div>
                        <div class="type">

                            <span class="badge  p-2" :class="{'bg-success': v.etat == 'Affected','bg-danger': v.etat == 'Completed','bg-warning': v.etat == 'Handling'}">{{v.etat}}</span>



                            <a :href="'/mechanic/demande/'+v.id+'/details'" class="badge  bg-warning p-2"  >
                                        <i class="fas fa-eye" style="font-size:14px"></i></a>


                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <div class="row justify-content-center">
         <div class=" px-3 pb-0 pt-3 my-4">
            <jw-pagination :pageSize=10 :items="filteredData" @changePage="onChangePage" >
            </jw-pagination>
        </div>
        </div>

    </div>

</div>
</template>

<script>

export default {


    props: ['demandes'],

     data(){
    return{
        startDate: null,
        endDate: null,
        data:this.demandes,
        pageOfItems: [],

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
            return v.etat.includes(etat) && (v.car.marque.name.toLowerCase().includes(search.toLowerCase()) || v.car.model.model.toLowerCase().includes(search.toLowerCase()))
        }
        )
        }
        return _.filter(vm.demandes, (function (data) {
        if ((_.isNull(startDate) && _.isNull(endDate))) {
        return true
        } else {
            var date = data.date;
            return (date >= startDate && date <= endDate) && (data.etat.includes(etat)) && (data.car.marque.name.toLowerCase().includes(search.toLowerCase()) || data.car.model.model.toLowerCase().includes(search.toLowerCase())) ;
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

    reset(){
          this.startDate =  null
           this.endDate =  null
            this.search = ''
            this.etat1 = ''
    }
  },

  created() {
      console.log(this.data);
  },

}
</script>


<style scoped>
.form-inline{
    width: 100%;
}
.ct {
    /* margin-right: 10%; */
  display: block;
  position: relative;
  padding-left: 35px;
  margin-bottom: 12px;
  cursor: pointer;
  font-size: 22px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}
.ct label{
    padding: 20px;
}
/* Hide the browser's default checkbox */
.ct input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  height: 0;
  width: 0;
}

/* Create a custom checkbox */
.checkmark {
  position: absolute;
  top: 0;
  left: 0;
  height: 25px;
  width: 25px;
  background-color: #eee;
}

/* On mouse-over, add a grey background color */
.ct:hover input ~ .checkmark {
  background-color: #ccc;
}

/* When the checkbox is checked, add a blue background */
.ct input:checked ~ .checkmark {
  background-color: #2196F3;
}

/* Create the checkmark/indicator (hidden when not checked) */
.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the checkmark when checked */
.ct input:checked ~ .checkmark:after {
  display: block;
}

/* Style the checkmark/indicator */
.ct .checkmark:after {
  left: 8px;
    top: 2px;
    width: 10px;
    height: 18px;
    border: solid white;
    border-width: 0 3px 3px 0;
    transform: rotate(45deg);
}

.widget-demandes li {
    border-bottom: 1px solid #ebebeb;
    padding: 15px 0;

}
.widget-demandes li > .img {
    float: left;
    margin-top: 8px;
    width: 80px;
    height: 80px;
    overflow: hidden;
    border-radius: 50%;
}

.widget-demandes li > .img img {

    width: 80px;
    height: 80px;
}



.widget-demandes li > .details > .name > h3 {
    color: #344644;
    font-weight: 700;
    font-size: 1rem;
}
.widget-demandes li > .details > .time {
    color: #2bb6a3;
    font-size: 1em;
    font-weight: 700;
    padding-bottom: 7px;
}

.widget-demandes li > .details > .type span{

    font-size: 1em;
    font-weight: 700;

}
</style>
