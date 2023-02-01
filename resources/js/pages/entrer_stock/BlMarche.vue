<template>
    <div>
        <div class="row mb-0">
                    <div class="col-md-3 offset-md-9 formButton" style="text-align: end;">
                        <button type="submit" name="imprimer" value="imprimer"
                         class="btn btn-default" id="modifier"
                          :disabled="blDetails.length == 0 || $v.blDetails.$each.$anyError">
                           <i class="fa fa-print"></i> Imprimer</button>
                        <button class="btn btn-default" type="submit" id="submit"
                         :disabled="blDetails.length == 0 || $v.blDetails.$each.$anyError">
                            <i class="fa fa-floppy-o me-1" aria-hidden="true"></i>Enregistrer</button>
                    </div>
        </div>
      <fieldset class="form-group p-3 mt-2">
        <legend class="p-2">Détails de Marche</legend>
        <div class="form-group row">
          <label for="text1" class="col-2 col-form-label text-end"
            >N° marche:</label
          >
          <div class="col-2">
            <v-select
              :options="marches"
              :reduce="(option) => option.id"
              label="no_marche"
              @input="setMarches"
              v-model="bl.marche_id"
            ></v-select>
            <input type="hidden" name="marche_id" :value="bl.marche_id">
          </div>

          <label for="text1" class="col-1 col-form-label text-end">ODS:</label>
          <div class="col-2">
            <div class="input-group">
              <input
                type="date"
                class="form-control mb intputHeight"
                id="date"
                :value="marche ? marche[0].ods : ''"
              />
            </div>
          </div>

          <label for="text1" class="col-1 col-form-label">Fournisseur:</label>
          <div class="col-3">
            <input
              type="text"
              class="form-control mb intputHeight"
              placeholder=""
              id="no_marche"
              :value="marche ? marche[0].fournisseur.nom : ''"
            />
          </div>
        </div>
      </fieldset>

      <fieldset class="form-group p-3 mt-2">
        <legend class="p-2">B.E</legend>
        <div class="form-group row">
          <label for="text1" class="col-2 col-form-label text-end"
            >N° BL:</label
          >
          <div class="col-2">
            <input type="hidden" name="fournisseur_id" v-model="bl.fournisseur_id"/>
            <input type="hidden" name="magasin_id" v-model="bl.magasin_id" />
          <input type="hidden" name="sous_magasin_id" v-model="bl.sous_magasin_id" />
            <input
                type="text"
                name="no_bl"
                class="form-control mb intputHeight"
                placeholder=""
                id="no_marche"
                v-model="bl.no_bl"
                width="100"
                required
              />
          </div>

          <label for="text1" class="col-1 col-form-label text-end">Date BL:</label>
          <div class="col-2">
            <div class="input-group">
                <input
                type="date"
                name="date"
                class="form-control mb intputHeight"
                placeholder=""
                id="date"
                :min="marche ?  marche[0].ods : ''"

                v-model="bl.date"
                @change="calcule"
                required
              />
            </div>
          </div>


          <label for="text1" class="col-1 col-form-label text-end">Retard:</label>
          <div class="col-3">
            <input type="text" name="retard" :value="bl.retard" hidden/>
            <input

                type="text"
                name="retard"
                class="form-control mb intputHeight"
                placeholder=""
                id="delais"
                :value="retardMsg"
                disabled
              />
          </div>
        </div>

        <div class="form-group row">


      </div>
      </fieldset>

      <fieldset class="form-group p-3 mt-2">
        <legend class="p-2">Détail de BC</legend>



        <div class="container-fluid">
          <table class="table">
            <thead>
              <tr>
                <th scope="col">Code</th>
                <th scope="col">Designation</th>
                <th scope="col">Unité</th>
                <th scope="col">Qté demandée</th>
                <th scope="col">Qté livrée</th>
                <th scope="col">Qté à livré</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(o, index) in $v.blDetails.$each.$iter" :key="index">
                <th scope="row">  <input type="text" readonly :name="'blDetails['+index+'][produit_id]'" class="form-control" :value="o.produit_id.$model"></th>
                <td>{{ o.designation.$model }}</td>
                <td>{{ o.unite_reglementaire.$model }}</td>
                <td><input type="text" readonly class="form-control" :name="'blDetails['+index+'][qte]'"  :value="o.qte.$model "></td>
                <td>{{ o.livree.$model }}</td>
                <td>
                  <input
                    type="number"
                    :name="'blDetails['+index+'][qte_livree]'"
                    required
                    id="qte_livree"
                    class="form-control"
                    v-model.number="o.qte_livree.$model"
                    :disabled="!creeButton"
                  />
                  <span v-if="!o.qte_livree.required" class="text-danger">
                    le champs est obligatoire
                  </span>
                  <span v-if="!o.qte_livree.mustBeCool" class="text-danger">
                    Qte donnée doit être entre 0 et
                    {{ o.qte.$model - o.livree.$model }}
                  </span>
                </td>

              </tr>
            </tbody>
          </table>

          <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-2"></div>

            <div class="col-md-4 d-flex">
              <label for=""><Strong>Number de line :</Strong> </label>
              <div class="ms-3">
                <input
                  type="text"
                  class="form-control"
                  disabled
                  :value="blDetails.length"
                />
              </div>
            </div>
            <div class="col-md-3">
                  <button
                      class="btn btn-default add-stock-btn "
                      style="float: right"
                      @click.prevent="show = true"
                      >
                      <i class="fa fa-search"></i> Articles
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
    !helpers.req(value) || (value > 0 && value <= detail.qte - detail.livree)
  );
};
  export default {
    props:['id'],
    data() {
      return {
        bl: {
          no_bl: null,
          date: null,
          facture_id: null,
          retard: null,
          fournisseur_id:null,
          marche_id:null,
          magasin_id: null,
          sous_magasin_id: null,

        },
        bl_id:'',
        retardMsg : '',
        show: false,
        showFacture:false,
        designation: "",
        pageOfItems: [],
        stocks:[],
        opened: [],
        notifmsg: [],
        marche:'',
        count:0,
        creeButton:true,
        spinnerImp:false,
        reloadPage:true,
        magasin_id:"",
        customLabels :   {
                  first: '<<',
                  last: '>>',
                  previous: '<',
                  next: '>'
          },
      };

    },
    watch:{
        "magasin_id" (val) {
        this.opened.map(o => {
              o.magasin_id = val
              return o;
          })
      }
    },

    computed: {
      marches(){
          return Object.values(this.$store.getters.marchesList);
      },
      magasins(){
          return this.$store.getters.magasinsList;
      },
      filtredStocks() {
        return this.stocks.filter((s) => {
        return s.designation
          .toLowerCase()
          .includes(this.designation.toLowerCase());
        });
      },
      blDetails(){
          return this.opened
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
          mustBeCool,
        },
        livree: {
          required,
        },

      },
    },
  },
    methods: {
      async setMarches(id){
        if (id != null) {
        await this.$store.dispatch("displayAllMarcheDetails",id)
        this.opened=[]
        this.stocks =this.$store.getters.marcheDetailsList;
        this.marche =this.$store.getters.marcheById;

        this.bl.fournisseur_id = this.marche[0].fournisseur_id
        this.bl.marche_id = this.marche[0].id
        this.bl.magasin_id = this.marche[0].magasin_id;
        this.bl.sous_magasin_id = this.marche[0].sous_magasin_id;


        console.log(this.marche);
        } else {
        this.opened = [];
        this.stocks = [];
        this.bl.no_bl = "";
        this.bl.date = "";
        this.retardMsg = ''

      }
      },


      onChangePage(pageOfItems) {
        // update page of items
        this.pageOfItems = pageOfItems;
      },
      calcule(){
        let date_1 = new Date(this.marche[0].ods);
        console.log(date_1);
        let date_2 = new Date(this.bl.date);

        const days = (date_1, date_2) =>{
            let difference =   date_2.getTime() - date_1.getTime() ;
            let TotalDays = Math.ceil(difference / (1000 * 3600 * 24));
            return TotalDays;
        }

        this.bl.retard =  this.marche[0].delais_execution - days(date_1, date_2)
        if (this.bl.retard >= 0 ) {
            this.retardMsg = 'Il reste en cours ' + this.bl.retard +' jour'
        } else {
            this.retardMsg = 'Le delai a été depassé par '+Math.abs(this.bl.retard) +' jour'
        }

    },
      toggle(s) {

        let index = this.opened.findIndex((object) => object.produit_id === s.produit_id)

        if (index > -1) {
          this.opened.splice(index, 1);
        } else {
            this.opened.push({
          produit_id: s.produit_id,
          designation: s.designation,
          unite_reglementaire: s.code,
          qte: s.qte,
          livree: s.qte_livree ? s.qte_livree : 0,
          qte_livree: null,
        });

        }

      },
      async enregistrer() {
        this.$v.$touch();
        if (!this.$v.blDetails.$each.$error) {
            await axios
            .post("/geststock/bls/bl-details", {
            bl: this.bl,
            blDetails: this.blDetails,
            }).then(response => {
                if (response.data.error == false) {
                    this.$toastr.s("Ajouter avec succés");
                    this.notifmsg=''
                    if (this.reloadPage) {
                        setTimeout(location.reload(),8000);
                    }
                }
            })
            .catch((e) => {
            this.notifmsg = e.response.data.messages;
            });
        }

      },


    },
    mounted() {
      this.$store.dispatch("displayAllMarches");
      this.$store.dispatch("displayAllMagasins");
      this.bl_id = this.id + 1;


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
