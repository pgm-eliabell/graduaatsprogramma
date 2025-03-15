<template>
  <div
    class="relative w-full h-64 bg-gray-800 text-white p-6 rounded-lg flex items-center"
    :style="backgroundImageStyle"
  >
    <!-- Left column: Profile Image -->
    <div class="w-1/4 h-full flex items-center justify-center">
      <label class="cursor-pointer">
        <div class="w-24 h-24 bg-gray-700 overflow-hidden">
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

    <!-- Right column: Name, Occupation, Description -->
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

    <!-- Background Image -->
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
  name: "hero_section",
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
        return `
          background-image: url('${this.backgroundImagePreview}');
          background-size: cover;
          background-position: center;
        `;
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

    /**
     * This is the crucial method the parent calls via child.getData().
     * We'll store everything in one object. 
     * For small images, you can embed them as base64. 
     * For large, see notes below.
     */
    getData() {
      return {
        name: this.name,
        occupation: this.occupation,
        description: this.description,
        // We store the images as "preview" data URLs for now:
        profileImage: this.profileImagePreview || null,
        backgroundImage: this.backgroundImagePreview || null,
      };
    },
  },
};
</script>
