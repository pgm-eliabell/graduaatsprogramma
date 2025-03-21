<template>
    <div class="video-container">
      <iframe :src="videoUrl" frameborder="0" allowfullscreen></iframe>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      videoUrl: String
    }
  };
  </script>
  
  <style scoped>
  .video-container iframe {
    width: 100%;
    height: 300px;
    border-radius: 8px;
  }
  </style>
  