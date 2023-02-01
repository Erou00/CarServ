<template>
  <div class="container-fluid">
    <button
      class="btn btn-success add-stock-btn mb-2"
      style="float: right"
      @click="show = true"
    >
      Articles
    </button>
    <table class="table">
      <thead>
        <tr>
          <th scope="col">Code</th>
          <th scope="col">Designation</th>
          <th scope="col">Unité</th>
          <th scope="col">Qté Commandée</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(o, index) in $v.demandeDetails.$each.$iter" :key="index">
          <th scope="row">{{ o.produit_id.$model }}</th>
          <td>{{ o.designation.$model }}</td>
          <td>{{ o.unite_reglementaire.$model }}</td>
          <td>
            <input
              type="number"
              name="qte"
              id="qte"
              class="form-control"
              v-model.number="o.qte_demandee.$model"
            />
            <span v-if="!o.qte_demandee.required" class="text-danger">
              le champs est obligatoire
            </span>
            <span v-if="!o.qte_demandee.between" class="text-danger">
              La quantité doit être supérieure à 0
            </span>
          </td>
        </tr>
      </tbody>
    </table>

    <hr />

    <button
      class="btn btn-primary mt btnClasse offset-md-10"
      type="submit"
      @click="enregistrer"
    >
      Enregistrer
    </button>

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
                v-model="designation"
              />

              <table class="table table-striped">
                <tr
                  v-for="p in pageOfItems"
                  :key="p.id"
                  @click="toggle(p)"
                  :class="{
                    opened:
                      opened.findIndex((object) => object.produit_id === p.id) >
                      -1,
                  }"
                >
                  <td class="pr-3 align-top">
                    <strong>{{ p.id }}</strong>
                  </td>
                  <td class="pr-3 align-top">
                    <strong>{{ p.designation }}</strong>
                  </td>

                  <td class="pr-3 align-top">
                    <strong>{{ p.unite_reglementaire.code }}</strong>
                  </td>
                </tr>
              </table>

              <div class="row text-center">
                <div class="px-3 pb-0 pt-3 my-4">
                  <jw-pagination
                    :pageSize="15"
                    :items="filtredProducts"
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
  import { required, minLength, between, minValue } from "vuelidate/lib/validators";

export default {
  props:["id"],
  data() {
    return {
      show: false,
      designation: "",
      pageOfItems: [],
      opened: [],
      notifmsg: [],
    };
  },
  computed: {
    filtredProducts() {
        return this.$store.getters.productListByCategories.filter((produit) => {
          return produit.designation
            .toLowerCase()
            .includes(this.designation.toLowerCase());
        });
    },
    demandeDetails() {
      return this.opened;
    },
  },
  validations: {
    demandeDetails: {
      required,
      minLength: minLength(3),
      $each: {
        produit_id: {
          required,
          between: between(1, 9999999),
        },
        designation: {
          required,
        },
        unite_reglementaire: {
          required,
        },
        qte_demandee: {
          required,
          between: between(1, 9999999),
        },
      },
    },
  },
  methods: {
    onChangePage(pageOfItems) {
      // update page of items
      this.pageOfItems = pageOfItems;
    },
    toggle(p) {
      console.log(this.opened.findIndex((object) => object.id === p.id));
      const index = this.opened.findIndex((object) => {
        return object.produit_id === p.id;
      });
      if (index > -1) {
        this.opened.splice(index, 1);
      } else {
        this.opened.push({
          produit_id: p.id,
          designation: p.designation,
          unite_reglementaire: p.unite_reglementaire.code,
          qte_demandee: 0,
        });
      }
    },
    async enregistrer() {
        this.$v.$touch();
        if (!this.$v.demandeDetails.$each.$error && this.demandeDetails.length > 0) {
            await axios
            .post(`/geststock/demandes/add-to-demande-details/${this.id}`, {
                demandeDetails: this.demandeDetails,
            })
            .then((response) => {
                if (response.data.error == false) {
                location.reload();
                }
            });
        } else {
            this.$toastr.e("Verifier les champs");
        }
    },
  },
  mounted() {
    this.$store.dispatch("dispayAllProduitByCategories");


  },
};
</script>

<style>
</style>
