<template>
<div>
    <vue-confirm-dialog></vue-confirm-dialog>

   <input type="text" class="form-control my-3"
         placeholder="Filter "
         v-model="searchTerm"  />
 <table class="table table-striped table-bordered mb-4" >
            <thead style="background: #000; color:white">
                          <tr>

                            <th>Nom</th>
                            <th>Demande Address</th>
                            <th>Date </th>
                            <th>Phone</th>
                            <th>Etat</th>
                            <th></th>
                            <th></th>
                          </tr>
                        </thead>
                        <tbody>




                            <tr v-for="demande in filteredDemande" :key="demande.id">
                                <td>{{demande.user.first_name | capitalFl}} {{demande.user.last_name | capitalFl}}</td>
                                <td>



                            <label style="color: #000 ">{{demande.address}}
                            </label>
                                </td>

                                <td>{{demande.date | formatDate}}</td>
                                <td>{{demande.user.phone_number}}</td>
                                <td>{{demande.etat}}</td>
                                <td>

                                    <a :href="'/dashboard/demandes/update-demande/'+demande.id" class="btn btn-info  py-0 " style="font-size: 16px">
                                        Edit
                                    </a>


                                        <button class="btn btn-danger m-0 py-0 "
                                        @click="handleClick(demande.id)"
                                        style="font-size: 16px"><i class="fa fa-trash m-0 p-0" aria-hidden="true"></i></button>



                                </td>
                                <td><a :href="'/dashboard/demandes/demande-details/'+demande.id">Details</a></td>

                            </tr>



                        </tbody>
                      </table>

                <div class="row  text-center">
         <div class=" px-3 pb-0 pt-3 my-4">
            <jw-pagination :pageSize=6 :items="filteredDemande" @changePage="onChangePage" :labels="customLabels">
            </jw-pagination>
        </div>
    </div>
</div>

</template>

<script>
export default ({

    props: ['user'],

    data(){
        return {
            searchTerm : '',
            allDemandes:[],
            showModal:false,
            lngLat:null,
        }
      } ,

        computed: {
    filteredDemande() {

      return this.allDemandes.filter((demande) => {
        return demande.user.first_name.toLowerCase().includes(this.searchTerm.toLowerCase()) || demande.user.last_name.toLowerCase().includes(this.searchTerm.toLowerCase());
      })
    }
  },

      filters : {

        formatDate  (value) {
            if (value) {
                return moment(String(value)).format('MM/DD/YYYY H:mm');
            }
         },

          capitalFl  (value) {
            return value.charAt(0).toUpperCase() + value.slice(1);
         },


    },

    mounted () {
      // Do something useful with the data in the template
      console.log(this.allDemandes)
    },

    methods: {

            handleClick(id){
      this.$confirm(
        {
          message: `Are you sure?`,
          button: {
            no: 'No',
            yes: 'Yes'
          },
          /**
          * Callback Function
          * @param {Boolean} confirm
          */
          callback: confirm => {
            if (confirm) {
              axios.delete('/dashboard/demandes/demande-delete/'+id).then(response => {
                this.getDemandes()
              });
            }
          }
        }
      )
    },

        getDemandes(){
         axios.get('/dashboard/demandes/new-demandes-data').then(response => {
                this.allDemandes  = response.data;
            });
        },



        },

        created() {
            this.getDemandes()
        },




})
</script>

<style scoped>
.page-link {
    position: relative;
    display: block;
    padding: 0.5rem 0.75rem;
    margin-left: -1px;
    line-height: 1.25;
    color: #ffffff;
    background-color: #000;
    border: 1px solid #fff;
}

.page-item.active .page-link {
    z-index: 3;
    color: #fff;
    background-color: #d32a2a;
    border-color: #d32a2a;
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
</style>
