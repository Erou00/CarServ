<template>
    <div id="stockForm">
      <label for=""><strong>Qte Livrée</strong></label>
      <input
        type="number"
        id="qte"
        name="qte_livree"
        class="form-control prixTotal mb-2"
        v-model="vqte_livree"
        required
        min="1"
      />

      <!-- <span v-if="!$v.vqte_livree.required" class="text-danger">
              le champs est obligatoire
            </span>

            <span v-if="!$v.vqte_livree.mustBeCool" class="text-danger">
                Qte donnée doit être i
                  {{ vqte  }}
            </span> -->

            <div class="error" v-if="!$v.vqte_livree.required">le champs est obligatoire</div>
        <div class="error" v-if="!$v.vqte_livree[valName]">le champs  doit être entre 0 et {{qte}}</div>






      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-bs-dismiss="modal">
         <i class="fa fa-close"></i> Annuler
        </button>
        <button type="submit" class="btn btn-default">
            <i class="fa fa-save"></i> Enregistrer</button>
      </div>
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

const mustBeCool = (value, qte_demmandee) => {
  return (
    !helpers.req(value) || (value > 0 && value <= qte_demmandee)
  );
};

const contains = (param) =>
  helpers.withParams(
    { type: 'contains', value: param },
    (value) => !helpers.req(value) || (value > 0 && value <= param)
  )

  export default {
    props: ["ql", "magasin","qte"],
    data() {
      return {
          vqte_livree : this.ql,
          vmagasin_id: this.magasin,
          vqte:this.qte,
          valName: 'validatorName'
      };
    },
    validations() {
    return {

             vqte_livree: {required,
                [this.valName]:between(0,this.qte) }
            }

    },
    computed:{
        magasins(){
          return this.$store.getters.magasinsList;
      },
    },
    mounted() {
      this.$store.dispatch("displayAllMagasins");
    },
  };
  </script>

  <style>
  </style>
