<template>
    <div id="stockForm">
      <label for=""><strong>Qte Commandée</strong></label>
      <input
        type="number"
        id="qte_stock"
        name="qte_demandee"
        class="form-control prixTotal mb-2"
        v-model="vqte_demandee"
        required
        min="1"
      />




      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-bs-dismiss="modal">
          <i class="fa fa-close me-1"></i>Annuler
        </button>
        <button type="submit" class="btn btn-default"><i class="fa fa-save"></i> Enregistrer</button>
      </div>
    </div>
</template>

<script>
export default {
    props:["qtedemandee"],
    data() {
    return {
        vqte_demandee : this.qtedemandee,
    }
},
mounted() {
},
}
</script>

<style>

</style>
