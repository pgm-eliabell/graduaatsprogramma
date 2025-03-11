<template>
  <div class="bg-black text-white p-4 rounded">
    <!-- Textarea for full video embed code -->
    <label class="block mb-1 font-semibold">Paste Spotify/Youtube/... Embeded Code</label>
    <textarea
      v-model="VideoEmbedCode"
      rows="5"
      class="w-full mb-4 p-2 bg-gray-700 rounded"
      placeholder="Paste your Spotify embed iframe here"
    ></textarea>

    <!-- Display embed preview -->
    <div v-if="VideoEmbedCode" v-html="VideoEmbedCode"></div>
  </div>
</template>

<script>
export default {
  name: "VideoEmbed",
  data() {
    return {
      VideoEmbedCode: "",
    };
  },
};
</script>