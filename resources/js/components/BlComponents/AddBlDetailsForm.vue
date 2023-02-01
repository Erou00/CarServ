<template>
  <div class="container-fluid">
    <button
      class="btn btn-default add-stock-btn my-3"
      style="float: right"
      @click="show = true"
    >
    <i class="fa fa-search"></i>  Articles
    </button>

    <table class="table">
      <thead>
        <tr>
          <th scope="col">Code</th>
          <th scope="col">Designation</th>
          <th scope="col">Unité</th>
          <th scope="col">Qté</th>
          <th scope="col">Qté livrée</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(o, index) in $v.blDetails.$each.$iter" :key="index">
          <th scope="row">{{ o.produit_id.$model }}</th>
          <td>{{ o.designation.$model }}</td>
          <td>{{ o.unite_reglementaire.$model }}</td>
          <td>{{ o.qte.$model }}</td>
          <td>
            <input
              type="number"
              name="qte_livree"
              id="qte_livree"
              class="form-control"
              v-model.number="o.qte_livree.$model"
            />
            <span v-if="!o.qte_livree.required" class="text-danger">
              le champs est obligatoire
            </span>
            <span v-if="!o.qte_livree.between" class="text-danger">
              Qte livrée doit être supérieure à 0
            </span>
          </td>
        </tr>
      </tbody>
    </table>

    <button
      class="btn btn-default mt btnClasse offset-md-10 my-3"
      type="submit"
      @click="enregistrer"
    >
    <i class="fa fa-save"></i>  Enregistrer
    </button>

    <Transition name="modal">
      <div v-if="show" class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-container">
            <div class="modal-header">
              <h2 class="header text-center">Veuillez sélectionner</h2>
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
                  v-for="s in pageOfItems"
                  :key="s.id"
                  @click="toggle(s)"
                  :class="{
                    opened:
                      opened.findIndex(
                        (object) => object.produit_id === s.produit_id
                      ) > -1,
                  }"
                >
                  <td class="pr-3 align-top">
                    <strong>{{ s.produit_id }}</strong>
                  </td>
                  <td class="pr-3 align-top">
                    <strong>{{ s.designation }}</strong>
                  </td>
                  <td class="pr-3 align-top">
                    <strong>{{ s.code }}</strong>
                  </td>
                  <td class="pr-3 align-top">
                    <strong>{{ s.qte }}</strong>
                  </td>
                </tr>
              </table>

              <div class="row text-center">
                <div class="px-3 pb-0 pt-3 my-4">
                  <jw-pagination
                    :pageSize="15"
                    :items="filtredStocks"
                    @changePage="onChangePage"
                    :labels="customLabels"
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
  props: ["id", "bld", "blid"],
  data() {
    return {
      show: false,
      designation: "",
      pageOfItems: [],
      opened: [],
      notifmsg: [],
      customLabels :   {
                  first: '<<',
                  last: '>>',
                  previous: '<',
                  next: '>'
          },
    };
  },
  computed: {
    filtredStocks() {
      return this.stocks.filter((object) => {
        return (
          object.produit_id !=
            this.bld.map((bld) => {
              return bld.produit_id;
            }) &&
          object.designation
            .toLowerCase()
            .includes(this.designation.toLowerCase())
        );
      });

      //this.opened.findIndex((object) => object.produit_id === s.produit_id)
    },
    magasins() {
      return this.$store.getters.magasinsList;
    },

    blDetails() {
      return this.opened;
    },
  },
  validations: {
    blDetails: {
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
        qte: {
          required,
          between: between(1, 9999999),
        },
        unite_reglementaire: {
          required,
        },
        qte_livree: {
          required,
          between: between(1, 9999999),
        },
      },
    },
  },

  methods: {
    async setStocks() {
      await this.$store.dispatch("displayAllCommandeDetails", this.id);
      this.opened = [];
      this.stocks = this.$store.getters.stocksList;
    },
    onChangePage(pageOfItems) {
      // update page of items
      this.pageOfItems = pageOfItems;
    },
    toggle(s) {
      let index = this.opened.findIndex(
        (object) => object.produit_id === s.produit_id
      );

      if (index > -1) {
        this.opened.splice(index, 1);
      } else {
        this.opened.push({
          produit_id: s.produit_id,
          designation: s.designation,
          unite_reglementaire: s.code,
          qte: s.qte,
          qte_livree: 0,
          magasin_id: null,
        });
      }
    },

    async enregistrer() {
      this.$v.$touch();
      if (!this.$v.blDetails.$each.$error && this.stocks.length > 0) {
        await axios
          .post(`/geststock/bls/add-to-bl-details/${this.blid}`, {
            blDetails: this.blDetails,
          })
          .then((response) => {
            console.log(response);
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
    this.$store.dispatch("displayAllMagasins");
    this.setStocks();
    console.log(this.bld);
  },
};
</script>

<style>
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
