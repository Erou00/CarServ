<template>
  <div class="formProduit">
    <div class="text-end">
      <button
        class="btn btn-default mt btnClasse offset-md-10"
        type="submit"
        @click="enregistrer"
      >
        <i class="fa fa-save"></i> Enregistrer
      </button>
    </div>

    <fieldset class="form-group p-3 mt-2">
      <legend class="p-2">Demandes Periodique</legend>

      <div class="container-fluid my-3">
        <div class="row">
          <!-- <div class="col-md-4">
                <div class="col-md-4 text-end">
            <button class="btn btn-dark w-50" @click="imprimer">
              Imprimer
            </button>
          </div>
            </div> -->
        </div>
      </div>

      <div class="container-fluid my-3">
        <div class="row">

            <label for="statut" class="col-2 p-0 text-end"
              >Date De demande :<span class="text-muted"></span
            ></label>


          <div class="col-md-4">
            <input
              type="date"
              class="form-control mb intputHeight"
              placeholder=""
              id="lb"
              v-model="demande.date_commande"
              readonly
            />
            <div
              v-for="(allErrors, idx) in demandeErrors.date_commande"
              :key="idx"
            >
              <span class="text-danger">{{ allErrors }} </span>
            </div>
          </div>

            <label for="statut" class="col-2 text-end">Entite :<span class="text-muted"></span></label>


          <div class="col-md-4" style="float: left">
            <v-select
              :options="entites"
              :reduce="(option) => option.id"
              label="nom"
              v-model="entites_id"
              multiple
            ></v-select>
            <div v-for="(allErrors, idx) in demandeErrors.entite_id" :key="idx">
              <span class="text-danger">{{ allErrors }} </span>
            </div>
          </div>

          <label for="text1" class="col-2 col-form-label text-end">
                            Magasin:</label>
                        <div class="col-2">
                            <div class="input-group">
                                <select name="sous_magasin_id" id="" class="form-control"
                                v-model="demande.sous_magasin_id">
                                    <option :value="sm.id" v-for="sm in sous_magasins" :key="sm.id">{{ sm.nom }}</option>
                                </select>
                            </div>
                        </div>


        </div>
      </div>
    </fieldset>

    <fieldset class="form-group p-3 mt-2">
      <legend class="p-2">Details de demande</legend>
      <button
        class="btn btn-default add-stock-btn"
        style="float: right"
        @click="show = true"
      >
        Articles
      </button>

      <div class="container-fluid">
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
            <tr
              v-for="(o, index) in $v.demandeDetails.$each.$iter"
              :key="index"
            >
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
        <div v-for="(errorArray, idx) in notifmsg.demandeDetails" :key="idx">
          <div v-for="(allErrors, idx) in errorArray" :key="idx">
            <span class="text-danger">{{ allErrors }} </span>
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
import {
  required,
  minLength,
  between,
  minValue,
} from "vuelidate/lib/validators";

export default {
  data() {
    return {
      demandes: [],
      demande: {
        date_commande: null,
        sous_magasin_id: null,
      },
      entites_id: null,
      count: 0,
      d_id: "",
      show: false,
      designation: "",
      pageOfItems: [],
      opened: [],
      notifmsg: [],
      creeButton: false,
    };
  },
  computed: {
    entites() {
      return this.$store.getters.entitesList;
    },
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
    demandeErrors() {
      return this.notifmsg;
    },
    sous_magasins(){
        return this.$store.getters.sousmagasinsList
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
        qte_demandee: {
          required,
          between: between(1, 9999999),
        },
        unite_reglementaire: {
          required,
        },
      },
    },
  },
  methods: {

    annuler() {},
    imprimer() {},
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
      if (this.demandeDetails.length == 0) {
        this.$toasted.show("Aucun Article dans ce demande", {
          action: [
            {
              text: "Continuer",
              onClick: async (e, toastObject) => {
                await axios
                  .post("/geststock/demandes/demande-periodique", {
                    demande: this.demande,
                    entites: this.entites_id,
                  })
                  .catch((e) => {
                    if (e.response.data.error) {
                      this.notifmsg = e.response.data.messages;
                    } else {
                      this.$toastr.s("SUCCESS MESSAGE", "Success Toast Title");
                      setTimeout((location.href = "/geststock/demandes"), 4000);
                      this.notifmsg = "";
                    }
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
        if (!this.$v.demandeDetails.$each.$error) {
          await axios
            .post("/geststock/demandes/demande-periodique", {
              entites: this.entites_id,
              demande: this.demande,
              demandeDetails: this.demandeDetails,
            })
            .then((response) => {
              this.$toastr.s("SUCCESS MESSAGE", "Success Toast Title");
              this.notifmsg = "";
             setTimeout((location.reload()), 10000);
            })
            .catch((e) => {
              this.notifmsg = e.response.data.demande;
            });
        }
      }
    },
  },
  async mounted() {
    this.$store.dispatch("dispayAllProduitByCategories");
    this.$store.dispatch("displayAllEntites");
    this.$store.dispatch("displayAllSousMagasins");


    let dateNow = new Date().toISOString().substr(0, 10);
    this.demande.date_commande = dateNow;
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
