<template>
  <div class="bg-gray-100 p-4 rounded shadow">
    <label class="block font-semibold mb-2">
      Embed Code
    </label>
    <textarea
      v-model="videoCode"
      rows="4"
      class="w-full p-2 border border-gray-300 rounded"
      placeholder="Paste embed iframe here"
    ></textarea>
    <div class="mt-4" v-html="videoCode"></div>
  </div>
</template>

<script>
export default {
  name: "video_embed", 
  data() {
    return {
      videoCode: "",
    };
  },
  methods: {
    getData() {
      return {
        embed_code: this.videoCode,
      };
    },
  },
};
</script>