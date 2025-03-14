<template>
  <div
    class="relative w-full h-64 bg-gray-800 text-white p-6 rounded-lg flex items-center"
    :style="backgroundImageStyle"
  >
    <div class="w-1/4 h-full flex items-center justify-center">
      <label class="cursor-pointer">
        <div class="w-24 h-24 bg-gray-700  overflow-hidden">
          <img
            v-if="profileImagePreview"
            :src="profileImagePreview"
            alt="Profile"
            class="object-cover h-full w-full"
          />
        </div>
        <input
          type="file"
          accept="image/*"
          class="hidden"
          @change="onProfileImageChange"
        />
      </label>
    </div>

    <div class="w-3/4 pl-6">
      <label class="block mb-1">Name</label>
      <input v-model="name" class="w-full mb-2 p-2 bg-gray-700 rounded" />

      <label class="block mb-1">Occupation</label>
      <input v-model="occupation" class="w-full mb-2 p-2 bg-gray-700 rounded" />

      <label class="block mb-1">Description</label>
      <textarea
  v-model="description"
  rows="2"
  class="w-full p-2 bg-gray-700 rounded"
  style="resize: none;"
></textarea>


    </div>

    <label
      class="absolute top-2 right-2 bg-black bg-opacity-50 px-3 py-2 rounded cursor-pointer"
    >
      Change Background
      <input type="file" accept="image/*" class="hidden" @change="onBackgroundImageChange" />
    </label>
  </div>
</template>

<script>
export default {
  name: "HeroSection",
  data() {
    return {
      name: "",
      occupation: "",
      description: "",
      profileImageFile: null,
      profileImagePreview: null,
      backgroundImageFile: null,
      backgroundImagePreview: null,
    };
  },
  computed: {
    backgroundImageStyle() {
      if (this.backgroundImagePreview) {
        return `background-image: url('${this.backgroundImagePreview}'); background-size: cover; background-position: center;`;
      }
      return "";
    },
  },
  methods: {
    onProfileImageChange(e) {
      this.profileImageFile = e.target.files[0];
      this.profileImagePreview = URL.createObjectURL(this.profileImageFile);
    },
    onBackgroundImageChange(e) {
      this.backgroundImageFile = e.target.files[0];
      this.backgroundImagePreview = URL.createObjectURL(this.backgroundImageFile);
    },
    async saveHeroSection() {
      try {
        const formData = new FormData();
        formData.append("name", this.name);
        formData.append("occupation", this.occupation);
        formData.append("description", this.description);
        if (this.profileImageFile) {
          formData.append("profileImage", this.profileImageFile);
        }
        if (this.backgroundImageFile) {
          formData.append("backgroundImage", this.backgroundImageFile);
        }

        const response = await fetch("/api/portfolios/hero", {
          method: "POST",
          body: formData,
        });
        if (!response.ok) {
          throw new Error("Failed to save hero section");
        }
        alert("Hero section saved!");
      } catch (error) {
        console.error(error);
      }
    },
  },
};
</script>

