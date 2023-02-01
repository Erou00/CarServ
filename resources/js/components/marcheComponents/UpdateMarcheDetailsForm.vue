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
      readonly
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
      <button type="button" class="btn btn-default" data-bs-dismiss="modal">
        <i class="fa fa-close"></i>Annuler
      </button>
      <button type="submit" class="btn btn-default"><i class="fa fa-save"></i> Enregistrer</button>
    </div>
  </div>
</template>

<script>

export default {
  props: ["qte", "puht", "tva", "prixtotal"],
  data() {
    return {
        vqte : this.qte,
        vpuht: this.puht,

    };
  },
};
</script>

<style>
</style>
