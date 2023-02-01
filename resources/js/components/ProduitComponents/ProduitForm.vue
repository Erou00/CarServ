<template>
  <tr  @click="acteur()">
    <th>
      <input
        type="text"
        class="form-control inputForm"
        placeholder="id"
        id="id"
        :value="pr.id"
        disabled
      />
    </th>

    <td>
      <select
        class="form-control"
        v-model="produitForm.categorie_id"
        :class="{ inputForm: updateProduit == false }"
        :disabled="updateProduit == false"
      >
        <option
          v-for="categorie in categories"
          :key="categorie.id"
          :value="categorie.id"
          :selected="categorie.id === pr.categorie_id"
        >
          {{ categorie.nom }}
        </option>
      </select>

      <span v-if="$v.produitForm.categorie_id.$error" class="text-danger">
        le champs est obligatoire
      </span>
    </td>

    <td>
      <select
        class="form-control"
        v-model="produitForm.sous_categorie_id"
        :class="{ inputForm: updateProduit == false }"
        :disabled="updateProduit == false"
      >
        <option
          v-for="sc in filtredSousCategories"
          :key="sc.id"
          :value="sc.id"
          :selected="sc.id === pr.sous_categorie_id"
        >
          {{ sc.nom }}
        </option>
      </select>
      <span v-if="$v.produitForm.sous_categorie_id.$error" class="text-danger">
        le champs est obligatoire
      </span>
    </td>

    <td>
      <select
        class="form-control"
        v-model="produitForm.marque_id"
        :class="{ inputForm: updateProduit == false }"
        :disabled="updateProduit == false"
      >
        <option
          v-for="m in filtredMarques"
          :key="m.id"
          :value="m.id"
          :selected="m.id === pr.marque_id"
        >
          {{ m.nom }}
        </option>
      </select>
      <span v-if="$v.produitForm.marque_id.$error" class="text-danger">
        le champs est obligatoire
      </span>
    </td>

    <td>
      <textarea
        name=""
        id="designation"
        class="form-control"
        :class="{ inputForm: updateProduit == false }"
        :disabled="updateProduit == false"
        v-model="produitForm.designation"
        placeholder="designation"
      ></textarea>
      <span v-if="$v.produitForm.designation.$error" class="text-danger">
        le champs est obligatoire
      </span>
    </td>

    <td>
      <input
        type="number"
        class="form-control"
        v-model="produitForm.prix_unitaire"
        placeholder="prix unitaire"
        id="prix"
        :class="{ inputForm: updateProduit == false }"
        :disabled="updateProduit == false"
      />
      <span v-if="$v.produitForm.prix_unitaire.$error" class="text-danger">
        le champs est obligatoire
      </span>
    </td>

    <td>
      <select
        class="form-control"
        v-model="produitForm.devise_id"
        :class="{ inputForm: updateProduit == false }"
        :disabled="updateProduit == false"
      >
        <option
          v-for="d in devises"
          :key="d.id"
          :value="d.id"
          :selected="d.id === pr.devise_id"
        >
          {{ d.code }}
        </option>
      </select>
      <span v-if="$v.produitForm.devise_id.$error" class="text-danger">
        le champs est obligatoire
      </span>
    </td>

    <td>
      <select
        class="form-control"
        v-model="produitForm.unite_reglementaire_id"
        :class="{ inputForm: updateProduit == false }"
        :disabled="updateProduit == false"
      >
        <option
          v-for="ur in unitereglementaires"
          :key="ur.id"
          :value="ur.id"
          :selected="ur.id === pr.unite_reglementaire_id"
        >
          {{ ur.code }}
        </option>
      </select>
      <span
        v-if="$v.produitForm.unite_reglementaire_id.$error"
        class="text-danger"
      >
        le champs est obligatoire
      </span>
    </td>

    <td>
      <input
        type="number"
        v-model="produitForm.stock_min"
        class="form-control"
        id="stock_min"
        :class="{ inputForm: updateProduit == false }"
        :disabled="updateProduit == false"
      />
      <span v-if="$v.produitForm.stock_min.$error" class="text-danger">
        le champs est obligatoire
      </span>
    </td>

    <td>
      <select
        class="form-control"
        v-model="produitForm.active"
        :class="{ inputForm: updateProduit == false }"
        :disabled="updateProduit == false"
      >
        <option value="1" :selected="pr.active == 1">Oui</option>
        <option value="0" :selected="pr.active == 0">Non</option>
      </select>
      <span v-if="$v.produitForm.active.$error" class="text-danger">
        le champs est obligatoire
      </span>
    </td>

    <td>
      <select
        class="form-control"
        v-model="produitForm.groupe_id"
        :class="{ inputForm: updateProduit == false }"
        :disabled="updateProduit == false"
      >
        <option
          v-for="g in groupes"
          :key="g.id"
          :value="g.id"
          :selected="g.id === pr.groupe_id"
        >
          {{ g.nom }}
        </option>
      </select>
    </td>

    <td style="display: flex;">
      <!-- <button
        class="btn btn-warning btn-sm"
        v-show="!show"
        style="margin-right:2px;"
        @click="updateProduitSend(pr.id)"

      >
        <i class="fa fa-edit"></i>{{ updateProduit ? "Save" : "" }}
      </button> -->
      <!-- <button
        class="btn btn-danger btn-sm"
        @click="deleteProduit(pr.id)"
        v-show="!show"
      >
        <i class="fa fa-trash">{{""}}</i>
      </button> -->

      <svg
        v-show="show"
        @click="deleteFieldForm()"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 24 24"
        width="35"
        height="35"
        class="ml-2 cursor-pointer"
      >
        <path fill="none" d="M0 0h24v24H0z" />
        <path
          fill="#EC4899"
          d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm0-9.414l2.828-2.829 1.415 1.415L13.414 12l2.829 2.828-1.415 1.415L12 13.414l-2.828 2.829-1.415-1.415L10.586 12 7.757 9.172l1.415-1.415L12 10.586z"
        />
      </svg>
    </td>
  </tr>


