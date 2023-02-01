<template>
  <div class="formProduit">
    <div class="row mb-0">
      <div class="col-md-3 offset-md-9 formButton" style="text-align: end">
        <button
          type="submit"
          name="imprimer"
          value="imprimer"
          class="btn btn-default"
          id="modifier"
          :disabled="
            inventaireDetails.length == 0 ||
            $v.inventaireDetails.$each.$anyError
          "
        >
          <i class="fa fa-print"></i> Imprimer
        </button>
        <button
          class="btn btn-default"
          type="submit"
          id="submit"
          :disabled="
            inventaireDetails.length == 0 ||
            $v.inventaireDetails.$each.$anyError
          "
        >
          <i class="fa fa-floppy-o me-1" aria-hidden="true"></i>Enregistrer
        </button>
      </div>
    </div>
    <fieldset class="form-group p-3 mt-2">
                    <legend class="p-2">Inventaire</legend>

                    <div class="form-group row">
                        <label for="text1" class="col-2 col-form-label text-end">N° Inventaire:</label>
                        <div class="col-2">
                            <div class="input-group">
                                <input id="text1" name="no_inventaire" type="text" required="required"
                                    class="form-control" :value="numinventaire" readonly>
                            </div>
                        </div>

                        <div class="col-3 text-center">
                            <div class="input-group">
                                <input id="text1" name="etat" type="text" required="required"
                                    class="form-control text-center" placeholder=""
                                     value="preparation" readonly>
                            </div>
                        </div>
                        <label for="text1" class="col-2 col-form-label text-end">Date Préparation:</label>
                        <div class="col-3">
                            <input id="text1" name="date_preparation" type="date" required="required"
                                    class="form-control" :value="nowDate" readonly>

                        </div>
                    </div>




                </fieldset>

    <fieldset class="form-group p-3 mt-2">
      <legend class="p-2">Details d inventaire</legend>


      <div class="container-fluid">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">Code</th>
              <th scope="col">Designation</th>
              <th scope="col">Unité</th>

            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(o, index) in $v.inventaireDetails.$each.$iter"
              :key="index"
            >
              <th scope="row">{{ o.produit_id.$model }}
                <input type="hidden" :name="'inventaireDetails['+index+'][produit_id]'" :value="o.produit_id.$model"></th>
              <td>{{ o.designation.$model }}</td>
              <td>{{ o.unite_reglementaire.$model }}</td>
            </tr>
          </tbody>
        </table>

        <div class="row">
            <div class="col-3 offset-9">
                <button
                    class="btn btn-default add-stock-btn"
                    style="float: right"
                    @click.prevent="show = true"
                >
                    Articles
                </button>
            </div>
        </div>
      </div>
    </fieldset>

    <hr />



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
                  class="modal-default-button btn btn-default"
                  @click.prevent="show = false"
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
import {
  required,
  minLength,
  between,
  minValue,
} from "vuelidate/lib/validators";

export default {
  props:['numinventaire'],
  data() {
    return {
        nowDate : new Date().toISOString().substr(0, 10),
      inventaire: {
        date_inventaire: null,
        valide: null,
        date_preparation: null,
        statut: null,
      },
      show: false,
      designation: "",
      pageOfItems: [],
      opened: [],
      notifmsg: [],
    };
  },
  computed: {
    magasins() {
      return this.$store.getters.magasinsList;
    },
    filtredProducts() {
      return this.$store.getters.productListByCategories.filter((produit) => {
        return produit.designation
          .toLowerCase()
          .includes(this.designation.toLowerCase());
      });
    },
    inventaireDetails() {
      return this.opened;
    },
    inventaireErrors() {
      return this.notifmsg;
    },
  },
  validations: {
    inventaireDetails: {
      required,
      minLength: minLength(3),
      $each: {
        produit_id: {
          required,
          between: between(1, 9999999),
        },
        designation: {

        },
        qte: {

        },
        unite_reglementaire: {

        },
        qte_stock: {

        },
        date_premption: {

        },
        magasin_id: {

        },
        lot: {

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
          lot: "",
          qte: 0,
          qte_stock: 0,
          magasin_id: null,
          date_premption: 0,
        });
      }
    },
    async enregistrer() {
      if (this.inventaireDetails.length == 0) {
        this.$toasted.show("Aucun Article dans ce inventaire", {
          action: [
            {
              text: "Continuer",
              onClick: async (e, toastObject) => {
                await axios
                  .post("/geststock/inventaires/inventaire-details", {
                    inventaire: this.inventaire,
                  })
                  .then((res) => {
                    this.$toastr.s("SUCCESS MESSAGE", "Success Toast Title");
                    setTimeout(
                      (location.href = "/geststock/inventaires"),
                      4000
                    );
                    this.notifmsg = "";
                  })
                  .catch((e) => {
                    this.notifmsg = e.response.data.messages;
                  });
                toastObject.goAway(0);
              },
            },
            {
              text: "Annuler",
              onClick: (e, toastObject) => {
                toastObject.goAway(0);
              },
            },
          ],
        });
      } else {
        this.$v.$touch();
        if (!this.$v.inventaireDetails.$each.$error) {
          await axios
            .post("/geststock/inventaires/inventaire-details", {
              inventaire: this.inventaire,
              inventaireDetails: this.inventaireDetails,
            })
            .then((response) => {
              if (response.data.error == false) {
                this.$toastr.s("SUCCESS MESSAGE", "Success Toast Title");
                this.notifmsg = "";
                setTimeout((location.href = "/geststock/inventaires"), 4000);
              }
            })
            .catch((e) => {
              this.notifmsg = e.response.data.inventaire;
            });
        }
      }
    },
  },
  mounted() {
    this.$store.dispatch("dispayAllProduitByCategories");
    this.$store.dispatch("displayAllMagasins");
  },
};
</script>

  <style scoped>
:root {
  /* Font */
  --vs-font-size: 2rem;
  --vs-line-height: 1.4;
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
  /* height: 650px; */
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
