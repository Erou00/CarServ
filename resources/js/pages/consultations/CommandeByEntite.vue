<template>
      <div class="formProduit">
      <fieldset>
        <legend>Commande par Entite</legend>

        <div class="container-fluid my-3 mb">
          <div class="row mb">
            <div class="col-md-2">
              <label for="entite"
                >entite :<span class="text-muted"></span
              ></label>
            </div>

            <div class="col-md-10">
              <v-select
                :options="entites"
                :reduce="(option) => option.id"
                label="nom"
                v-model="entite_id"
                @input="setCommandes"
              ></v-select>

            </div>
          </div>
        </div>



      </fieldset>

      <fieldset>
        <legend>Détail</legend>
        <div class="container-fluid">
          <table class="table">
            <thead>
              <tr>
                <th scope="col">N Demande</th>
                <th scope="col">Date</th>
                <th scope="col">Code</th>
                <th scope="col">Designation</th>
                <th scope="col">Unité</th>
                <th scope="col">Demandee</th>
                <th scope="col">Qté Donnée</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(c, index) in pageOfItems" :key="index">
                <th scope="row">{{c.no_commande}}</th>
                <td>{{c.date_commande}}</td>
                <td>{{c.produit_id}}</td>
                <td>{{c.designation}}</td>
                <td>{{c.code}}</td>
                <td>{{c.qte_demandee}}</td>
                <td>{{c.product_stock}}</td>
              </tr>
            </tbody>
          </table>

          <div class="row text-center">
                  <div class="px-3 pb-0 pt-3 my-4">
                    <jw-pagination
                      :pageSize="10"
                      :items="commandesList"
                      @changePage="onChangePage"
                    >
                    </jw-pagination>
                  </div>
          </div>
        </div>
      </fieldset>
      <hr />


    </div>
</template>

<script>
export default {

    data() {
        return {
            entite_id:null,
            commandes:[],
            pageOfItems: [],
        }
    },

    computed: {
        entites(){
          return this.$store.getters.entitesList;
        },

        commandesList(){
            return this.commandes;
        }
    },

    methods: {
        onChangePage(pageOfItems) {
        // update page of items
        this.pageOfItems = pageOfItems;
        },
        async setCommandes(){
            await axios.get(`/geststock/consultations/commandes-by-entite/${this.entite_id}`)
                    .then(res => {
                        console.log(res.data.demandes);
                        this.commandes = res.data.demandes
                    })
        }
    },
    mounted() {
        this.$store.dispatch("displayAllEntites");
    },
}
</script>

<style scoped>

</style>
