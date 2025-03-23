<template>
  <div class="gallery-card p-4 border border-blue-500 rounded">
    <h2 class="text-xl font-bold mb-2 text-blue-500 bg-blue-600">Gallery Card</h2>

    <!-- Preview container (2-column grid) -->
    <div class="mb-2 grid grid-cols-2 gap-2">
      <div
        v-for="(filename, index) in images"
        :key="index"
        class="overflow-hidden"
      >
        <img
          :src="getImageUrl(filename)"
          alt="Gallery Image"
          class="object-cover w-full h-32 rounded"
        />
      </div>
    </div>

    <!-- File input for multiple images -->
    <input
      type="file"
      accept="image/*"
      multiple
      @change="onImageChange"
      class="block text-blue-500"
    />
  </div>
</template>

<script>
export default {
  name: "gallery_card",
  props: {
    content: {
      type: Object,
      default: () => ({})
    }
  },
  data() {
    return {
      // Use existing images if available
      images: this.content.images || []
    };
  },
  methods: {
    getImageUrl(filename) {
      return `/uploads/gallery/${filename}`;
    },
    async onImageChange(e) {
      const files = Array.from(e.target.files);
      for (const file of files) {
        const formData = new FormData();
        formData.append("file", file);
        try {
          const response = await fetch("/api/uploads/gallery", {
            method: "POST",
            body: formData
          });
          if (!response.ok) {
            throw new Error("Gallery image upload failed");
          }
          const data = await response.json();
          this.images.push(data.filename);
        } catch (err) {
          console.error(err);
          alert("Failed to upload gallery image");
        }
      }
      e.target.value = ""; // Clear so user can upload the same file again if needed
    },
    getData() {
      return {
        images: this.images
      };
    }
  }
};
</script>
