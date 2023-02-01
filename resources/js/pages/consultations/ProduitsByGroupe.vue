<template>
    <div class="formProduit">
    <fieldset>
      <legend>Produits par groupe</legend>

      <div class="container-fluid my-3 mb">
        <div class="row mb">
          <div class="col-md-2">
            <label for="groupe"
              >groupe :<span class="text-muted"></span
            ></label>
          </div>

          <div class="col-md-10">
            <v-select
              :options="groupes"
              :reduce="(option) => option.id"
              label="nom"
              v-model="groupe_id"
              @input="setproduits"
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
              <th scope="col">Categorie</th>
              <th scope="col">Sous Categorie</th>
              <th scope="col">Marque/Famille</th>
              <th scope="col">Code</th>
              <th scope="col">Designation</th>
              <th scope="col">Unité</th>
              <th scope="col">Qte Minimum</th>
              <th scope="col">Qte Stock</th>
              <th scope="col">Magasin</th>

            </tr>
          </thead>
          <tbody>
            <tr v-for="(s, index) in pageOfItems" :key="index">
              <td>{{s.categorie.nom}}</td>
              <td>{{s.souscategorie.nom}}</td>
              <td>{{s.marque.nom}}</td>
              <th scope="row">{{s.id}}</th>
              <td>{{s.designation}}</td>
              <td>{{s.unite_reglementaire.code}}</td>
              <td>{{s.stock_min}}</td>
              <td>{{ (s.stock) ? s.stock.qte : 0}}</td>
              <td>{{ (s.stock) ? s.stock.magasin.nom : ''}}</td>

            </tr>
          </tbody>
        </table>

        <div class="row text-center">
                  <div class="px-3 pb-0 pt-3 my-4">
                    <jw-pagination
                      :pageSize="10"
                      :items="produitsList"
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

  props:['groupes'],
  data() {
      return {
          groupe_id:null,
          produits:[],
          pageOfItems: [],
      }
  },

  computed: {

      produitsList(){
          return this.produits;
      }
  },

  methods: {
      onChangePage(pageOfItems) {
      // update page of items
      this.pageOfItems = pageOfItems;
      },
      async setproduits(){
          await axios.get(`/geststock/consultations/produits-by-groupe/${this.groupe_id}`)
                  .then(res => {
                      this.produits = res.data.produits
                  })
      }
  },
  mounted() {

  },
}
</script>

<style>

</style>
