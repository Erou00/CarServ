<template>
  <button class="btn btn-sm btn-warning" @click="imprimer()">
    <i class="fa fa-print"></i>
  </button>
</template>

<script>
export default {
props:['id','pour','duplicata'],
methods: {
    async imprimer(){
        await axios({
        url: `/geststock/${this.pour}/imprimer/${this.id}`,
        method: "GET",
        responseType: "blob", // important
        })
        .then((response) => {
        var blob = new Blob([response.data], {
            type: "application/pdf",
        });
        var url = window.URL.createObjectURL(blob);
        window.open(url);

        })
        .catch(function (error) {

        });


        location.reload();
    },


},
}
</script>

<style>

</style>
