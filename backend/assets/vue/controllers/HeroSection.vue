<template>
  <!-- Main hero container -->
  <div
    class="p-8 flex items-center justify-between rounded shadow-lg mb-6"
    :style="heroStyle"
  >
    <!-- Left Column: Profile Image -->
    <div class="flex flex-col items-center mr-8">
      <div class="w-40 h-40 overflow-hidden rounded-full border-4 border-white shadow-lg">
        <!-- If there's a valid profile image, show it. Otherwise show "No Image" -->
        <img
          v-if="displayProfileImage"
          :src="displayProfileImage"
          alt="Profile"
          class="w-full h-full object-cover"
        />
        <div
          v-else
          class="w-full h-full flex items-center justify-center bg-gray-700 text-gray-300"
        >
          No Image
        </div>
      </div>

      <!-- Profile Image Uploader -->
      <label class="mt-4 font-semibold text-gray-300">Profile Image</label>
      <input type="file" @change="onProfileImageChange" class="text-white" />
    </div>

    <!-- Right Column: Name, Occupation, Description -->
    <div class="flex-1 text-gray-200">
      <h2 class="text-3xl font-bold mb-2">
        Hello, my name is {{ localContent.name || "Your Name" }}
      </h2>
      <p class="text-xl text-pink-400 font-semibold mb-2">
        {{ localContent.occupation || "Your Occupation" }}
      </p>
      <p class="max-w-md leading-relaxed">
        {{ localContent.description || "A short description about yourself..." }}
      </p>

      <!-- Optional: CTA button -->
      <button
        v-if="localContent.ctaLabel"
        class="mt-4 bg-pink-600 text-white px-5 py-2 rounded hover:bg-pink-500"
      >
        {{ localContent.ctaLabel }}
      </button>
    </div>
  </div>

  <!-- STYLE & BACKGROUND CONTROLS BELOW -->
  <div class="p-4 border rounded bg-gray-800 text-gray-200">
    <h3 class="font-semibold mb-2">🎨 Customize Hero Style</h3>

    <!-- Row: BG color & BG image side by side -->
    <div class="flex flex-wrap items-center gap-4 mb-4">
      <!-- Background Color -->
      <div>
        <label class="block font-semibold">Background Color</label>
        <input type="color" v-model="localStyle.backgroundColor" />
      </div>

      <!-- Background Image Upload -->
      <div>
        <label class="block font-semibold">Background Image</label>
        <input type="file" @change="onBackgroundImageChange" class="text-white" />
      </div>
    </div>

    <!-- Text Color & Border Radius -->
    <div class="flex flex-wrap items-center gap-4 mb-4">
      <div>
        <label class="block font-semibold">Text Color</label>
        <input type="color" v-model="localStyle.textColor" />
      </div>

      <div>
        <label class="block font-semibold">Border Radius (px)</label>
        <input type="range" min="0" max="50" v-model="localStyle.borderRadius" />
        <span>{{ localStyle.borderRadius }}px</span>
      </div>
    </div>

    <!-- Editable text fields for user data (Name, Occupation, etc.) -->
    <div class="flex flex-col gap-2">
      <label class="font-semibold">Name</label>
      <input
        v-model="localContent.name"
        class="border p-2 rounded bg-gray-700"
      />

      <label class="font-semibold">Occupation</label>
      <input
        v-model="localContent.occupation"
        class="border p-2 rounded bg-gray-700"
      />

      <label class="font-semibold">Description</label>
      <textarea
        v-model="localContent.description"
        rows="3"
        class="border p-2 rounded bg-gray-700"
      ></textarea>

      <label class="font-semibold">CTA Label</label>
      <input
        v-model="localContent.ctaLabel"
        class="border p-2 rounded bg-gray-700"
        placeholder="e.g. Get In Touch"
      />
    </div>
  </div>
</template>

<script>
export default {
  name: "HeroSection",
  props: {
    // The parent passes in { name, occupation, ... } if already saved
    content: {
      type: Object,
      default: () => ({}),
    },
  },
  data() {
    return {
      localContent: {
        name: this.content.name || "",
        occupation: this.content.occupation || "",
        description: this.content.description || "",
        profileImage: this.content.profileImage || null, // Just the filename
        backgroundImage: this.content.backgroundImage || null, // Just the filename
        ctaLabel: this.content.ctaLabel || "",
      },
      localStyle: {
        textColor:
          (this.content.style && this.content.style.textColor) || "#ffffff",
        backgroundColor:
          (this.content.style && this.content.style.backgroundColor) || "#1f2937",
        borderRadius:
          (this.content.style && this.content.style.borderRadius) || 20,
      },
    };
  },
  computed: {
    // For the profile image, prepend /uploads/heroImages/ if there's a filename
    displayProfileImage() {
      return this.localContent.profileImage
        ? "/uploads/heroImages/" + this.localContent.profileImage
        : null;
    },
    // Same logic for the background
    displayBackgroundImage() {
      return this.localContent.backgroundImage
        ? "/uploads/heroImages/" + this.localContent.backgroundImage
        : null;
    },
    // Combine textColor, background (or color), and borderRadius
    heroStyle() {
      if (this.localContent.backgroundImage) {
        return {
          color: this.localStyle.textColor,
          borderRadius: this.localStyle.borderRadius + "px",
          background: `url(${this.displayBackgroundImage}) center/cover no-repeat`,
        };
      }
      return {
        backgroundColor: this.localStyle.backgroundColor,
        color: this.localStyle.textColor,
        borderRadius: this.localStyle.borderRadius + "px",
      };
    },
  },
  methods: {
    // Handles uploading the profile image and storing only the filename
    async onProfileImageChange(e) {
      const file = e.target.files[0];
      if (!file) return;
      const formData = new FormData();
      formData.append("file", file);

      try {
        const res = await fetch("/api/uploads/hero", {
          method: "POST",
          body: formData,
        });
        if (!res.ok) {
          console.error("Profile image upload failed:", await res.text());
          return;
        }
        const data = await res.json();
        // data.filename => "someFileName.jpg"
        this.localContent.profileImage = data.filename;
      } catch (err) {
        console.error("Profile image upload error:", err);
      }
    },

    // Same approach for the background
    async onBackgroundImageChange(e) {
      const file = e.target.files[0];
      if (!file) return;
      const formData = new FormData();
      formData.append("file", file);

      try {
        const res = await fetch("/api/uploads/hero", {
          method: "POST",
          body: formData,
        });
        if (!res.ok) {
          console.error("Background image upload failed:", await res.text());
          return;
        }
        const data = await res.json();
        this.localContent.backgroundImage = data.filename;
      } catch (err) {
        console.error("Background image upload error:", err);
      }
    },

    // The parent calls getData() to gather final data to send to the server
    getData() {
      return {
        name: this.localContent.name,
        occupation: this.localContent.occupation,
        description: this.localContent.description,
        profileImage: this.localContent.profileImage,    // just filename
        backgroundImage: this.localContent.backgroundImage, // just filename
        ctaLabel: this.localContent.ctaLabel,
        style: {
          textColor: this.localStyle.textColor,
          backgroundColor: this.localStyle.backgroundColor,
          borderRadius: this.localStyle.borderRadius,
        },
      };
    },
  },
};
</script>

<style scoped>
/* Basic styling for a "hero" feel. Adjust as needed. */
</style>
