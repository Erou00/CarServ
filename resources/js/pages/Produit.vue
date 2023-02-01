<template>
  <section class="content">
    <vue-confirm-dialog></vue-confirm-dialog>
    <div class="row mb-0">
      <div class="col-md-3 offset-md-9 formButton" style="text-align: end">
        <button
          class="btn btn-default"
          type="submit"
          id="submit"
          @click="addProduits()"
        >
          <i class="fa fa-floppy-o me-1" aria-hidden="true"></i>
          {{ show ? "Ajouter un champs" : "Enregistrer" }}
        </button>
      </div>
    </div>

    <div class="box box-primary">
      <fieldset class="form-group p-3 mt-2">
        <legend class="p-2">
          Ajouter Multi Produits ({{ filtredProducts.length }})
        </legend>

        <div class="container-fluid" style="padding: 10px; float: right">
          <button v-if="show" class="btn btn-default ml-1" @click="verifyField()">
            <i class="fa fa-save"></i> Enregistrer les produits
          </button>
          <button class="btn btn-default" v-if="show" @click="annuler">
            <i class="fa fa-close"></i> annuler
          </button>
        </div>

        <table class="table">
          <thead class="thead-dark">
            <tr>
              <th scope="col">Code</th>
              <th scope="col">Categorie</th>
              <th scope="col">Sous Categorie</th>
              <th scope="col">Marque</th>
              <th scope="col">Designation</th>
              <th scope="col">Prix</th>
              <th scope="col">Devise</th>
              <th scope="col">UR</th>
              <th scope="col">Stock Min</th>
              <th scope="col">Acive</th>
              <th scope="col">Groupe</th>
              <th scope="col"></th>
            </tr>
          </thead>
          <tbody>


            <ProduitForm
              v-for="(p, index) in pageOfItems"
              :key="p.id"
              :show="show"
              :index="index"
              :produit="p"
              :categories="AllCategories"
              :souscategories="AllSousCategories"
              :marques="AllMarques"
              :unitereglementaires="AllUniteRegle"
              :devises="AllDevises"
              :groupes="AllGroupes"
              :id="p.id"
              @acteur="getActeur"
            />
          </tbody>
        </table>

        <div class="row text-right">
          <div class="col-lg-6"></div>


        </div>

        <div class="row text-center">
          <div class="px-3 pb-0 pt-3 my-4">
            <jw-pagination
              :pageSize="8"
              :items="filtredProducts"
              @changePage="onChangePage"
            >
            </jw-pagination>
          </div>
        </div>
      </fieldset>
    </div>
  </section>
</template>

      <script>
import ProduitForm from "../components/ProduitComponents/ProduitForm.vue";
export default {
  props: ["categories", "devises", "unitereglementaires", "marques", "groupes"],
  data() {
    return {
      add: false,
      AllCategories: this.categories,
      AllDevises: this.devises,
      AllUniteRegle: this.unitereglementaires,
      AllMarques: this.marques,
      AllGroupes: this.groupes,
      id: null,
      categorie_id: "",
      sous_categorie_id: "",
      marque_id: "",
      devise_id: null,
      unite_regl_id: null,
      prix: "",
      designation: "",
      stock: "",
      active: "",
      groupe_id: "",
      show: false,
      counter: 0,
      produits: [],
      pageOfItems: [],
      creePar: "",
      modifierPar: "",
    };
  },
  computed: {
    filtredProducts() {
      return this.produits;
      //   .filter((produit) => {
      //     return (
      //       produit.categorie_id.toString().includes(this.categorie_id) &&
      //       produit.sous_categorie_id
      //         .toString()
      //         .includes(this.sous_categorie_id) &&
      //       produit.marque_id.toString().includes(this.marque_id) &&
      //       produit.designation
      //         .toLowerCase()
      //         .includes(this.designation.toLowerCase()) &&
      //       produit.prix_unitaire.toString().includes(this.prix) &&
      //       produit.stock_min.toString().includes(this.stock) &&
      //       produit.active.toString().includes(this.active)
      //     );
      //   });
    },

    AllSousCategories() {
      return this.$store.getters.sousCategoriesList;
    },
  },
  methods: {
    onChangePage(pageOfItems) {
      // update page of items
      this.pageOfItems = pageOfItems;
    },
    interogation() {
      this.produits = this.$store.getters.productList;
    },
    addProduits() {
      if (!this.add) {
        this.$store.dispatch("videListOfProduit");
        this.add = true;
        this.show = true;
      }
      if (this.add) {
        let produit = {
          id: `0${this.counter}`,
          categorie_id: "",
          sous_categorie_id: "",
          marque_id: "",
          designation: "",
          prix_unitaire: "",
          devise_id: "",
          unite_reglementaire_id: "",
          stock_min: "",
          active: "",
          groupe_id: "",
        };
        this.$store.dispatch("addField", { produit });
        this.produits = this.$store.getters.productList;
        this.counter++;
      }
    },
    async verifyField() {
      let v = !this.$store.getters.isAllValide;
      await this.$store.dispatch("emptyIsValide");
      await this.$store.dispatch("prepareToAdd", { isValid: v });

      let allResult = [];
      for (let i = 0; i < this.$store.getters.allValide.length; i++) {
        allResult.push(this.$store.getters.allValide[i]);
      }
      if (!allResult.includes(false)) {
        this.$store.dispatch(
          "addMultiProduit",
          this.$store.getters.produitsList
        );
        this.annuler();
      }
    },
    annuler() {
      this.$store.dispatch("resetProduits");
      this.show = false;
      this.add = false;
      this.creePar = "";
      this.modifierPar = "";
    },
    getActeur(event) {
      this.creePar = "";
      this.modifierPar = "";
      if (event.user.nom && event.user.prenom) {
        this.creePar = event.user.nom + " " + event.user.prenom;
      }
      if (event.historiques_produit.length > 0) {
        this.modifierPar =
          event.historiques_produit[0].user.nom +
          " " +
          event.historiques_produit[0].user.prenom;
      }
    },

    async imprimer() {
      await axios({
        url: `/geststock/produits/imprimer`,
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
    },
  },
  created() {},
  mounted() {
    this.$store.dispatch("dispayAllSousCategories");
    //this.AllSousCategories = this.$store.getters.sousCategoriesList
  },
  updated() {},
  components: { ProduitForm },
};
</script>

      <style>
</style>
