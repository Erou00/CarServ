<template>
  <div class="formProduit">

    <div class="row mb-0">
                    <div class="col-md-3 offset-md-9 formButton" style="text-align: end;">
                        <button type="submit" name="imprimer" value="imprimer" class="btn btn-default"
                            id="modifier" :disabled="blDetails.length == 0 || $v.blDetails.$each.$anyError">
                            <i class="fa fa-print"></i> Imprimer</button>
                        <button class="btn btn-default" type="submit" id="submit"
                            :disabled="blDetails.length == 0 || $v.blDetails.$each.$anyError">
                                <i class="fa fa-floppy-o me-1" aria-hidden="true"></i>Enregistrer</button>
                    </div>
     </div>
    <fieldset class="form-group p-3 my-3">
      <legend class="p-2">Détails de Commande</legend>

        <div class="form-group row ">

            <label for="" class="col-2 text-end">No commande :</label>

          <div class="col-md-2">
            <v-select
              :options="demandes"
              :reduce="(option) => option.id"
              label="no_commande"
              @input="setStocks"
              v-model="bl.demande_id"
            ></v-select>
            <input type="hidden" name="demande_id" :value="bl.demande_id">
            <input type="hidden" name="entite_id" :value="bl.entite_id"/>
            <input type="hidden" name="no_bl" :value="bl.no_bl"/>
            <input type="hidden" name="date" :value="bl.date"/>
          </div>

            <label for="no_marche" class="col-1 text-end"
              >Entite :<span class="text-muted"></span
            ></label>

          <div class="col-md-3" style="">
            <input
              type="text"
              class="form-control mb intputHeight"
              placeholder=""
              id="no_marche"
              :value="demande ? demande[0].entite.nom : ''"
              style="width: 100% !important;"
            />
          </div>


            <label for="date" class="col-2 text-end"
              >Date commande :<span class="text-muted"></span
            ></label>


          <div class="col-md-2">
            <input
              type="date"
              class="form-control mb intputHeight"
              id="date"
              :value="demande ? demande[0].date_commande : ''"
            />
          </div>
        </div>

    </fieldset>

    <fieldset class="form-group p-3 my-3">
      <legend class="p-2">Bon Sortie</legend>

        <div class="form-group row">

            <label for="no_marche" class="col-2 text-end"
              >No Bl :<span class="text-muted"></span
            ></label>

          <div class="col-4 d-flex" style="">
            <input
              type="text"
              class="form-control mb intputHeight"
              placeholder=""
              id=""
              name=""
              v-model="bl.no_bl"
              style="margin-right: 3px"
              readonly
            />

            <input type="hidden" name="magasin_id" v-model="bl.magasin_id" />
            <input type="hidden" name="sous_magasin_id" v-model="bl.sous_magasin_id" />

            <div v-for="(allErrors, idx) in blErrors.no_bl" :key="idx">
              <span class="text-danger">{{ allErrors }} </span>
            </div>
          </div>


            <label for="date" class="col-1 text-end">Date Bl :<span class="text-muted"></span></label>


          <div class="col-md-4">
            <input
              type="date"
              class="form-control mb intputHeight"
              placeholder=""
              id="date"
              name="date"
              v-model="bl.date"
             readonly
            />
          </div>
        </div>



    </fieldset>

    <fieldset class="form-group p-3 my-3">
      <legend class="p-2">Détail Livraison</legend>

      <div class="container-fluid">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">Code</th>
              <th scope="col">Designation</th>
              <th scope="col">Unité</th>
              <th scope="col">Qte Stock</th>
              <th scope="col">Qté Commandée</th>
              <th scope="col">En train</th>
              <th scope="col">Qté Accordée</th>

            </tr>
          </thead>
          <tbody>
            <tr v-for="(o, index) in $v.blDetails.$each.$iter" :key="index">
              <th scope="row"> <input type="text" :name="'bsDetails['+index+'][produit_id]'" :value="o.produit_id.$model" class="form-control"></th>
              <td>{{ o.designation.$model }}</td>
              <td>{{ o.unite_reglementaire.$model }}</td>
              <td>{{ o.qte.$model }}</td>
              <td>
                {{ o.qte_demandee.$model }}
                <input type="hidden" :name="'bsDetails['+index+'][qte_demandee]'" :value="o.qte_demandee.$model" >

              </td>
              <td>
                {{ o.en_train.$model }}
              </td>
              <td>
                <input
                  type="number"
                  :name="'bsDetails['+index+'][qte_donnee]'"
                  required
                  id="qte_donnee"
                  class="form-control"
                  :max="o.qte.$model - o.en_train.$model"
                  v-model.number="o.qte_donnee.$model"
                  :disabled="!creeButton"
                />
                <span v-if="!o.qte_donnee.required" class="text-danger">
                  le champs est obligatoire
                </span>
                <span v-if="!o.qte_donnee.mustBeCool" class="text-danger">
                  Qte donnée doit être entre 0 et
                  {{ o.qte.$model - o.en_train.$model }}
                </span>
              </td>

            </tr>
          </tbody>
        </table>
      </div>
    </fieldset>
    <hr />


    <Transition name="modal">
        <div v-if="show" class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container">
              <div class="modal-header">
              </div>

              <div class="modal-body">
            <div class="container">
                            <table class="table table-striped">
                            <thead>
                                <th>N°BL</th>
                                <th>DATE</th>
                                <th>Designation</th>
                                <th>Qte donnée</th>
                            </thead>
                            <tr
                                v-for="s in pageOfItems"
                                :key="s.id">
                                <td class="pr-3 align-top">
                                <strong>{{s.no_bl}}</strong>
                                </td>
                                <td class="pr-3 align-top">
                                <strong>{{s.date}}</strong>
                                </td>
                                <td class="pr-3 align-top">
                                <strong>{{s.designation}}</strong>
                                </td>
                                <td class="pr-3 align-top">
                                <strong>{{s.qte_donnee}}</strong>
                                </td>


                            </tr>
                            </table>
                        </div>

                            <div class="row text-center">
                            <div class="px-3 pb-0 pt-3 my-4">
                                <jw-pagination
                                :pageSize="15"
                                :items="lastTake"
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
  helpers,
} from "vuelidate/lib/validators";

