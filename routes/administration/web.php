<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BeController;
use App\Http\Controllers\BlController;
use App\Http\Controllers\BsController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\ConventionController;
use App\Http\Controllers\Parametrage\CategorieController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DemandeController;
use App\Http\Controllers\DemandeExternController;
use App\Http\Controllers\EditionController;
use App\Http\Controllers\EntrerController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\HistoriqueProduitController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InventaireController;
use App\Http\Controllers\MarcheController;
use App\Http\Controllers\Parametrage\SousCategorieController;
use App\Http\Controllers\Parametrage\ClasseController;
use App\Http\Controllers\Parametrage\DeviseController;
use App\Http\Controllers\Parametrage\EntiteController;
use App\Http\Controllers\Parametrage\FournisseurController;
use App\Http\Controllers\Parametrage\GroupeController;
use App\Http\Controllers\Parametrage\MagasinController;
use App\Http\Controllers\Parametrage\MarqueController;
use App\Http\Controllers\Parametrage\PaysController;
use App\Http\Controllers\Parametrage\RoleController;
use App\Http\Controllers\Parametrage\ServiceController;
use App\Http\Controllers\Parametrage\SousMagasinController;
use App\Http\Controllers\Parametrage\TvaController;
use App\Http\Controllers\Parametrage\TypeEntiteController;
use App\Http\Controllers\Parametrage\UniteReglementaireController;
use App\Http\Controllers\Parametrage\VilleController;
use App\Http\Controllers\Parametrage\VolumeController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\StockController;
use App\Models\SousMagasin;
use App\Models\TypeEntite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth'])->prefix('geststock')->group(function () {

    Route::controller(DashboardController::class)->group(function () {
        Route::get('/', 'index')->name('index');


    //Editions
    Route::get('/editions/bs-preparation',[EditionController::class,'blPreparation'])->name('editions.blPreparation');
    Route::get('/editions/produits-non-active',[EditionController::class,'produitsNonActive'])->name('editions.produitsNonActive');
    // Route::get('/editions/commandes/{etat}',[EditionController::class,'commandes'])->name('editions.commandes');
    // Route::get('/editions/conventions/{etat}',[EditionController::class,'conventions'])->name('editions.conventions');
    // Route::get('/editions/marches/{etat}',[EditionController::class,'marches'])->name('editions.marches');

    Route::get('/editions/statistiques',[EditionController::class,'statistiqueUtlistateurs'])->name('editions.statistiqueUtlistateurs');

    Route::get('/editions/approvisionnement',[EditionController::class,'approvisionnement'])->name('editions.approvisionnement');
    Route::get('/editions',[EditionController::class,'index'])->name('editions.index');


    //Profile
    Route::put('/modifier-profile/{id}',[HomeController::class,'updateProfile'])->name('profile.update');

    //Produits
    Route::get('/produits/by-categories',[ProduitController::class,'data2']);
    Route::get('/produits/imprimer', [ProduitController::class, 'rapport'])->name('produits.rapport');
    Route::get('/produits/historiques/data', [ProduitController::class, 'historiquesData'])->name('produits.historiquesData');
    Route::get('/produits/historiques', [ProduitController::class, 'historiques'])->name('produits.historiques');
    Route::delete('/produits/bulk_delete', [ProduitController::class, 'bulkDelete'])->name('produits.bulk_delete');
    Route::get('/produits/data', [ProduitController::class, 'data'])->name('produits.data');
    Route::put('/produits/produit-active/{id}', [ProduitController::class, 'active'])->name('produits.active');
    Route::put('/produits/update-produit/{id}', [ProduitController::class, 'updateProduit']);
    Route::post('/produits/add-multi-products', [ProduitController::class, 'addMultiProducts']);
    Route::get('/produits/multi-produits', [ProduitController::class, 'multi'])->name('produits.multi');

    Route::resource('produits', ProduitController::class);



    //Consultations
    Route::get('/consultations/produits-by-groupe/{id}',[ConsultationController::class,'produitsByGroupe'])->name('consultations.produitsByGroupe');
    Route::get('/consultations/produits-par-groupe',[ConsultationController::class,'produitsByGroupeView'])->name('consultations.produitsByGroupeView');


    Route::get('/consultations/entrer-stock-global-filter',[ConsultationController::class,'entrerStocksGlobalFilter'])->name('consultations.entrerStocksGlobalFilter');
    Route::get('/consultations/entrer-stock-global',[ConsultationController::class,'entrerStocksGlobal'])->name('consultations.entrerStocksGlobal');


    Route::get('/consultations/entrer-stock-filter',[ConsultationController::class,'entrerStocksFilter'])->name('consultations.entrerStocksFilter');
    Route::get('/consultations/entrer-stock',[ConsultationController::class,'entrerStocks'])->name('consultations.entrerStocks');



    Route::get('/consultations/sortie-stock-global-filter',[ConsultationController::class,'sortieStocksGlobalFilter'])->name('consultations.sortieStocksGlobalFilter');
    Route::get('/consultations/sortie-stock-global',[ConsultationController::class,'sortieStocksGlobal'])->name('consultations.sortieStocksGlobal');


    Route::get('/consultations/sortie-stock-filter',[ConsultationController::class,'sortieStocksFilter'])->name('consultations.sortieStocksFilter');
    Route::get('/consultations/sortie-stock',[ConsultationController::class,'sortieStocks'])->name('consultations.sortieStocks');

    Route::get('/consultations/stocks-minimum',[ConsultationController::class,'stockMinimum'])->name('consultations.stockMinimum');
    Route::get('/consultations/stocks/rappror',[ConsultationController::class,'stocksPrint'])->name('consultations.stocks.rapport');
    Route::get('/consultations/stocks',[ConsultationController::class,'stocks'])->name('consultations.stocks');
    Route::get('/consultations/commandes-by-entite/{id}',[ConsultationController::class,'commandeByEntite'])->name('consultations.commandeByEntite');
    Route::get('/consultations/commandes-par-entite',[ConsultationController::class,'commandeByEntiteView'])->name('consultations.commandeByEntiteView');


    //Bon sortie
    Route::get('/bon-sortie/autre-magasins',[BeController::class,'consulterAutreMagasin'])->name('bonSortie.autre_magasins');

    Route::get('/bon-sortie/no_commande/{id}',[BeController::class,'getNumCommande'])->name('bonSortie.getNumCommande');

    Route::get('/bon-sortie/imprimer/{id}',[BeController::class,'imprimer'])->name('bonSortie.imprimer');
    Route::put('/bon-sortie/update/{id}',[BeController::class,'bonSortieUpdateAfterPrint'])->name('bs.bonSortieUpdateAfterPrint');

    Route::get('/bon-sortie/fetch',[BeController::class,'fetchBonSortie'])->name('bs.fetchBonSortie');
    Route::get('/bon-sortie',[BeController::class,'create'])->name('bs.mbs');
    Route::post('bon-sorties/create', [BeController::class,'bonSortieStore']);
    Route::get('/bon-sorties/consulter', [BeController::class,'index'])->name('bs.externe');

    //sortie stock
    Route::get('/bs/autre-magasins',[BsController::class,'consulterAutreMagasin'])->name('bs.autre_magasins');
    Route::delete('/bs/bulk_validation', [BsController::class,'bulkValidation'])->name('bs.bulk_validation');

    Route::post('/bs/classement/{id}',[BsController::class,'classement'])->name('bs.classement');
    Route::get('/bs/no_commande/{id}',[BsController::class,'getNumCommande'])->name('bs.getNumCommande');
    Route::get('/bs/fetch-bs',[BsController::class,'fetchBs'])->name('bs.fetchBs');
    Route::put('/bs/update-after-print/{id}',[BsController::class,'blUpdateAfterPrint'])->name('bs.updateAfterPrint');
    Route::get('/bs/imprimer/{id}/{from?}/{duplicata}',[BsController::class,'rapport'])->name('bs.rapport');
    Route::delete('/bs/bs-details-delete/{id}',[BsController::class,'deleteBsDetails'])->name('deleteBsDetails');
    Route::put('/bs/bs-details-update/{id}',[BsController::class,'bsDetailsUpdate'])->name('bsDetailsUpdate');
    Route::post('/bs/add-to-bs-details/{id}',[BsController::class,'addToBsDetails']);
    Route::post('/bs/bs-details',[BsController::class,'bsWithDetails'])->name('bs.bsWithDetails');
    Route::resource('bs', BsController::class);

      //Inventaires
      Route::get('/inventaires/autre-magasins',[InventaireController::class,'consulterAutreMagasin'])->name('inventaires.autre_magasins');

      Route::get('/inventaires/imprimer/{id}',[InventaireController::class,'rapport'])->name('inventaires.rapport');
      Route::delete('/inventaires/inventaire-details-delete/{id}',[InventaireController::class,'deleteInventaireDetails'])->name('inventaires.deleteInventaireDetails');
      Route::put('/inventaires/inventaire-details-update/{id}',[InventaireController::class,'inventaireDetailsUpdate'])->name('inventaires.inventaireDetailsUpdate');
      Route::get('/inventaires/inventaire-by-id/{id}',[InventaireController::class,'inventaireById'])->name('inventaires.inventaireById');
      Route::post('/inventaires/add-to-inventaire-details/{id}',[InventaireController::class,'addToInventaireDetails']);
      Route::post('/inventaires/inventaire-details',[InventaireController::class,'inventaireWithDetails'])->name('inventaires.inventaireWithDetails');
      Route::resource('inventaires', InventaireController::class);

     //Entrer Stock
     Route::resource('bls', BlController::class);

     //Commande
     Route::get('/stocks/all-commandes/{id}',[StockController::class,'allCommandeDetails'])->name('allCommnandeDetails');
    //Convention
    Route::get('/stocks/all-conventions/{id}',[StockController::class,'allConventionDetails'])->name('allConventionDetails');
    //marche
    Route::get('/stocks/all-marches/{id}',[StockController::class,'allMarcheDetails'])->name('allMarcheDetails');

    // Entrer
    Route::get('/autres/autre-magasins',[EntrerController::class,'consulterAutreMagasin'])->name('autres.autre_magasins');

    Route::get('/autres/imprimer/{id}',[EntrerController::class,'rapport'])->name('autres.rapport');

    Route::put('/autres/autes-details-update/{id}',[EntrerController::class,'updateAutreDetails'])->name('autres.updateAutreDetails');
    Route::delete('/autres/delete-stock/{id}',[EntrerController::class,'deleteStock'])->name('autres.deleteStock');
    Route::post('/autres/add-to-stock/{id}',[EntrerController::class,'addToStock']);
    Route::post('/autres/entrer-stock',[EntrerController::class,'storeWithStock'])->name('autres.storeWithStock');
    Route::resource('autres', EntrerController::class);


    //Bl
    Route::get('/bon-de-livraison/autre-magasins',[BlController::class,'consulterAutreMagasin'])->name('bls.autre_magasins');
    Route::put('/bls/update-after-print/{id}',[BlController::class,'blUpdateAfterPrint'])->name('bls.updateAfterPrint');
    Route::get('/bls/imprimer/{id}',[BlController::class,'rapport'])->name('bls.rapport');
    Route::delete('/bls/bl-details-delete/{id}',[BlController::class,'deleteBlDetails'])->name('deleteBlDetails');
    Route::put('/bls/bl-details-update/{id}',[BlController::class,'blDetailsUpdate'])->name('blDetailsUpdate');
    Route::post('/bls/add-to-bl-details/{id}',[BlController::class,'addToBlDetails']);
    Route::post('/bls/bl-details',[BlController::class,'blWithDetails'])->name('bls.blWithDetails');
    Route::get('/bon-de-livraison/bon-commande',[BlController::class,'BlCommande'])->name('createBlCommande');
    Route::get('/bon-de-livraison/convention',[BlController::class,'BlConvention'])->name('createBlConvention');
    Route::get('/bon-de-livraison/marche',[BlController::class,'BlMarche'])->name('createBlMarche');
    Route::get('/bon-entrer/conventions',[BlController::class,'getBlofConvention'])->name('bls.getBlofConvention');
    Route::get('/bon-entrer/marches',[BlController::class,'getBlofMarche'])->name('bls.getBlofMarche');
    Route::get('/bon-entrer/commandes',[BlController::class,'getBlofCommande'])->name('bls.getBlofCommande');
    Route::resource('bls', BlController::class);



    //Factures
    Route::post('factures/add',[FactureController::class,'addFacture'])->name('factures.addFacture');
    Route::get('factures/all-factures',[FactureController::class,'getFacture'])->name('factures.getFacture');
    Route::resource('factures',FactureController::class);

    //Commandes Externe
        //Convention
        Route::get('/conventions/autre-magasins',[ConventionController::class,'consulterAutreMagasin'])->name('conventions.autre_magasins');
        Route::get('/conventions/imprimer/{id}',[ConventionController::class,'rapport'])->name('conventions.rapport');
        Route::get('/conventions/all-conventions',[ConventionController::class,'allConventions'])->name('conventions.allConventions');
        Route::delete('/conventions/delete-stock/{id}',[ConventionController::class,'deleteStock'])->name('conventions.deleteStock');
        Route::put('/conventions/update-stock/{id}',[ConventionController::class,'updateStock'])->name('conventions.updateStock');
        Route::post('/conventions/add-to-stock/{id}',[ConventionController::class,'addToStock']);
        Route::get('/conventionss/data',[ConventionController::class,'data'])->name('conventions.data');
        Route::post('/conventions/convention-stock',[ConventionController::class,'storeWithStock'])->name('conventions.storeWithStock');
        Route::resource('conventions', ConventionController::class);
        //Marchés
        Route::get('/marches/autre-magasins',[MarcheController::class,'consulterAutreMagasin'])->name('marches.autre_magasins');
        Route::get('/marches/imprimer/{id}',[MarcheController::class,'rapport'])->name('marches.rapport');
        Route::get('/marches/all-marches',[MarcheController::class,'allMarches'])->name('marches.allMarches');
        Route::delete('/marches/delete-stock/{id}',[MarcheController::class,'deleteStock'])->name('marches.deleteStock');
        Route::put('/marches/update-stock/{id}',[MarcheController::class,'updateStock'])->name('marches.updateStock');
        Route::post('/marches/add-to-stock/{id}',[MarcheController::class,'addToStock']);
        Route::get('/marches/data',[MarcheController::class,'data'])->name('marches.data');
        Route::post('/marches/marche-stock',[MarcheController::class,'storeWithStock'])->name('marches.storeWithStock');
        Route::resource('marches', MarcheController::class);
        //Commandes /commandes/no_commande/


        Route::get('/commandes/autre-magasins',[CommandeController::class,'consulterAutreMagasin'])->name('commandes.autre_magasins');
        Route::get('/commandes/no_commande/{id}',[CommandeController::class,'getNumCommande'])->name('commandes.getNumCommande');

        Route::get('/commandes/imprimer/{id}',[CommandeController::class,'rapport'])->name('commandes.rapport');
        Route::get('/commandes/all-commandes',[CommandeController::class,'allCommandes'])->name('commandes.allCommandes');
        Route::delete('/commandes/delete-stock/{id}',[CommandeController::class,'deleteStock'])->name('commandes.deleteStock');
        Route::put('/commandes/update-stock/{id}',[CommandeController::class,'updateStock'])->name('commandes.updateStock');
        Route::get('/commandes/data',[CommandeController::class,'data'])->name('commandes.data');
        Route::post('/commandes/add-to-stock/{id}',[CommandeController::class,'addToStock']);
        Route::post('/commandes/commande-stock',[CommandeController::class,'storeWithStock'])->name('commandes.storeWithStock');
        Route::resource('commandes', CommandeController::class);

    //Commandes Interne

    Route::get('/demandes/autre-magasins',[DemandeController::class,'consulterAutreMagasin'])->name('demandes.autre_magasins');

    Route::delete('/demandes-externe/delete-demande-details/{id}',[DemandeExternController::class,'deleteDetails'])->name('demandes-externe.deleteDetails');
    Route::put('/demandes-externe/update-demande-details/{id}',[DemandeExternController::class,'updateDetails'])->name('demandes-externe.updateDetails');
    Route::post('/demandes-externe/add-to-demande-details/{id}',[DemandeExternController::class,'addToDetails']);
    Route::resource('demandes-externe',DemandeExternController::class);


    Route::get('/demandes/no_commande/{id}',[DemandeController::class,'getNumCommande'])->name('demandes.getNumCommande');
    Route::post('/demandes/impoter',[DemandeController::class,'importerDemande'])->name('demandes.importerDemande');
    Route::delete('/demandes/delete-produit/{id}',[DemandeController::class,'deleteProduit']);
    Route::put('/demandes/update-entite/{id}',[DemandeController::class,'updateEntite']);
    Route::put('/demandes/update-after-print/{id}',[DemandeController::class,'demandeUpdateAfterPrint'])->name('demandes.updateAfterPrint');
    Route::get('/demandes/imprimer/{id}',[DemandeController::class,'rapport'])->name('demandes.rapport');
    Route::get('/demandes/fetch-demandes/',[DemandeController::class,'FetchDemandes'])->name('demandes.FetchDemandes');
    Route::post('/demandes/demande-periodique/',[DemandeController::class,'storeDemandePeriodique'])->name('demandes.storeDemandePeriodique');
    Route::get('/demandes/demande-periodique/',[DemandeController::class,'demandePeriodique'])->name('demandes.demandePeriodique');
    Route::get('/demandes/demande-details/{id}',[DemandeController::class,'DemandeDetails'])->name('demandes.DemandeDetails');
    Route::get('/demandes/all-demandes',[DemandeController::class,'AllDemandes'])->name('demandes.AllDemandes');
    Route::delete('/demandes/delete-demande-details/{id}',[DemandeController::class,'deleteDetails'])->name('demandes.deleteDetails');
    Route::put('/demandes/update-demande-details/{id}',[DemandeController::class,'updateDetails'])->name('demandes.updateDetails');
    Route::post('/demandes/add-to-demande-details/{id}',[DemandeController::class,'addToDetails']);
    Route::post('/demandes/demande-details/',[DemandeController::class,'storeWithDetails'])->name('demandes.storeWithDetails');
    Route::resource('demandes', DemandeController::class);

    //Utlisateurs
    Route::delete('/utilisateurs/bulk_delete', [AdminController::class, 'bulkDelete'])->name('utilisateurs.bulk_delete');
    Route::get('/utilisateurs/data', [AdminController::class, 'data'])->name('utilisateurs.data');
    Route::resource('utilisateurs', AdminController::class);

    Route::prefix('parametrage')->group(function () {

        //Entites
        Route::get('/entites/print', [EntiteController::class, 'rapport'])->name('entites.print');
        Route::get('/entites/all-entites', [EntiteController::class,'allEntites'])->name('entites.allEntites');
        Route::delete('/entites/bulk_delete', [EntiteController::class, 'bulkDelete'])->name('entites.bulk_delete');
        Route::get('/entites/data', [EntiteController::class, 'data'])->name('entites.data');
        Route::resource('entites', EntiteController::class);

        //Type Entite
        Route::get('/types/print', [TypeEntiteController::class, 'rapport'])->name('types.print');
        Route::delete('/types/bulk_delete', [TypeEntiteController::class, 'bulkDelete'])->name('types.bulk_delete');
        Route::get('/types/data', [TypeEntiteController::class, 'data'])->name('types.data');
        Route::resource('types', TypeEntiteController::class);

        //Groupes
        Route::delete('/groupes/bulk_delete', [GroupeController::class, 'bulkDelete'])->name('groupes.bulk_delete');
        Route::get('/groupes/data', [GroupeController::class, 'data'])->name('groupes.data');
        Route::resource('groupes', GroupeController::class);

        //Roles
        Route::delete('/roles/bulk_delete', [RoleController::class, 'bulkDelete'])->name('roles.bulk_delete');
        Route::get('/roles/data', [RoleController::class, 'data'])->name('roles.data');
        Route::resource('roles', RoleController::class);

        //Categories
        Route::get('/categories/print', [CategorieController::class, 'rapport'])->name('categories.print');
        Route::delete('/categories/bulk_delete', [CategorieController::class, 'bulkDelete'])->name('categories.bulk_delete');
        Route::get('/categories/data', [CategorieController::class, 'data'])->name('categories.data');
        Route::resource('categories', CategorieController::class);

        //Pays
        Route::delete('/pays/bulk_delete', [PaysController::class, 'bulkDelete'])->name('pays.bulk_delete');
        Route::get('/pays/data', [PaysController::class, 'data'])->name('pays.data');
        Route::resource('pays', PaysController::class);



        //Villes
        Route::delete('/villes/bulk_delete', [VilleController::class, 'bulkDelete'])->name('villes.bulk_delete');
        Route::get('/villes/data', [VilleController::class, 'data'])->name('villes.data');
        Route::resource('villes', VilleController::class);


        //Services
        Route::delete('/services/bulk_delete', [ServiceController::class,'bulkDelete'])->name('services.bulk_delete');
        Route::get('/services/data', [ServiceController::class,'data'])->name('services.data');
        Route::resource('services',ServiceController::class);


         //tva
         Route::delete('/tvas/bulk_delete', [TvaController::class,'bulkDelete'])->name('tvas.bulk_delete');
         Route::get('/tvas/data', [TvaController::class,'data'])->name('tvas.data');
         Route::resource('tvas',TvaController::class);


        //Sous Magasin
        Route::get('/sous_magasins/by-user', [SousMagasinController::class, 'getUserSousMagasin'])->name('sous_magasins.getUserSousMagasin');
        Route::post('/sous_magasins/by-magasin', [SousMagasinController::class, 'getSousMagasin'])->name('sous_magasins.getSousMagasins');
        Route::get('/sous_magasins/print', [SousMagasinController::class, 'rapport'])->name('sous_magasins.print');
        Route::get('/sous_magasins/allSousMagasins', [SousMagasinController::class,'allSousmagasins'])->name('sous_magasins.allSousMagasins');
        Route::delete('/sous_magasins/bulk_delete', [SousMagasinController::class, 'bulkDelete'])->name('sous_magasins.bulk_delete');
        Route::get('/sous_magasins/data', [SousMagasinController::class, 'data'])->name('sous_magasins.data');
        Route::resource('sous_magasins', SousMagasinController::class);


        //Magasins
        Route::delete('/magasins/bulk_delete', [MagasinController::class,'bulkDelete'])->name('magasins.bulk_delete');
        Route::get('/magasins/data', [MagasinController::class,'data'])->name('magasins.data');
        Route::get('/magasins/all-magasins', [MagasinController::class,'allMagasins'])->name('magasins.allMagasins');
        Route::resource('magasins',MagasinController::class);

        //Sous Categories
        Route::post('/sous_categories/by-categorie', [SousCategorieController::class, 'getSousCategorie'])->name('sous_categories.getSousCategorie');
        Route::get('/sous_categories/print', [SousCategorieController::class, 'rapport'])->name('sous_categories.print');
        Route::get('/sous_categories/allSousCategories', [SousCategorieController::class,'allSousCategories'])->name('sous_categories.allSousCategories');
        Route::delete('/sous_categories/bulk_delete', [SousCategorieController::class, 'bulkDelete'])->name('sous_categories.bulk_delete');
        Route::get('/sous_categories/data', [SousCategorieController::class, 'data'])->name('sous_categories.data');
        Route::resource('sous_categories', SousCategorieController::class);


        //marques
        Route::post('/marques/by-sous-categorie', [MarqueController::class, 'getMarque'])->name('marques.getMarque');
        Route::get('/marques/print', [MarqueController::class, 'rapport'])->name('marques.print');
        Route::delete('/marques/bulk_delete', [MarqueController::class, 'bulkDelete'])->name('marques.bulk_delete');
        Route::get('/marques/data', [MarqueController::class, 'data'])->name('marques.data');
        Route::resource('marques', MarqueController::class);


        // Fournisseurs
        Route::get('/fournisseurs/print', [FournisseurController::class, 'rapport'])->name('fournisseurs.print');
        Route::get('/fournisseurs/all-fournisseurs', [FournisseurController::class, 'allFournisseurs'])->name('fournisseurs.allFournisseurs');
        Route::delete('/fournisseurs/bulk_delete', [FournisseurController::class, 'bulkDelete'])->name('fournisseurs.bulk_delete');
        Route::get('/fournisseurs/data', [FournisseurController::class, 'data'])->name('fournisseurs.data');
        Route::resource('fournisseurs', FournisseurController::class);

        // Volumes
        Route::delete('/volumes/bulk_delete', [VolumeController::class, 'bulkDelete'])->name('volumes.bulk_delete');
        Route::get('/volumes/data', [VolumeController::class, 'data'])->name('volumes.data');
        Route::resource('volumes', VolumeController::class);


        // Classes
        Route::delete('/classes/bulk_delete', [ClasseController::class, 'bulkDelete'])->name('classes.bulk_delete');
        Route::get('/classes/data', [ClasseController::class, 'data'])->name('classes.data');
        Route::resource('classes', ClasseController::class);

         // Devises
         Route::delete('/devises/bulk_delete', [DeviseController::class, 'bulkDelete'])->name('devises.bulk_delete');
         Route::get('/devises/data', [DeviseController::class, 'data'])->name('devises.data');
         Route::resource('devises', DeviseController::class);

          // Unite Reglementaire
        Route::delete('/unite_reglementaire/bulk_delete', [UniteReglementaireController::class, 'bulkDelete'])->name('unite_reglementaire.bulk_delete');
        Route::get('/unite_reglementaire/data', [UniteReglementaireController::class, 'data'])->name('unite_reglementaire.data');
        Route::resource('unite_reglementaire', UniteReglementaireController::class);


    });
});
});
Auth::routes([ 'register' => false]);
