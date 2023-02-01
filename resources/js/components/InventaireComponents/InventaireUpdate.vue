<template>
  <div class="formProduit">


    <fieldset class="form-group p-3 mt-2">
      <legend class="p-2">Details d inventaire</legend>

      <div class="container-fluid">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">Code</th>
              <th scope="col">Designation</th>
              <th scope="col">Qté Inventorie</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(o, index) in inventaireDetails" :key="index">
              <th scope="row">
                {{ o.produit_id }}
                <input
                  type="hidden"
                  :name="'inventaireDetails[' + index + '][produit_id]'"
                  :value="o.produit_id"
                />
              </th>
              <td>{{ o.produit.designation }}</td>

              <td>
                <input
                  type="number"
                  :name="'inventaireDetails[' + index + '][qte_inventorie]'"
                  class="form-control"
                  v-model="o.qte_inventorie"

                />
              </td>


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
  </div>
</template>

    <script>
import axios from "axios";

export default {
  props: ["id"],
  data() {
    return {
      nowDate: new Date().toISOString().substr(0, 10),
      inventaire: {
        no_inventaire: null,
        date_preparation: null,
        date_verification: null,
        date_validation: null,
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
    inventaireDetails() {
      return this.opened;
    },
    inventaireErrors() {
      return this.notifmsg;
    },
  },
  validations: {},
  methods: {
    async getInventaire() {
      await axios
        .get(`/geststock/inventaires/inventaire-by-id/${this.id}`)
        .then((res) => {
          this.inventaire = res.data.inventaire;
          this.opened = res.data.details;
        });
    },
  },
  mounted() {
    this.getInventaire();
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
