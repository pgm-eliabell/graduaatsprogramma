<template>
    <div class="gallery-card p-4 border border-blue-300 rounded bg-white">
      <h2 class="text-xl font-bold mb-2 text-blue-600">Gallery Card</h2>
      <div class="grid grid-cols-2 gap-2">
        <div
          v-for="(filename, idx) in content.images || []"
          :key="idx"
          class="overflow-hidden"
        >
          <img
            :src="getImageUrl(filename)"
            alt="Gallery Image"
            class="object-cover w-full h-32 rounded"
          />
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: "ShowGalleryCard",
    props: {
      content: {
        type: Object,
        default: () => ({})
      }
    },
    methods: {
      getImageUrl(filename) {
        return `/uploads/gallery/${filename}`;
      }
    }
  };
  </script>
  