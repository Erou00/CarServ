<template>
    <div>
        <div class="row mb-0">
                    <div class="col-md-3 offset-md-9 formButton" style="text-align: end;">
                        <button type="submit" name="imprimer" value="imprimer"
                         class="btn btn-default" id="modifier"
                          :disabled="stocks.length == 0 || $v.stocks.$each.$anyError">
                           <i class="fa fa-print"></i> Imprimer</button>
                        <button class="btn btn-default" type="submit" id="submit" :disabled="stocks.length == 0 || $v.stocks.$each.$anyError">
                            <i class="fa fa-floppy-o me-1" aria-hidden="true"></i>Enregistrer</button>
                    </div>
        </div>
        <fieldset class="form-group p-3 mt-2">
                        <legend class="p-2">Marche</legend>
                        <div class="form-group row">
                            <label for="text1" class="col-2 col-form-label text-end">N° Marche:</label>
                            <div class="col-2">
                                <div class="input-group">
                                    <input id="text1" name="no_marche" type="text" required="required"
                                        class="form-control"
                                        v-model="marche.no_marche">
                                </div>
                            </div>

                            <label for="text1" class="col-1 col-form-label">Fournisseur:</label>
                            <div class="col-3">
                                <v-select
                                    :options="fournisseurs"
                                    :reduce="(option) => option.id"
                                    label="nom"
                                    v-model="marche.fournisseur_id"
                                ></v-select>

                                <input type="hidden" name="fournisseur_id" :value="marche.fournisseur_id">
                            </div>
                            <label for="text1" class="col-1 col-form-label text-end">TVA %:</label>
                            <div class="col-2">
                                <div class="input-group">
                                    <select name="tva" id="" class="form-control" v-model="marche.tva">
                                    <option :value="t.tva" v-for="t in tva" :key="t.id">{{ t.tva }}</option>
                                </select>
                                </div>
                            </div>
                        </div>



                        <div class="form-group row mt-2">
                            <label for="text1" class="col-2 col-form-label text-end">Ligne budgetaire:</label>
                            <div class="col-2">
                                <div class="input-group">
                                    <input id="text1" name="ligne_budgetaire" type="text" required="required"
                                        class="form-control">
                                </div>
                            </div>

                            <label for="text1" class="col-1 col-form-label text-end">ODS:</label>
                            <div class="col-2">
                                <div class="input-group">
                                    <input id="text1" name="ods" type="date" required="required"
                                        class="form-control">
                                </div>
                            </div>

                            <label for="text1" class="col-2 col-form-label text-end">Delais d'execution:</label>
                            <div class="col-2">
                                <div class="input-group">
                                    <input id="text1" name="delais_execution" type="number" required="required" placeholder=""
                                        class="form-control">
                                </div>
                            </div>

                        </div>

                        <div class="form-group row mt-2">

                            <label for="text1" class="col-2 col-form-label text-end">
                             Magasin:</label>
                            <div class="col-2">
                                <div class="input-group">
                                    <select name="sous_magasin_id" id="" class="form-control"
                                         v-model="marche.sous_magasin_id" >
                                        <option :value="sm.id" v-for="sm in sous_magasins" :key="sm.id">{{ sm.nom }}</option>
                                    </select>
                                </div>
                            </div>

                            <label for="text1" class="col-1 col-form-label text-end">Objet:</label>
                            <div class="col-3">
                                <textarea id="textarea" name="objet" cols="40" rows="2" class="form-control"></textarea>
                            </div>

                            <label for="text1" class="col-2 col-form-label text-end">Type de marche:</label>
                            <div class="col-2">
                                <div class="input-group">

                                    <div class="form-control border-0">
                                        <input type="radio" name="type" id="ch1" class="" value="marche cadre">
                                        <label for="ch1">Marche Cadre</label><br>
                                        <input type="radio" name="type" id="ch2" class="" value="marche fermé">
                                        <label for="ch2">Marche Fermé</label>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </fieldset>

                    <fieldset class="form-group p-3 my-3">
                        <legend class="p-2">Marche Details</legend>



            <div class="container-fluid">
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">Code</th>
                    <th scope="col">Designation</th>
                    <th scope="col">Qté</th>
                    <th scope="col">Unité</th>
                    <th scope="col">P.U.H.T</th>
                    <th scope="col">T.V.A</th>
                    <th scope="col">P.T.T.C</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(o, index) in $v.stocks.$each.$iter" :key="index">
                    <td scope="row"><input type="text" class="form-control"
                        :name="'marcheDetails['+index+'][produit_id]'"
                         v-model="o.produit_id.$model"
                         readonly
                        ></td>
                    <td>{{ o.designation.$model }}</td>
                    <td>
                      <input
                        type="number"

                        id="qte"
                        class="form-control"
                        v-model.number="o.qte.$model"
                        :name="'marcheDetails['+index+'][qte]'"
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
                        :name="'marcheDetails['+index+'][puht]'"
                        step="0.00001"
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
                        step="0.01"
                        :name="'marcheDetails['+index+'][tva]'"
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
                        step="0.01"
                        :name="'marcheDetails['+index+'][prix_total]'"
                        id="prix_total"
                        class="form-control"
                        :value="
                          Math.ceil((o.prix_total.$model =
                            o.puht.$model * o.qte.$model * (1 + marche.tva / 100)))
                        "
                        readonly
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


              <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-4 d-flex">

                  <label for=""><Strong>Qte Total :</Strong> </label>
                  <div class="ms-3">
                  <input
                    type="text"
                    class="form-control"
                    disabled
                    :value="stocks.reduce((acc, item) => acc + item.qte, 0)"
                  />
                </div>
                </div>
                <div class="col-md-4 d-flex">
                  <label for=""><Strong>Prix Total TTC:</Strong> </label>
                  <div class="ms-3">
                    <input
                    type="text"
                    class="form-control"
                    disabled
                    :value="Math.ceil(stocks.reduce((acc, item) => acc + item.prix_total, 0))"
                  />
                  </div>

                </div>
                <div class="col-md-2">
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
                          :labels="customLabels"
                        >
                        </jw-pagination>
                      </div>
                    </div>
                  </div>

                  <div class="modal-footer">
                    <slot name="footer">
                      <button
                        class="modal-default-button btn btn-primary"
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

        props: ["tva"],
        data() {
          return {
            yearNow: new Date().getFullYear(),
            marche: {
              no_marche: null,
              date_marche: null,
              fournisseur_id: null,
              sous_magasin_id:null,
              tva:0,
            },
            customLabels :   {
                  first: '<<',
                  last: '>>',
                  previous: '<',
                  next: '>'
          },
            c_id: "",
            show: false,
            designation: "",
            pageOfItems: [],
            opened: [],
            notifmsg: [],
            creeButton: true,
            count: 0,
            reloadPage:true,
            spinnerImp:false,
          };
        },
        watch: {
          "marche.tva" (val) {
              this.opened.map(o => {
                  o.tva = val
                  return o;
              })
          }
        },
        computed: {
          fournisseurs() {
            return this.$store.getters.fournisseursList;
          },
          filtredProducts() {
            return this.$store.getters.productListByCategories.filter((produit) => {
              return produit.designation
                .toLowerCase()
                .includes(this.designation.toLowerCase());
            });
          },
          stocks() {
            return this.opened;
          },
          marcheErrors() {
            return this.notifmsg;
          },
          sous_magasins(){
                return this.$store.getters.sousmagasinsList
            },
        },
        validations: {
          stocks: {
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

        methods:{
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
                tva: this.marche.tva,
                prix_total: 0,
              });
            }
          },
        },
    mounted() {
        this.$store.dispatch("dispayAllProduitByCategories");
          this.$store.dispatch("dispayAllFournisseurs");
          this.$store.dispatch("displayAllSousMagasins");


    },
    }
    </script>

    <style>

    </style>
