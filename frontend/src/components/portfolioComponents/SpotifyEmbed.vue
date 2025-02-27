<template>
    <iframe
      :src="spotifyUrl"
      width="100%"
      height="80"
      frameborder="0"
      allowtransparency="true"
      allow="encrypted-media">
    </iframe>
  </template>
  
  <script>
  export default {
    props: {
      spotifyUrl: String
    }
  };
  </script>
  