<template>
  <div class="bg-gray-900 hero-section p-4 border border-gray-300 rounded">
    <h2 class="text-xl font-bold mb-2 text-white">Hero Section</h2>

    <!-- Profile Image Uploader -->
    <label class="block mb-2 font-semibold text-white">Profile Image</label>
    <input 
      type="file" 
      @change="onProfileImageChange" 
      class="mb-4 w-full text-sm"
    />
    <!-- Display profile image preview if available -->
    <div v-if="localContent.profileImage" class="mb-4">
      <img 
        :src="localContent.profileImage" 
        alt="Profile Preview" 
        class="border border-gray-400 w-32 h-32 object-cover rounded"
      />
    </div>

    <!-- Background Image Uploader -->
    <label class="block mb-2 font-semibold text-white">Background Image</label>
    <input 
      type="file" 
      @change="onBackgroundImageChange" 
      class="mb-4 w-full text-sm"
    />
    <!-- Display background image preview if available -->
    <div v-if="localContent.backgroundImage" class="mb-4">
      <img 
        :src="localContent.backgroundImage" 
        alt="Background Preview" 
        class="border border-gray-400 w-64 h-32 object-cover rounded"
      />
    </div>

    <!-- Name, Occupation, Description fields -->
    <label class="block mb-1 text-white">Name</label>
    <input 
      v-model="localContent.name" 
      class="border p-2 w-full mb-2" 
      placeholder="Enter your name"
    />

    <label class="block mb-1 text-white">Occupation</label>
    <input 
      v-model="localContent.occupation" 
      class="border p-2 w-full mb-2"
      placeholder="Your occupation"
    />

    <label class="block mb-1 text-white">Description</label>
    <textarea
      v-model="localContent.description"
      class="border p-2 w-full"
      rows="3"
      placeholder="Enter description here"
    ></textarea>
  </div>
</template>

<script>
export default {
  name: "HeroSection",
  props: {
    // Expect an object from the parent; if not provided, default to an empty object.
    content: {
      type: Object,
      default: () => ({})
    }
  },
  data() {
    return {
      // Create a local copy so we can mutate it.
      localContent: { ...this.content }
    };
  },
  methods: {
    async onProfileImageChange(e) {
      const file = e.target.files[0];
      if (!file) return;
      
      const formData = new FormData();
      formData.append("file", file);

      try {
        const response = await fetch("/api/uploads/hero", {
          method: "POST",
          body: formData,
        });
        if (!response.ok) {
          console.error("Upload failed:", await response.text());
          return;
        }
        const result = await response.json();
        // Save the final URL into localContent (adjust the folder if needed)
        this.localContent.profileImage = "/uploads/heroImages/" + result.filename;
      } catch (err) {
        console.error("Profile image upload error:", err);
      }
    },
    async onBackgroundImageChange(e) {
      const file = e.target.files[0];
      if (!file) return;
      
      const formData = new FormData();
      formData.append("file", file);

      try {
        const response = await fetch("/api/uploads/hero", {
          method: "POST",
          body: formData,
        });
        if (!response.ok) {
          console.error("Upload failed:", await response.text());
          return;
        }
        const result = await response.json();
        // Save the final URL into localContent
        this.localContent.backgroundImage = "/uploads/heroImages/" + result.filename;
      } catch (err) {
        console.error("Background image upload error:", err);
      }
    },
    /**
     * Returns the local data that will be posted to the backend.
     */
    getData() {
      return this.localContent;
    }
  }
};
</script>
