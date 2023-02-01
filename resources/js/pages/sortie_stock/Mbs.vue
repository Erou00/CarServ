<template>
    <div class="formProduit">

        <fieldset class="form-group p-3 mt-2">
            <legend class="p-2">BON DE SORTIE</legend>
            <div class="container-fluid">
                        <button
          class="btn btn-default add-stock-btn"
          style="float: right"
          @click="show = true"
        >
          BL
        </button>

        <button
                class="btn btn-default add-stock-btn mb-2"
                style="float: left"
                @click="imprimer"
                >
                <div class="spinner-border" role="status" v-if="spinnerImp">
                    <span class="sr-only">Loading...</span>
                </div>
                IMPRIMER
            </button>

          <table  class="table" >
            <thead>
                <th>N°</th>
                <th>Date</th>
                <th>Designation</th>
                <th>Magasin</th>
                <th>BL</th>

            </thead>
            <tr>

            <td><input type="text" name="" class="form-control" id="" :value="bs_num" disabled></td>
            <td><input type="date" name="" class="form-control" id="" :value="dateNow" disabled ></td>
            <td><input type="text" name="" class="form-control" id="" v-model="designation"></td>
           <td>

                <select name="sous_magasin_id" id="" class="form-control"
                v-model="sous_magasin_id" @change="getNumCommande()" style="min-width: 120px;">
                    <option :value="sm.id" v-for="sm in sous_magasins" :key="sm.id">{{ sm.nom }}</option>
                </select>

           </td>
            <td>
            <table class="table" >
            <thead>
                <th>N B.L</th>
                <th>Date</th>
                <th>Destination</th>
            </thead>
                <tr v-for="(bl, index) in bls" :key="index">
                    <th scope="row">{{ bl.no_bl }}</th>
                    <td>{{ bl.date  }}</td>
                    <td>{{ bl.entite.nom }}</td>
                </tr>

            </table>
            </td>
            </tr>
            </table>
        </div>
        </fieldset>


      <Transition name="modal">
        <div v-if="show" class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container">
              <div class="modal-header">
                <h2 class="header text-center">Please select</h2>
              </div>

              <div class="modal-body">
                <input
                  type="text"
                  class="form-control"
                  id=""
                  placeholder="filtre ..."
                  v-model="filterByNoBl"

                />

                <table class="table table-striped">
                  <tr
                    v-for="s in pageOfItems"
                    :key="s.id"
                    @click="toggle(s)"
                    :class="{
                      opened:
                        opened.findIndex((object) => object.id === s.id) >
                        -1,
                    }"

                  >
                    <td class="pr-3 align-top">
                      <strong>{{s.name}}</strong>
                    </td>
                    <td class="pr-3 align-top">
                      <strong>{{s.date}}</strong>
                    </td>
                    <td class="pr-3 align-top">
                      <strong>{{s.entite.nom}}</strong>
                    </td>

                  </tr>
                </table>

                <div class="row text-center">
                  <div class="px-3 pb-0 pt-3 my-4">
                    <jw-pagination
                      :pageSize="15"
                      :items="lisOfBs"
                      @changePage="onChangePage"
                    >
                    </jw-pagination>
                  </div>
                </div>
              </div>

              <div class="modal-footer">
                <slot name="footer">
                  <button
                    class="modal-default-button btn btn-primary"
                    @click="show = false"
                  >
                    OK
                  </button>
                </slot>
              </div>
            </div>
          </div>
        </div>
      </Transition>
    </div>
</template>

<script>
export default {
props:[],
data() {
    return {
        yearNow : new Date().getFullYear(),
        bes:[],
        dateNow : '',
        bls:[],
        bl_id:[],
        show: false,
        designation:"Divers Fournitures",
        pageOfItems: [],
        opened:[],
        bs_num:'',
        sous_magasin_id:'',
        bs_id:'',
        count:0,
        creeButton:true,
        spinnerImp:false,
        filterByNoBl : "",
    }
},
computed: {
        lisOfBs(){
            return this.$store.getters.bsList.filter((s) => {
                    return s.name
                    .toLowerCase()
                    .includes(this.filterByNoBl.toLowerCase());
                });
        },

        sous_magasins(){
        return this.$store.getters.sousmagasinsList
      },

        filtredBs(){
            return this.opened.filter((s) => {
                    return s.no_bl
                    .toLowerCase()
                    .includes(this.filterByNoBl.toLowerCase());
                });
        }
    },
methods: {
    onChangePage(pageOfItems) {
        // update page of items
        this.pageOfItems = pageOfItems;
      },
      toggle(s) {

        let index = this.opened.findIndex((object) => object.id === s.id)

        if (index > -1) {
        this.opened.splice(index, 1);
        this.bl_id.splice(index, 1);
        } else {
        this.bl_id.push(s.id)
        this.opened.push({
            id: s.id,
            no_bl: s.name,
            date: s.date,
            entite: s.entite,
        });

        }

        this.bls = this.opened

        },

        async imprimer(){
           if (this.creeButton == true) {
            await axios.post("/geststock/bon-sorties/create",{
                bls_id : this.bl_id,
                designation : this.designation,
                sous_magasin_id : this.sous_magasin_id,

            }).then(res => {
                 if (res.data.error == false) {
                    this.$toastr.s("Ajouter avec succés");
                    this.bs_id = res.data.id;
                 }
            })
           }

             this.spinnerImp = true
              await axios({
                    url: "/geststock/bon-sortie/imprimer/"+this.bs_id,
                    method: "GET",
                    responseType: "blob", // important
                })
                    .then((response) => {
                    var blob = new Blob([response.data], {
                        type: "application/pdf",
                    });
                    var url = window.URL.createObjectURL(blob);
                    window.open(url);
                    })
                    .catch(function (error) {});

          this.spinnerImp = false
          location.reload();

        },




        async getNumCommande(){
            await axios.get(`/geststock/bon-sortie/no_commande/${this.sous_magasin_id}`).then((res)=> {
                this.bs_num  = res.data.no_commande
            })
      }





},
mounted() {

    this.$store.dispatch("displayAllBs");
    this.dateNow = new Date().toISOString().substr(0, 10)
    this.$store.dispatch("displayAllSousMagasins");


},
}
</script>

<style>
.form-control{
    width: 80%;
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
  height: 450px;
  overflow-x: auto;
}

.modal-default-button {
  float: right;
}

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


.opened {
  background-color: yellow;
}
tr {
  cursor: pointer;
}
td {
  padding: 6px;
}
</style>
