<template>
  <div class="bg-white p-4 border border-gray-300 rounded">
    <h2 class="text-xl font-bold mb-2">Social Links</h2>
    <label class="block mb-1">Twitter:</label>
    <input 
      v-model="twitter" 
      class="border p-2 w-full mb-2" 
      placeholder="https://twitter.com/..." 
    />

    <label class="block mb-1">Facebook:</label>
    <input 
      v-model="facebook" 
      class="border p-2 w-full mb-2" 
      placeholder="https://facebook.com/..." 
    />

    <label class="block mb-1">Instagram:</label>
    <input 
      v-model="instagram" 
      class="border p-2 w-full mb-2" 
      placeholder="https://instagram.com/..." 
    />
  </div>
</template>

<script>
export default {
  name: "social_links",
  props: ["content"],
  data() {
    return {
      twitter: "",
      facebook: "",
      instagram: "",
    };
  },
  mounted() {
    if (this.content) {
      this.twitter = this.content.twitter || "";
      this.facebook = this.content.facebook || "";
      this.instagram = this.content.instagram || "";
    }
  },
  methods: {
    getData() {
      return {
        twitter: this.twitter,
        facebook: this.facebook,
        instagram: this.instagram
      };
    },
  },
};

</script>
