<template>
    <div class="video-embed bg-gray-100 p-4 border rounded">
      <h2 class="text-xl font-bold mb-2">Video Embed</h2>
      <div v-if="content.embed_code" v-html="content.embed_code"></div>
    </div>
  </template>
  
  <script>
  export default {
    name: "VideoEmbed",
    props: {
      content: {
        type: Object,
        default: () => ({})
      }
    }
  };
  </script>
  