<template>
  <div class="w-full max-w-xl mx-auto bg-black text-white p-4 rounded">
    <!-- Carousel -->
    <div class="relative flex items-center justify-center h-64 border border-gray-700 rounded mb-4">
      <button
        v-if="currentIndex > 0"
        @click="prevImage"
        class="absolute left-2 text-xl bg-gray-800 px-2 py-1 rounded hover:bg-gray-700"
      >
        ‹
      </button>
      <img
        v-if="images.length"
        :src="images[currentIndex]"
        alt="Carousel Image"
        class="max-h-full max-w-full object-contain"
      />
      <button
        v-if="currentIndex < images.length - 1"
        @click="nextImage"
        class="absolute right-2 text-xl bg-gray-800 px-2 py-1 rounded hover:bg-gray-700"
      >
        ›
      </button>
    </div>

    <div>
      <button
        class="flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded"
        @click="triggerFileInput"
      >
        <span class="mr-2 text-2xl font-bold">+</span>
        Add Images
      </button>
      <input
        ref="fileInput"
        type="file"
        multiple
        accept="image/*"
        class="hidden"
        @change="onImagesChange"
      />
    </div>
  </div>
</template>

<script>
export default {
  name: "GalleryCard",
  data() {
    return {
      images: [],
      currentIndex: 0,
    };
  },
  methods: {
    triggerFileInput() {
      this.$refs.fileInput.click();
    },
    onImagesChange(e) {
      const files = Array.from(e.target.files || []);
      // Keep logic to limit images to 30
      if (files.length + this.images.length > 30) {
        alert("You can upload a maximum of 30 images.");
        return;
      }
      files.forEach((file) => {
        const url = URL.createObjectURL(file);
        this.images.push(url);
      });
    },
    nextImage() {
      if (this.currentIndex < this.images.length - 1) {
        this.currentIndex++;
      }
    },
    prevImage() {
      if (this.currentIndex > 0) {
        this.currentIndex--;
      }
    },
  },
};
</script>

<style scoped>
/* Customize if needed */
</style>