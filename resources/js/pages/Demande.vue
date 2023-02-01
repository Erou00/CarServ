<template>
  <div >
    <div class="row mb-0">
                    <div class="col-md-3 offset-md-9 formButton" style="text-align: end;">
                        <button type="submit" name="imprimer" value="imprimer" class="btn btn-default" id="modifier"
                        :disabled="demandeDetails.length == 0 || $v.demandeDetails.$each.$anyError">
                           <i class="fa fa-print"></i> Imprimer</button>
                        <button class="btn btn-default" type="submit" id="submit"
                        :disabled="demandeDetails.length == 0 || $v.demandeDetails.$each.$anyError">
                            <i class="fa fa-floppy-o me-1" aria-hidden="true"></i>Enregistrer</button>
                    </div>
                </div>

    <fieldset class="form-group p-3 mt-2">
      <legend class="p-2">Demande</legend>

      <div class="form-group row">

            <label for="no_marche" class="col-2 col-form-label  text-end"  >N° Demande </label>

          <div class="col-2" >
            <input
              type="text"
              class="form-control mb intputHeight"
              placeholder=""
              name="no_commande"
              readonly
              v-model="demande.no_commande"

            />


          </div>


            <label for="statut" class="col-2 col-form-label text-end" >Date De demande </label>


          <div class="col-2">
            <input
              type="date"
              class="form-control mb intputHeight"
              placeholder=""
              name="date_commande"
              readonly
              v-model="demande.date_commande"
            />
            <div
              v-for="(allErrors, idx) in demandeErrors.date_commande"
              :key="idx"
            >
              <span class="text-danger">{{ allErrors }} </span>
            </div>
          </div>
          <div class="col-md-1 text-center">
            <label for="statut" class="col-2 col-form-label text-end">Entite:</label>
          </div>

          <div class="col-md-3" style="float: left">
            <v-select
              :options="entites"
              :reduce="(option) => option.id"
              label="nom"
              v-model="demande.entite_id"

            ></v-select>
            <input type="hidden" name="entite_id" :value="demande.entite_id">
          </div>

          <label for="text1" class="col-2 col-form-label text-end">
                            Magasin:</label>
                        <div class="col-2">
                            <div class="input-group">
                                <select name="sous_magasin_id" id="" class="form-control"
                                v-model="demande.sous_magasin_id" @change="getNumCommande()">
                                    <option :value="sm.id" v-for="sm in sous_magasins" :key="sm.id">{{ sm.nom }}</option>
                                </select>
                            </div>
                        </div>

                        <label for="text1" class="col-2 col-form-label text-end">Facture:</label>
        <div class="col-4">
            <div class="form-group  row">
                <div class="col-sm-11">
                   <select name="facture_id" id="" class="form-control" v-model="demande.facture_id">
                        <option value=""></option>
                        <option :value="f.id" v-for="f in factures" :key="f.id">
                        {{ f.n_facture }}</option>
                    </select>
                </div>
                <div class="col-sm-1 p-0" style="margin-left: -70px;">
                    <button
                    class="btn p-0 "
                    @click.prevent="showFacture = true"
                    >
                    <i class="fa fa-plus-square fa-lg" aria-hidden="true"></i>
                </button>

                </div>
            </div>

        </div>

      </div>
    </fieldset>

    <fieldset class="form-group p-3 mt-2" >
      <legend class="p-2">Details de demande</legend>

      <div class="container-fluid">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">Code</th>
              <th scope="col">Designation</th>
              <th scope="col">Unité</th>
              <th scope="col">Qté Stock</th>
              <th scope="col">Qté Commandée</th>

            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(o, index) in $v.demandeDetails.$each.$iter"
              :key="index"
            >
              <th scope="row">
                <input type="text" class="form-control" :name="'demandeDetails['+index+'][produit_id]'" :value="o.produit_id.$model "> </th>
              <td>{{ o.designation.$model }}</td>
              <td>{{ o.unite_reglementaire.$model }}</td>
              <td>
                {{ o.stock.$model }}
              </td>
              <td>
                <input
                  type="number"
                  :name="'demandeDetails['+index+'][qte_demandee]'"
                  id="qte"
                  class="form-control"
                  required
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

        <div class="row">

            <div class="col-md-4"></div>

            <div class="col-md-4 d-flex">
                <label for="" class="me-2"><Strong>Number de line:</Strong> </label>
                <div>
                    <input type="text" class="form-control" disabled :value="demandeDetails.length">
                </div>

            </div>
            <div class="col-md-4">
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


    <Transition name="modal">
      <div v-if="showFacture" class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-container">
            <div class="modal-header">
              <h2 class="header text-center">Facture</h2>
            </div>

            <div class="container">
                <div v-for="(errorArray, index) in notifmsg" :key="index">
                    <div v-for="(allErrors, index) in errorArray" :key="index">
                         <span class="text-danger">{{ allErrors}} </span>
                    </div>
                </div>
                <div class="rendered-form">

                        <div class="formbuilder-text form-group field-no_facture">
                            <label for="n_facture" class="formbuilder-text-label">No Facture<span class="formbuilder-required">*</span></label>
                            <input type="text" class="form-control" name="n_facture" v-model="facture.n_facture" access="false" id="no_facture" required="required" aria-required="true">
                        </div>
                        <div class="formbuilder-text form-group field-n_pv">
                            <label for="n_pv" class="formbuilder-text-label">N pv<span class="formbuilder-required">*</span></label>
                            <input type="text" class="form-control" name="n_pv" v-model="facture.n_pv" access="false" id="n_pv" required="required" aria-required="true">
                        </div>
                        <div class="formbuilder-text form-group field-montant">
                            <label for="montant" class="formbuilder-text-label">Montant<span class="formbuilder-required">*</span></label>
                            <input type="text" class="form-control" name="montant" v-model="facture.montant" access="false" id="montant" required="required" aria-required="true">
                        </div>
                        <div class="formbuilder-text form-group field-date_depot">
                            <label for="date_depot" class="formbuilder-text-label">Date Depot<span class="formbuilder-required">*</span></label>
                            <input type="date" class="form-control" name="date_depot" v-model="facture.date_depot" access="false" id="date_depot" required="required" aria-required="true">
                        </div>
                        <div class="formbuilder-text form-group field-n_registre mb-2">
                            <label for="n_registre" class="formbuilder-text-label">N registre<span class="formbuilder-required">*</span></label>
                            <input type="text" class="form-control" name="n_registre" v-model="facture.n_registre" access="false" id="n_registre" required="required" aria-required="true">
                        </div>

                        </div>
            </div>


            <div class="modal-footer">
              <slot name="footer">
                <button
                  class="modal-default-button btn btn-default"
                  @click.prevent="addFacture()"
                >
                  <i class="fa fa-add"></i> Enregistrer
                </button>
                <button
                  class="modal-default-button btn btn-default"
                  @click.prevent="showFacture = false"
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
  props:['id'],
  data() {
    return {
      yearNow : new Date().getFullYear(),

      factures:[],
      facture : {
        n_facture  :null,
        n_pv  :null,
        montant  :null,
        date_depot  :null,
        n_registre  :null,
      },

      allDemandes: [],
      demande: {
        no_commande: null,
        date_commande: null,
        entite_id: null,
        sous_magasin_id:null,
        facture_id:null,
      },
      count: 0,
      d_id: 0,
      show: false,
      showFacture:false,
      designation: "",
      pageOfItems: [],
      opened: [],
      notifmsg: [],
      creeButton: true,
      reloadPage:true,
      spinnerImp:false,
    };
  },
  watch: {

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
      $each: {
        id: {
        },
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
        stock: {
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


    onChangePage(pageOfItems) {
      // update page of items
      this.pageOfItems = pageOfItems;
    },
    toggle(p) {
    //   console.log(this.opened.findIndex((object) => object.id === p.id));
    console.log(p);
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
          qte_demandee: null,
          stock :  p.stock
        });
      }
    },


    async getNumCommande(){
            await axios.get(`/geststock/demandes/no_commande/${this.demande.sous_magasin_id}`).then((res)=> {
                this.demande.no_commande  = res.data.no_commande
            })
      },


      async getFacture(){
        await axios.get(`/geststock/factures/all-factures`).then((res) => {
            this.factures = res.data.factures
        })
    },

    async addFacture(){
          await axios.post(`/geststock/factures/add`,this.facture).then((res) => {
               if (res.data.error == false) {
                  this.showFacture = false;
                  this.getFacture()
               }
          }).catch(e => {
                this.notifmsg = e.response.data
            })
    }


  },
  async mounted() {
    this.$store.dispatch("dispayAllProduitByCategories");
    this.$store.dispatch("displayAllEntites");
    this.$store.dispatch("displayAllSousMagasins");

    let dateNow = new Date().toISOString().substr(0, 10)
    this.demande.date_commande = dateNow
    this.getFacture()

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
</style>
