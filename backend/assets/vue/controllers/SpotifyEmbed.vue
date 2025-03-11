<template>
  <div class="bg-black text-white p-4 rounded">
    <!-- Textarea for full Spotify embed code -->
    <label class="block mb-1 font-semibold">Paste Spotify Embed Code</label>
    <textarea
      v-model="spotifyEmbedCode"
      rows="5"
      class="w-full mb-4 p-2 bg-gray-700 rounded"
      placeholder="Paste your Spotify embed iframe here"
    ></textarea>

    <!-- Display embed preview -->
    <div v-if="spotifyEmbedCode" v-html="spotifyEmbedCode"></div>
  </div>
</template>

<script>
export default {
  name: "SpotifyEmbed",
  data() {
    return {
      spotifyEmbedCode: "",
    };
  },
};
</script>