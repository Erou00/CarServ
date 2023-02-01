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
          <th scope="col">Qté</th>
          <th scope="col">Unité</th>
          <th scope="col">P.U.H.T</th>
          <th scope="col">T.V.A</th>
          <th scope="col">P.T</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(o, index) in $v.marcheDetails.$each.$iter" :key="index">
          <th scope="row">{{ o.produit_id.$model }}</th>
          <td>{{ o.designation.$model }}</td>
          <td>
            <input
              type="number"
              name="qte"
              id="qte"
              class="form-control"
              v-model.number="o.qte.$model"
            />
            <span v-if="!o.qte.required" class="text-danger">
              le champs est obligatoire
            </span>
            <span v-if="!o.qte.between" class="text-danger">
              La quantité doit être supérieure à 0
            </span>
          </td>
          <td>{{ o.unite_reglementaire.$model }}</td>
          <td>
            <input
              type="number"
              name="puht"
              id="puht"
              class="form-control"
              v-model.number="o.puht.$model"
            />
            <span v-if="!o.puht.required" class="text-danger">
              le champs est obligatoire
            </span>
            <span v-if="!o.puht.between" class="text-danger">
              PUHT doit être supérieure à 0
            </span>
          </td>
          <td>
            <input
              type="number"
              name="tva"
              id="tva"
              class="form-control"
              v-model.number="o.tva.$model"
              readonly
            />
            <span v-if="!o.tva.required" class="text-danger">
              le champs est obligatoire
            </span>
          </td>
          <td>
            <input
              type="number"
              name="prix_total"
              id="prix_total"
              class="form-control"
              :value="Math.ceil((o.prix_total.$model =
                        o.puht.$model * o.qte.$model * (1 + o.tva.$model / 100)))"
            />
            <span v-if="!o.prix_total.required" class="text-danger">
              le champs est obligatoire
            </span>
            <span v-if="!o.prix_total.minValue" class="text-danger">
              Le prix total doit être supérieure à 0
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
     <i class="fa fa-save"></i> Enregistrer
    </button>

    <Transition name="modal">
      <div v-if="show" class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-container">
            <div class="modal-header">
              <h2 class="header text-center">Veuillez sélectionner</h2>
            </div>

            <div class="modal-body" >
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
                <div class="px-2 pb-0 pt-1">
                  <jw-pagination
                    :pageSize="15"
                    :items="filtredProducts"
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
  props:['mid' , 'tva'],
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
    filtredProducts() {
        return this.$store.getters.productListByCategories.filter((produit) => {
          return produit.designation
            .toLowerCase()
            .includes(this.designation.toLowerCase());
        });
      },
    marcheDetails() {
      return this.opened;
    },
  },
  validations: {
    marcheDetails: {
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
        puht: {
          required,
          between: between(1, 9999999),
        },
        tva: {
          required,
        },
        prix_total: {
          required,
          minValue: minValue(1),
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
          qte: 0,
          puht: p.prix_unitaire,
          tva: this.tva,
          prix_total: 0,
        });
      }
    },
    async enregistrer(){
        this.$v.$touch();
            if (!this.$v.marcheDetails.$each.$error && this.marcheDetails.length > 0) {
                await axios.post(`/geststock/marches/add-to-stock/${this.mid}`,
                {marcheDetails:this.marcheDetails}).then((response)=>{

                    console.log(response);
                    if (response.data.error==false) {
                                location.reload();
                            };
                })
            }else{
                this.$toastr.e("Verifier les champs");
            }
    }
  },
  mounted() {
    this.$store.dispatch("dispayAllProduitByCategories");
  },
};
</script>

<style scoped>
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