</template>

<script>
import { required, minLength } from "vuelidate/lib/validators";

export default {
  props: [
    "show",
    "id",
    "produit",
    "categories",
    "marques",
    "souscategories",
    "devises",
    "unitereglementaires",
    "index",
    "groupes",

  ],

  data() {
    return {
      opened: [],
      pr: this.produit,
      produitForm: {
        categorie_id: this.produit.categorie_id,
        sous_categorie_id: this.produit.sous_categorie_id,
        marque_id: this.produit.marque_id,
        designation: this.produit.designation,
        prix_unitaire: this.produit.prix_unitaire,
        devise_id: this.produit.devise_id,
        unite_reglementaire_id: this.produit.unite_reglementaire_id,
        stock_min: this.produit.stock_min,
        active: this.produit.active,
        groupe_id: this.produit.groupe_id,
      },
      updateProduit: this.show,
    };
  },
  computed: {
    prod() {
      return this.$store.getters.productById(this.id);
    },
    isAllValide() {
      return this.$store.getters.isAllValide;
    },
    filtredSousCategories() {
      return this.souscategories.filter((sc) => {
        return sc.categorie_id == this.produitForm.categorie_id;
      });
    },
    filtredMarques() {
      return this.marques.filter((m) => {
        return m.sous_categorie_id == this.produitForm.sous_categorie_id;
      });
    },
  },

  validations() {
    return {
      produitForm: {
        categorie_id: { required },
        sous_categorie_id: { required },
        marque_id: { required },
        designation: { required, minLength: minLength(3) },
        prix_unitaire: { required },
        stock_min: { required },
        devise_id: { required },
        unite_reglementaire_id: { required },
        active: { required },
      },
    };
  },
  watch: {
    isAllValide(newVal) {
      this.handelChange();
    },
  },

  methods: {
    handelChange() {
      this.$v.$touch();
      if (!this.$v.$invalid) {
        this.$store.dispatch("isValide", { statut: true });
        if (!this.$store.getters.produitsList.includes(this.produitForm)) {
          this.$store.dispatch("AddPoduitToList", {
            produit: this.produitForm,
          });
        }
      } else {
        this.$store.dispatch("isValide", { statut: false });
      }
    },

    // updateProduitSend(id) {
    //   this.$v.$touch();
    //   if (this.updateProduit) {
    //     if (!this.$v.$error) {
    //       axios
    //         .put("/geststock/produits/update-produit/" + id, this.produitForm)
    //         .then((response) => {
    //           this.$emit("displayData");
    //         });
    //       return (this.updateProduit = false);
    //     } else {
    //       console.log("error");
    //     }
    //   }

    //   this.updateProduit = true;
    // },

    // deleteProduit(id) {
    //   //this.$store.dispatch("deleteProduit", id);
    //   this.$confirm(
    //     {
    //       message: `Êtes-vous sûr?`,
    //       button: {
    //         no: 'Non',
    //         yes: 'Oui'
    //       },
    //       /**
    //       * Callback Function
    //       * @param {Boolean} confirm
    //       */
    //       callback: confirm => {
    //         if (confirm) {
    //           // ... do something
    //           this.$store.dispatch("deleteProduit", id);
    //         }
    //       }
    //     }
    //   )
    // },

    deleteFieldForm() {
      this.$store.dispatch("rProduitByInde", { index: this.index });
    },

    acteur(){
        if(!this.show){
            this.$emit("acteur",this.pr);
        }
    },

  },

  mounted() {

  },
};
</script>

<style>
.inputForm {
  border: none;
  appearance: none;
}

.inputForm[disabled] {
  background-color: white;
}

</style>
