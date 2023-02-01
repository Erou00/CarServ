import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const store = new Vuex.Store({
  state: {
    listOfProduits : [],
    listOfProduitsByCategories : [],
    listOfSousCategories : [],
    isValide:[],
    prepareToAdd:false,
    produits:[],
    listOfFournisseurs:[],
    listOfStocks:[],
    listOfMagasins:[],
    listOfSousMagasins:[],
    listOfCommandes:[],
    commandeById:'',
    listOfConventions:[],
    listOfConventionDetails:[],
    conventionById:'',
    listOfMarches:[],
    marcheById:'',
    listOfMarchesDetails:[],
    listOfEntites : [],
    listOfDemandes:[],
    listOfDemandeDetails:[],
    listOfLastDemande:[],
    demandeById:'',
    listOfBs : [],


  },
  mutations: {
    initValid(state,statut){
        state.isValide.push(statut)
    },
    EMPTYISVALIDE(state){
        state.isValide = []
    },
    initProduits(state, produits) {
        state.listOfProduits = produits;
    },

    initProduitsByCategories(state, produits) {
        state.listOfProduitsByCategories = produits;
    },

    initFournisseurs(state, fournisseurs) {
    state.listOfFournisseurs = fournisseurs;
    },
    initStocks(state, stocks) {
        state.listOfStocks = stocks;
    },
    initMagasins(state, magasin) {
        state.listOfMagasins = magasin;
    },
    initSousMagasins(state, magasin) {
        state.listOfSousMagasins = magasin;
    },
    initCommandes(state, commandes) {
        state.listOfCommandes = commandes;
    },
    initCommandeById(state,commande){
        state.commandeById = commande
     },

    initEntites(state, entites) {
        state.listOfEntites = entites;
    },

    initProduit(state, produit) {
        state.produits.push(produit);
      },
    emptyListOfProduit(state){
        state.listOfProduits = []
    },
    pushField(state,produit){
        state.listOfProduits.push(produit)
    },
    removeProduitByIndex(state,index){
        state.listOfProduits.splice(index, 1);
    },
    PREPARETOADD(state,isValid) {
        state.prepareToAdd = isValid
    },
    updateCategorie(state,{index,cid}) {
        state.listOfProduits[index].categorie_id = cid
    },

    initSousCategories(state, sousCategories) {
        state.listOfSousCategories = sousCategories;
      },

      initConventions(state, conventions) {
        state.listOfConventions = conventions;
    },

    initConventionDetails(state, details) {
        state.listOfConventionDetails = details;
    },

    initConventionById(state,convention){
        state.conventionById = convention
    },

    initMarches(state, marches) {
        state.listOfMarches = marches;
    },
    initMarcheById(state,marche){
       state.marcheById = marche
    },
      initMarcheDetails(state, details) {
        state.listOfMarchesDetails = details;
    },

    initDemandes(state, demandes) {
        state.listOfDemandes = demandes;
    },
      initDemandeDetails(state, details) {
        state.listOfDemandeDetails = details;
    },

    initLastDemande(state, details) {
        state.listOfLastDemande = details;
    },

    initDemandeById(state, demande) {
        state.demandeById = demande;
    },

    initBs(state,bs){
        state.listOfBs = bs
    }

  },

  getters :{

    productList: state => state.listOfProduits,
    productListByCategories: state => state.listOfProduitsByCategories,
    fournisseursList: state => state.listOfFournisseurs,
    stocksList: state => state.listOfStocks,
    magasinsList: state => state.listOfMagasins,
    sousmagasinsList: state => state.listOfSousMagasins,
    commandesList: state => state.listOfCommandes,
    commandeById: state => state.commandeById,

    conventionsList: state => state.listOfConventions,
    conventionDetailsList: state => state.listOfConventionDetails,
    conventionById: state => state.conventionById,


    marchesList: state => state.listOfMarches,
    marcheById: state => state.marcheById,
    marcheDetailsList: state => state.listOfMarchesDetails,

    demandesList: state => state.listOfDemandes,
    demandeDetailsList: state => state.listOfDemandeDetails,
    demandeById: state => state.demandeById,
    lastDemandeTake: state => state.listOfLastDemande,



    entitesList: state => state.listOfEntites,
    allValide : state => state.isValide,
    sousCategoriesList: state => state.listOfSousCategories,
    isAllValide: state => state.prepareToAdd,
    productById: (state,getters) => (id) => {
         return getters.productList.find(p =>{
            return p.id == id
        })
    },
    produitsList: state => state.produits,
    bsList : state => state.listOfBs,



  },

  actions:{
    dispayAllProduit({commit}){
        axios.get("/geststock/produits/data").then((response) => {
            const products = response.data.produits;
            commit('initProduits', products);
        });
    },
    dispayAllProduitByCategories({commit}){
        axios.get("/geststock/produits/by-categories").then((response) => {
            const products = response.data.produits;
            commit('initProduitsByCategories', products);
        });
    },
    async dispayAllSousCategories({commit,getters}){
        await axios.get("/geststock/parametrage/sous_categories/data").then((response) => {
            commit('initSousCategories', response.data.sousCategories);
            //console.log(getters.sousCategoriesList);

        });
    },
    async dispayAllFournisseurs({commit}){
        await axios.get("/geststock/parametrage/fournisseurs/all-fournisseurs").then((response) => {
            const fournisseurs = response.data.fournisseurs;
            commit('initFournisseurs', fournisseurs);
        });
    },

    async displayAllCommandes({commit}){
        await axios.get("/geststock/commandes/all-commandes").then((response) => {
            const commandes = response.data.commandes;
            commit('initCommandes', commandes);
        });
    },

    async displayAllCommandeDetails({commit},id){
        await axios.get(`/geststock/stocks/all-commandes/${id}`).then((response) => {
            const commande = response.data.commande;
            const stocks = response.data.stocks;
            commit('initCommandeById', commande);
            commit('initStocks', stocks);
        });
    },

    async displayAllConventions({commit}){
        await axios.get("/geststock/conventions/all-conventions").then((response) => {
            const conventions = response.data.conventions;
            commit('initConventions', conventions);
        });
    },

    async displayAllConventionDetails({commit},id){
        await axios.get(`/geststock/stocks/all-conventions/${id}`).then((response) => {
            const convention = response.data.convention;
            const stocks = response.data.stocks;
            commit('initConventionById', convention);
            commit('initConventionDetails', stocks);
        });
    },

    async displayAllMarches({commit}){
        await axios.get("/geststock/marches/all-marches").then((response) => {
            const marches = response.data.marches;
            commit('initMarches', marches);
        });
    },

    async displayAllMarcheDetails({commit},id){
        await axios.get(`/geststock/stocks/all-marches/${id}`).then((response) => {
            const marche =  response.data.marche;
            const stocks = response.data.stocks;
            commit('initMarcheDetails', stocks);
            commit('initMarcheById', marche);
        });
    },

    async displayAllDemandes({commit}){
        await axios.get("/geststock/demandes/all-demandes").then((response) => {
            const demandes = response.data.demandes;
            commit('initDemandes', demandes);
        });
    },

    async displayAllBs({commit}){
        await axios.get("/geststock/bs/fetch-bs").then((response) => {
            const bs = response.data.bs;
            commit('initBs', bs);
        });
    },

    async displayAllDemandeDetails({commit},id){
        await axios.get(`/geststock/demandes/demande-details/${id}`).then((response) => {
            const details = response.data.details;
            const demande = response.data.demande;
            const lastDemande = response.data.lastDemande;
            commit('initDemandeById', demande);
            commit('initDemandeDetails', details);
            commit('initLastDemande', lastDemande);
        });
    },



    displayAllMagasins({commit}){
        axios.get("/geststock/parametrage/magasins/all-magasins").then((response) => {
            const magasins = response.data.magasins            ;
            commit('initMagasins', magasins);
        });
    },

    displayAllSousMagasins({commit}){
        axios.get("/geststock/parametrage/sous_magasins/by-user").then((response) => {
            const magasins  = response.data.SousMagasins           ;
            commit('initSousMagasins', magasins);
        });
    },

    displayAllEntites({commit}){
        axios.get("/geststock/parametrage/entites/all-entites").then((response) => {
            const entites = response.data.entites            ;
            commit('initEntites', entites);
        });
    },
    addMultiProduit({ dispatch },data){
        axios.post('/geststock/produits/add-multi-products/',data).then(response => {
            //dispatch('dispayAllProduit')
           });
    },
    AddPoduitToList({commit},payload){
        commit('initProduit',payload.produit);
    },
    videListOfProduit({commit}){
        commit('emptyListOfProduit')
    },
    addField({commit},payload){
        commit('pushField',payload.produit)
    },
    resetProduits({ dispatch }){
        dispatch('videListOfProduit')
        dispatch('dispayAllProduit')
    },

    rProduitByInde({commit},payload){
        commit('removeProduitByIndex',payload.index)
    },
    isValide({commit},payload){
        commit('initValid',payload.statut)
    },
    emptyIsValide({commit}){
        commit('EMPTYISVALIDE')
    },
    prepareToAdd({commit},payload){
        commit('PREPARETOADD',payload.isValid)
    },
    deleteProduit({dispatch},id) {
        axios.delete("/geststock/produits/" + id).then((response) => {
            dispatch('dispayAllProduit');
        });
    },

  }
})

export default store
