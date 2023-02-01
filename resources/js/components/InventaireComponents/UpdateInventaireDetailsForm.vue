<template>
  <div>

    <label for=""><strong>LOT</strong></label>
    <input
      type="text"
      id="lot"
      name="lot"
      class="form-control prixTotal mb-2"
      v-model="vlot"
      required

    />

    <label for=""><strong>Date Premption</strong></label>
    <input
      type="date"
      id="date_premption"
      name="date_premption"
      class="form-control prixTotal mb-2"
      :value="vdate_premption"
      required

    />

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

    <label for=""><strong>Qte Stock</strong></label>
    <input
      type="number"
      id="qte"
      name="qte_stock"
      class="form-control prixTotal mb-2"
      v-model="vqte_stock"
      required
      min="1"
    />

    <label for=""><strong>Magasin</strong></label>
    <select id="magasin" class="form-control" name="magasin_id" required>
      <option
        v-for="(m, index) in magasins"
        :key="index"
        :value="m.id"
        :selected="vmagasin_id == m.id"
      >
        {{ m.nom }}
      </option>
    </select>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Close
        </button>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
      </div>
  </div>
</template>

<script>
export default {
    props: ["ind","dp","qte","qs", "magasin"],
    data() {
      return {
            vlot : this.ind.lot,
            vdate_premption : this.ind.date_premption,
            vqte : this.ind.qte,
            vqte_stock : this.ind.qte_stock,
            vmagasin_id: this.ind.magasin_id,
      };
    },
    computed:{
        magasins(){
          return this.$store.getters.magasinsList;
      },
    },
    mounted() {
      this.$store.dispatch("displayAllMagasins");
      console.log(this.vdate_premption);
    },
};
</script>

<style>
</style>