const mustBeCool = (value, detail) => {
  return (
    !helpers.req(value) || (value >= 0 && value <= detail.qte - detail.en_train)
  );
};

export default {
  props: ["id"],
  data() {
    return {
      yearNow: new Date().getFullYear(),

      bl: {
        no_bl: "",
        date: null,
        demande_id: null,
        entite_id: null,
        magasin_id: null,
        sous_magasin_id: null,
        facture_id: null,
      },
      bs_id: "",
      demande: "",
      show: false,
      designation: "",
      pageOfItems: [],
      stocks: [],
      opened: [],
      notifmsg: [],
      count: 0,
      creeButton: true,
      en: 10,
      reloadPage: true,
      spinnerImp : false,
      lastTake:[],
      magasin_id:""
    };
  },
  watch: {
    "magasin_id" (val) {
        this.opened.map(o => {
              o.magasin_id = val
              return o;
          })
      }
  },

  computed: {
    demandes() {
      return this.$store.getters.demandesList;
    },
    magasins() {
      return this.$store.getters.magasinsList;
    },
    filtredStocks() {
      return this.stocks.filter((s) => {
        return s.designation
          .toLowerCase()
          .includes(this.designation.toLowerCase());
      });
    },
    blDetails() {
      return this.opened;
    },
    blErrors() {
      return this.notifmsg;
    },
  },
  validations: {
    blDetails: {
      required,
      minLength: minLength(3),
      $each: {
        produit_id: {
          required,
        },
        designation: {
          required,
        },
        qte: {
          required,
        },
        en_train: {
          required,
        },
        qte_donnee: {
          required,
          mustBeCool,
        },
        unite_reglementaire: {
          required,
        },
        qte_demandee: {
          required,
        },

      },
    },
  },
  methods: {
    async setStocks(id) {
      if (id != null) {
        await this.$store.dispatch("displayAllDemandeDetails", id);

        this.opened = [];
        this.creeButton = true;
        this.lastTake = this.$store.getters.lastDemandeTake;
        if (this.lastTake.length > 0) {
            this.show = true;
        }
        this.demande = this.$store.getters.demandeById;
        var details = this.$store.getters.demandeDetailsList;

        let dateNow = new Date().toISOString().substr(0, 10);


        this.bl = {
          no_bl :  await this.getNumBl(this.demande[0].sous_magasin_id),
          date: dateNow,
          demande_id: this.demande[0].id,
          entite_id: this.demande[0].entite_id,
          magasin_id: this.demande[0].magasin_id,
          sous_magasin_id: this.demande[0].sous_magasin_id,
        };

        for (let index = 0; index < details.length; index++) {
          const s = details[index];
          this.opened.push({
            produit_id: s.produit_id,
            designation: s.designation,
            unite_reglementaire: s.code,
            qte: s.qte,
            qte_demandee: s.qte_demandee,
            qte_donnee: s.qte_demandee,
            en_train: s.product_stock ? s.product_stock : 0,
          });
        }
      } else {
        this.opened = [];
        this.stocks = [];
        this.bl.date = "";
      }
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
          qte_demandee: s.qte_demandee,
          qte_donnee: 0,
          en_train: s.product_stock ? s.product_stock : 0,

        });
      }

    },

    async getNumBl(sm){
           let no_bl
            await axios.get(`/geststock/bs/no_commande/${sm}`).then((res)=> {
                no_bl  = res.data.no_commande;
            })

            return no_bl;
      },



  },
  mounted() {
    this.$store.dispatch("displayAllDemandes");
    this.$store.dispatch("displayAllMagasins");
    this.bs_id = this.id + 0;
    let dateNow = new Date().toISOString().substr(0, 10);
    this.bl.date = dateNow;

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
.vs__dropdown-toggle {
  border-color: #000;
}
.v-select{
    z-index: 1000;
}
</style>
