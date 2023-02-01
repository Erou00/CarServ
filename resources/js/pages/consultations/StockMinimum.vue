<template>
    <div class="formProduit">
        <fieldset class="form-group p-3 mt-2">
            <legend class="p-2">Stock Minimum</legend>
        <div class="container-fluid mb-3">
        <div class="row">
          <div class="col-md-3">
            <label for=""><strong>Categorie</strong></label>
            <select class="form-control" v-model="categorie_id">
              <option value=""></option>
              <option
                v-for="categorie in categories"
                :key="categorie.id"
                :value="categorie.id"
              >
                {{ categorie.nom }}
              </option>
            </select>
          </div>
          <div class="col-md-3">
            <label for=""><strong>Sous Categorie</strong></label>
            <select class="form-control" v-model="sous_categorie_id">
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
            <select class="form-control" v-model="marque_id" >
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


                         ></v-select>
          </div>


            <div class="col-md-6">


                <button @click="ExportExcel('xlsx')" class="btn btn-secondary">Excel</button>
                <button @click="ExportPdf()" class="btn btn-secondary">Pdf</button>

          </div>
        </div>
      </div>
    </fieldset>
    <fieldset class="form-group p-3 mt-2">
      <legend class="p-2">Stock</legend>

      <div class="container-fluid">
        <table class="table" id="stock-table" ref="exportable_table">
          <thead>
            <tr>
              <th scope="col">Categorire</th>
              <th scope="col">Sous Categorie</th>
              <th scope="col">Marque</th>
              <th scope="col">Code</th>
              <th scope="col">Designation</th>
              <th scope="col">Unité</th>
              <th scope="col">Qte Minimum</th>
              <th scope="col">Qte Stock</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(s, index) in pageOfItems" :key="index">
              <td>{{s.categorie.nom}}</td>
              <td>{{s.souscategorie.nom}}</td>
              <td>{{s.marque.nom}}</td>
              <th scope="row">{{s.id}}</th>
              <td>{{s.designation}}</td>
              <td>{{s.unite_reglementaire.code}}</td>
              <td>{{s.stock_min}}</td>
              <td>{{ s.qte }}</td>
            </tr>
          </tbody>
        </table>

        <div class="row text-center">
                  <div class="px-3 pb-0 pt-3 my-4">
                    <jw-pagination
                      :pageSize="10"
                      :items="stocksList"
                      @changePage="onChangePage"
                    >
                    </jw-pagination>
                  </div>
                </div>
      </div>
    </fieldset>
    <hr />


  </div>
</template>

<script>

import html2pdf from 'html2pdf.js'

export default {

  props:['stocks','categories','pmarques']  ,
  data() {
      return {
        pageOfItems: [],
        categorie_id:'',
        sous_categorie_id:'',
        marque_id:'',
        product_id:'',

      }
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
    stocksList(){
      return this.stocks.filter(s => {
                  return  s.stock_min > s.qte && ( s.id === this.product_id || s.categorie_id === this.categorie_id
                    || s.sous_categorie_id === this.sous_categorie_id  || s.marque_id === this.marque_id)
        });
    }
  },

  methods: {
    onChangePage(pageOfItems) {
        // update page of items
        this.pageOfItems = pageOfItems;
      },

    async ExportPdf(){

        let d = new Date().toLocaleDateString("fr")
        const para = document.createElement("p");
        para.innerHTML = ` <div>
                            <div style='display:flex;justify-content: space-between;'>
                            <img src="http://127.0.0.1:8000/assets/images/rym.png"
                               style='margin-bottom:5px; height:150px'/>
                            <img src="http://127.0.0.1:8000/assets/images/tgr.png"
                            style='margin-bottom:5px; height:150px'/>
                            </div>
                                <div style='display:flex;justify-content: space-between;'>
                                            <div></div>
                                            <h6>Rabat le ,<strong>${d}</strong></h6>
                            </div>
                            </div>
                                        `;
            const t = document.getElementById('stock-table');
            para.appendChild(t);
            await html2pdf(para, {
                        margin: 10,
                        filename: 'document.pdf',
                        image: { type: 'jpeg', quality: 1 },
                        html2canvas: { dpi: 192, letterRendering: true,scale: 4 },
                        jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' }
            })

            location.reload();
            },

    ExportExcel(type, fn, dl) {
        var elt = this.$refs.exportable_table;
        var wb = XLSX.utils.table_to_book(elt, {sheet:"Sheet JS"});
        return dl ?
          XLSX.write(wb, {bookType:type, bookSST:true, type: 'base64'}) :
        XLSX.writeFile(wb, fn || (('document' + '.'|| 'SheetJSTableExport.') + (type || 'xlsx')));
    },
  },
  mounted() {
    this.$store.dispatch("dispayAllProduit");
    this.$store.dispatch("dispayAllSousCategories");
  },
}
</script>

<style>

</style>
