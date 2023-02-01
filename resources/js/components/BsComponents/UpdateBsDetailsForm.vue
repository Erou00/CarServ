<template>
    <div id="stockForm">
      <label for=""><strong>Qte Livrée</strong></label>
      <input
        type="number"
        id="qte"
        name="qte_donnee"
        class="form-control prixTotal mb-2"
        v-model="vqte_donnee"
        required
        min="1"
      />
      <div>
        <div class="error" v-if="!$v.vqte_donnee.required">le champs est obligatoire</div>
        <div class="error" v-if="!$v.vqte_donnee[valName]">le champs  doit être entre 0 et {{qte}}</div>



      </div>



      <label for=""><strong>Magasin</strong></label>
      <select id="magasin" class="form-control" name="magasin_id" required>

            <option v-for="m,index in magasins" :key="index" :value="m.id" :selected="vmagasin_id==m.id">{{m.nom}}</option>

      </select>


      <div class="modal-footer">
        <button type="button" class="btn btn-default me-1" data-bs-dismiss="modal">
          <i class="fa fa-close"></i>Annuler
        </button>
        <button type="submit" class="btn btn-default" >
            <i class="fa fa-save me-1"></i>Enregistrer</button>
      </div>
    </div>
  </template>

  <script>
      import { required, minLength,between, helpers } from "vuelidate/lib/validators";
      const mustBeCool = (value,qte) => {
        return (

            !helpers.req(value) || (value > 0 && value < qte)

        );
        };
  export default {
    props: ["qte","qtedonnee", "magasin"],

    data() {
      return {
          vqte_donnee : this.qtedonnee,
          vmagasin_id: this.magasin,
          valName: 'validatorName'

      };
    },
    computed:{
        magasins(){
          return this.$store.getters.magasinsList;
      },
    },
    validations() {
    return {

             vqte_donnee: {required,
                [this.valName]:between(0,this.qte) }
            }

    },
    mounted() {
      this.$store.dispatch("displayAllMagasins");
    },
  };
  </script>

  <style>
  </style>
