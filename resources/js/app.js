

require('./bootstrap');
window.Vue = require('vue').default;

import JwPagination from 'jw-vue-pagination';
import 'vue-select/dist/vue-select.css';


import VueTableDynamic from 'vue-table-dynamic';
import VueConfirmDialog from 'vue-confirm-dialog'
import Vue from "vue";
import Vuex from "vuex";
import store from './store/index.js';
import Vuelidate from 'vuelidate'
import vSelect from 'vue-select'
import Toasted from 'vue-toasted';
import Toastr from 'vue-toastr';
import excel from 'vue-excel-export'


Vue.use(Vuelidate)
Vue.use(Vuex);
Vue.use(VueTableDynamic)
Vue.use(VueConfirmDialog)
Vue.use(Toasted)
Vue.use(Toastr)
Vue.use(excel)

Vue.component('vue-confirm-dialog', VueConfirmDialog.default)
Vue.component('v-select', vSelect)

window.moment = require('moment');
window.moment.locale('ru');


Vue.component('jw-pagination', JwPagination);

//Pages
Vue.component('produits-page', require('./pages/Produit.vue').default);

//Approvisionnement
Vue.component('commandes-page', require('./pages/approvisionnement/Commande.vue').default);
Vue.component('marches-page', require('./pages/approvisionnement/Marche.vue').default);
Vue.component('conventions-page', require('./pages/approvisionnement/Convention.vue').default);

//Entrer stock
Vue.component('bl-commande-page', require('./pages/entrer_stock/BlCommande.vue').default);
Vue.component('bl-convention-page', require('./pages/entrer_stock/BlConvention.vue').default);
Vue.component('bl-marche-page', require('./pages/entrer_stock/BlMarche.vue').default);
Vue.component('entrer-page', require('./pages/entrer_stock/autre/Entrer.vue').default);




Vue.component('inventaire-page', require('./pages/Inventaire.vue').default);

Vue.component('demandes-page', require('./pages/Demande.vue').default);
Vue.component('demandes-periodique-page', require('./pages/DemandePeriodique.vue').default);


//sortie stock
Vue.component('bs-page', require('./pages/sortie_stock/Bs.vue').default);
Vue.component('m-bs-page', require('./pages/sortie_stock/Mbs.vue').default);


//Consultation
Vue.component('produits-by-groupe-page', require('./pages/consultations/ProduitsByGroupe.vue').default);
Vue.component('commandes-by-entite-page', require('./pages/consultations/CommandeByEntite.vue').default);
Vue.component('stock-minimum-page', require('./pages/consultations/StockMinimum.vue').default);
Vue.component('entrer-stock-global-page', require('./pages/consultations/EntrerStockGlobal.vue').default);
Vue.component('entrer-stock-multi-page', require('./pages/consultations/EntrerStockMulti.vue').default);

Vue.component('sortie-stock-global-page', require('./pages/consultations/SortieStockGlobal.vue').default);
Vue.component('sortie-stock-multi-page', require('./pages/consultations/SortieStockMulti.vue').default);
Vue.component('stocks-page', require('./pages/consultations/Stock.vue').default);



//Components

//Spinner


//Produits
Vue.component('produit-create', require('./components/ProduitComponents/ProduitCreate.vue').default);



Vue.component('update-stock-form', require('./components/stockComponents/UpdateStockForm.vue').default);
Vue.component('add-stock-form', require('./components/stockComponents/AddStockForm.vue').default);

Vue.component('update-marche-details-form', require('./components/marcheComponents/UpdateMarcheDetailsForm.vue').default);
Vue.component('add-marche-details-form', require('./components/marcheComponents/AddMarcheDetailsForm.vue').default);

Vue.component('update-convention-details-form', require('./components/conventionComponents/UpdateConventionDetailsForm.vue').default);
Vue.component('add-convention-details-form', require('./components/conventionComponents/AddConventionDetailsForm.vue').default);

//Auters
Vue.component('add-autre-details-form', require('./components/autreComponents/AddAutreDetailsForm.vue').default);
Vue.component('update-autre-details-form', require('./components/autreComponents/UpdateAutreDetailsForm.vue').default);


Vue.component('add-bl-details-form', require('./components/BlComponents/AddBlDetailsForm.vue').default);
Vue.component('update-bl-details-form', require('./components/BlComponents/UpdateBlDetailsForm.vue').default);


Vue.component('update-inventaire', require('./components/InventaireComponents/InventaireUpdate.vue').default);
Vue.component('update-inventaire-details-form', require('./components/InventaireComponents/UpdateInventaireDetailsForm.vue').default);
Vue.component('add-inventaire-details-form', require('./components/InventaireComponents/AddInventaireDetailsForm.vue').default);

Vue.component('add-demande-details-form', require('./components/DemandeComponents/AddDemandeDetailsForm.vue').default);
Vue.component('update-demande-details-form', require('./components/DemandeComponents/UpdateDemandeDetailsForm.vue').default);

Vue.component('update-bs-details-form', require('./components/BsComponents/UpdateBsDetailsForm.vue').default);


Vue.component('print-link', require('./components/imp/Imprimer.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

 const app = new Vue({
    el: '#app',
    store
});


