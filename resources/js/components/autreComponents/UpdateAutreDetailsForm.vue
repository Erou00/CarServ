<template>
  <div id="stockForm">
    <label for=""><strong>Qte</strong></label>
    <input
      type="number"
      id="qte"
      name="qte"
      class="form-control prixTotal mb-2"

      v-model="vqte"
      required
      min="1"
    />

    <label for=""><strong>P.U.H.T</strong></label>
    <input
      type="number"
      id="puht"
      name="puht"
      class="form-control prixTotal mb-2"
      v-model="vpuht"
      required
      min="1"
    />

    <label for=""><strong>TVA</strong></label>
    <input
      type="number"
      name="tva"
      class="form-control mb-2"
      :value="tva"
      required
      min="0"
      disabled
    />

    <label for=""><strong>Prix total</strong></label>
    <input
      type="number"
      id="prix_total"
      name="prix_total"
      class="form-control mb-2"
      :value="Math.ceil(vpuht * vqte * (1 + tva / 100))"
      required
      min="0"
    />


    <div class="modal-footer">
      <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal">
         <i class="fa fa-close"></i> Anunuler
      </button>
      <button type="submit" class="btn btn-default btn-sm"><i class="fa fa-save me-1"></i>Enregistrer</button>
    </div>
  </div>
</template>

<script>
export default {
  props: ["qte", "puht", "tva", "prixtotal","mid"],
  data() {
    return {
        vqte : this.qte,
        vpuht: this.puht,
        vmagasin_id: this.mid,

    };
  },

  computed: {
    magasins() {
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
