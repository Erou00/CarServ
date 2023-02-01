<template>
    <div class="formProduit">
      <fieldset class="form-group p-3 mt-2">
        <legend class="p-2">Entrer Stock Global</legend>

        <div class="container-fluid mb-3">
          <div class="row">
            <div class="col-md-3">
              <label for=""><strong>Categorie</strong></label>
              <select class="form-control" v-model="categorie_id" @change="filterStock(false)">
                <option value=""></option>
                <option
                  v-for="categorie in AllCategories"
                  :key="categorie.id"
                  :value="categorie.id"
                >
                  {{ categorie.nom }}
                </option>
              </select>
            </div>
            <div class="col-md-3">
              <label for=""><strong>Sous Categorie</strong></label>
              <select class="form-control" v-model="sous_categorie_id" @change="filterStock(false)">
                <option value=""></option>
                <option
                  v-for="sc in AllSousCategories"
                  :key="sc.id"
                  :value="sc.id"
                >
                  {{ sc.nom }}
                </option>
              </select>
            </div>
            <div class="col-md-3">
              <label for=""><strong>Marque/Famille</strong></label>
              <select class="form-control" v-model="marque_id" @change="filterStock(false)">
                <option value=""></option>
                <option v-for="m in marques" :key="m.id" :value="m.id">
                  {{ m.nom }}
                </option>
              </select>
            </div>
            <div class="col-md-3">
              <label for=""><strong>Designation</strong></label>
              <v-select
                              :options="filtredProducts"
                              :reduce="(option) => option.id"
                              label="designation"
                              v-model="product_id"
                              @input="filterStock(false)"

                           ></v-select>
            </div>


            <div class="col-md-6">
              <label for=""><strong>Entre Date :</strong></label>
                  <div class="d-flex">
                      <input type="date" class="form-control " id="" placeholder="" v-model="startDate" @change="filterStock(false)">
                      <input type="date" class="form-control mx-1" id="" placeholder="" v-model="endDate" @change="filterStock(false)">
                  </div>
              </div>
              <div class="col-md-6 mt-2">

                  <button @click="ExportExcel('xlsx')" class="btn btn-secondary">Excel</button>
                  <button @click="ExportPdf('xlsx')" class="btn btn-secondary">Pdf</button>

            </div>
          </div>
        </div>
      </fieldset>
      <fieldset class="form-group p-3 mt-2">
        <legend class="p-2">Stock</legend >
            <div class="container-fluid" id="stock-table">
          <table class="table"  ref="exportable_table">
            <thead>
              <tr>
                <th scope="col">Categorie</th>
                <th scope="col">Sous Categorie</th>
                <th scope="col">Marque/Famille</th>
                <th scope="col">Code</th>
                <th scope="col">Designation</th>
                <th scope="col">Unité</th>

                <th scope="col">Qte livree</th>


              </tr>
            </thead>
            <tbody>
              <tr v-for="(s, index) in stocksList" :key="index">
                <td>{{ s.categorie.nom }}</td>
                <td>{{ s.souscategorie.nom }}</td>
                <td>{{ s.marque.nom }}</td>
                <th scope="row">{{ s.id }}</th>
                <td>{{ s.designation }}</td>
                <td>{{ s.unite_reglementaire.code }}</td>

                <td>{{ s.qte_livree }}</td>




                <!-- <td>{{ (s.stock) ? s.stock.magasin.nom : ''}}</td> -->
              </tr>
            </tbody>
          </table>


        </div>
      </fieldset>
      <hr />
    </div>
  </template>

  <script>

  import html2pdf from "html2pdf.js";

  export default {
    props: [ "categories", "magasins", "unitereglementaires", "pmarques"],

    data() {
      return {
        pageOfItems: [],
        AllCategories: this.categories,
        AllDevises: this.devises,
        AllUniteRegle: this.unitereglementaires,
        AllMarques: this.marques,
        categorie_id: "",
        sous_categorie_id: "",
        marque_id: "",
        unite_regl_id: "",
        designation: "",
        qte_minimum: "",
        stock: "",
        startDate: '',
        endDate: '',
        stocks:[],
        products:[],
        product_id:'',

      };
    },
    watch :{

    },

    filters : {

  formatDate  (value) {
      if (value) {
          return moment(String(value)).format('YYYY/MM/DD');
      }
   },
  },

    computed: {
      filtredProducts() {
          //this.$store.getters.productList
        return this.$store.getters.productList
      },
      AllSousCategories() {
        return this.$store.getters.sousCategoriesList.filter((sc) => {
          return sc.categorie_id.toString().includes(this.categorie_id);
        });
      },
      marques() {
        return this.pmarques.filter((m) => {
          return m.sous_categorie_id.toString().includes(this.sous_categorie_id);
        });
      },
      fournisseurs() {
        return this.$store.getters.fournisseursList;
      },
      stocksList() {
            return this.stocks

      },
    },

    methods: {
      onChangePage(pageOfItems) {
        // update page of items
        this.pageOfItems = pageOfItems;
      },

      async filterStock(print){

          try {
              await axios.post(`/geststock/consultations/entrer-stock-global-filter/${print}`,{
                  produit_id:this.product_id,
                  categorie_id: this.categorie_id,
                  sous_categorie_id: this.sous_categorie_id,
                  marque_id: this.marque_id,
                  unite_regl_id: this.unite_regl_id,
                  startDate : this.startDate,
                  endDate : this.endDate,
              },{
                  Accept: 'application/pdf',
              }).then(res => {
                      if (res.data.stocks) {
                          this.stocks = res.data.stocks;
                      }else{
                          var blob = new Blob([res.data.imp], {
                              type: "application/pdf",
                          });
                          var url = window.URL.createObjectURL(blob);
                          window.open(url);

                      }

              })
          } catch (error) {

          }


      },

      ExportExcel(type, fn, dl) {
        var elt = this.$refs.exportable_table;
        var wb = XLSX.utils.table_to_book(elt, {sheet:"Sheet JS"});
        return dl ?
          XLSX.write(wb, {bookType:type, bookSST:true, type: 'base64'}) :
        XLSX.writeFile(wb, fn || (('document' + '.'|| 'SheetJSTableExport.') + (type || 'xlsx')));
      },

      async ExportPdf() {
          await this.filterStock(true);
      },


    },
    mounted() {
      this.$store.dispatch("dispayAllProduit");
      this.$store.dispatch("dispayAllSousCategories");
      this.$store.dispatch("dispayAllFournisseurs");

    },
  };
  </script>

  <style>
  </style>
