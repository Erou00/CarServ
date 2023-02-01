<template>
  <fieldset class="form-group p-3 ms-0 me-0 mt-1 w-100">
    <legend class="p-2">Ajouter un produit</legend>

    <div class="form-group row my-2 lh-1">
      <label for="text1" class="col-2 col-form-label text-end my-0 lh-1"
        >Categorie*:</label
      >
      <div class="col-2">
        <div class="input-group">
          <select
            name="categorie_id"
            class="form-control"
            v-model="f_categorie_id"
          >
            <option
              v-for="categorie in categories"
              :key="categorie.id"
              :value="categorie.id"
            >
              {{ categorie.nom }}
            </option>
          </select>
        </div>
      </div>

      <label for="text1" class="col-2 col-form-label text-end my-0 lh-1"
        >Sous Categorie*:</label
      >
      <div class="col-2">
        <div class="input-group">
          <select
            class="form-control"
            name="sous_categorie_id"
            v-model="sous_categorie_id"
          >
            <option
              v-for="sc in filtredSousCategories"
              :key="sc.id"
              :value="sc.id"
            >
              {{ sc.nom }}
            </option>
          </select>
          <!-- <input id="text1" name="sous_categorie" type="text" required="required"
                                class="form-control  py-0 my-0"> -->
        </div>
      </div>

      <label for="text1" class="col-2 col-form-label text-end my-0 lh-1"
        >Marque / Famille*:</label
      >
      <div class="col-2">
        <div class="input-group">
          <select class="form-control" name="marque_id" v-model="marque_id">
            <option v-for="m in filtredMarques" :key="m.id" :value="m.id">
              {{ m.nom }}
            </option>
          </select>
          <!-- <input id="text1" name="marque/famille" type="text" required="required"
                                class="form-control  py-0 my-0"> -->
        </div>
      </div>
    </div>

    <div class="form-group row my-2 lh-1">
      <label for="text1" class="col-2 col-form-label text-end my-0 lh-1"
        >Unite Reglem*:</label
      >
      <div class="col-2">
        <div class="input-group">
          <select
            name="unite_reglementaire_id"
            class="form-control"
            v-model="unite_reglementaire_id"
          >
            <option
              v-for="ur in unitereglementaires"
              :key="ur.id"
              :value="ur.id"
            >
              {{ ur.code }}
            </option>
          </select>
          <!-- <input id="text1" name="unite_reglementaire" type="text" required="required"
                                class="form-control  py-0 my-0"> -->
        </div>
      </div>

      <label for="text1" class="col-2 col-form-label text-end my-0 lh-1"
        >Designation*:</label
      >
      <div class="col-2">
        <div class="input-group">
          <input
            type="text"
            class="form-control"
            name="designation"
            access="false"
            id="designation"
            required="required"
            aria-required="true"
            v-model="designation"
          />
          <!-- <input id="text1" name="designation" type="text" required="required"
                                class="form-control  py-0 my-0"> -->
        </div>
      </div>

      <label for="text1" class="col-2 col-form-label text-end my-0 lh-1"
        >stock min*:</label
      >
      <div class="col-2">
        <div class="input-group">
          <input
            id="text1"
            name="stock_min"
            type="number"
            step="0.01"
            required="required"
            class="form-control py-0 my-0"
          />
        </div>
      </div>
    </div>

    <div class="form-group row my-2 lh-1">
      <label for="text1" class="col-2 col-form-label text-end my-0 lh-1"
        >prix unitaire*:</label
      >

      <div class="col-3">
        <div class="input-group">
          <input
            id="text1"
            name="prix_unitaire"
            type="text"
            required="required"
            class="form-control py-0 my-0"
          />
        </div>
      </div>

      <label for="text1" class="col-2 col-form-label text-end my-0 lh-1"
        >Devise*:</label
      >
      <div class="col-3">
        <div class="input-group">
          <select name="devise_id" class="form-control" v-model="devise_id">
            <option v-for="d in devises" :key="d.id" :value="d.id">
              {{ d.code }}
            </option>
          </select>
        </div>
      </div>
    </div>

    <div class="form-group row my-2 lh-1">
      <label for="text1" class="col-2 col-form-label text-end my-0 lh-1"
        >active:</label
      >

      <div class="col-3">
        <div class="input-group">
          <!-- <input id="text1" name="ligne_budgetaire" type="text" required="required"
                                        class="form-control  py-0"> -->
          <select name="active" id="cars" class="form-control py-0 my-0">
            <option value="1">Oui</option>
            <option value="0">Non</option>
          </select>
        </div>
      </div>

      <label for="text1" class="col-2 col-form-label text-end lh-1"
        >Groupe:</label
      >
      <div class="col-3">
        <div class="input-group">
          <!-- <input id="text1" name="ligne_budgetaire" type="text" required="required"
                                        class="form-control  py-0"> -->
          <select name="groupe_id" class="form-control">
            <option value=""></option>
            <option v-for="g in groupes" :key="g.id" :value="g.id">
              {{ g.nom }}
            </option>
          </select>
        </div>
      </div>
    </div>
  </fieldset>
</template>

<script>
export default {
  props: ["categories", "marques", "devises", "unitereglementaires", "groupes"],
  data() {
    return {
      products: [],
      product_id: "",
      designation: "",
      f_categorie_id: "",
      sous_categorie_id: "",
      marque_id: "",
      devise_id: "",
      unite_reglementaire_id: "",
      creeButton: true,
      count: 0,
    };
  },
  watch: {},
  computed: {
    filtredProducts() {
      //this.$store.getters.productList
      return this.products;
    },

    filtredSousCategories() {
      return this.$store.getters.sousCategoriesList.filter((sc) => {
        return sc.categorie_id == this.f_categorie_id;
      });
    },

    filtredMarques() {
      return this.marques.filter((m) => {
        return m.sous_categorie_id == this.sous_categorie_id;
      });
    },
  },
  methods: {},
  mounted() {
    this.$store.dispatch("dispayAllSousCategories");
  },
};
</script>

<style>
</style>
