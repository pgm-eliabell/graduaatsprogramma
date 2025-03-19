<template>
    <div class="social-links p-4 bg-blue-50 rounded">
      <h2 class="text-xl font-bold mb-2">Social Links</h2>
      <ul class="space-y-1">
        <li v-if="content.twitter">
          <a :href="content.twitter" class="text-blue-600" target="_blank">
            Twitter
          </a>
        </li>
        <li v-if="content.facebook">
          <a :href="content.facebook" class="text-blue-600" target="_blank">
            Facebook
          </a>
        </li>
        <li v-if="content.instagram">
          <a :href="content.instagram" class="text-blue-600" target="_blank">
            Instagram
          </a>
        </li>
      </ul>
    </div>
  </template>
  
  <script>
  export default {
    name: "ShowSocialLinks",
    props: {
      content: {
        type: Object,
        default: () => ({})
      }
    }
  };
  </script>
  