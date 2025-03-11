<template>
  <div class="bg-black text-white p-6 rounded-md w-full">
    <h3 class="text-2xl font-bold mb-4">Add Up to 5 Sections</h3>
    <div
      v-for="(section, index) in sections"
      :key="index"
      class="mb-4 p-4 bg-gray-800 rounded"
    >
      <label class="block mb-1">Title</label>
      <input
        v-model="section.title"
        class="w-full p-2 bg-gray-700 rounded mb-2"
        type="text"
      />
      <label class="block mb-1">Description</label>
      <textarea
        v-model="section.description"
        rows="2"
        class="w-full p-2 bg-gray-700 rounded"
      ></textarea>
      <button
        class="mt-2 px-4 py-2 bg-red-600 hover:bg-red-700 rounded-md"
        @click="removeSection(index)"
      >
        Remove
      </button>
    </div>
    <button
      v-if="sections.length < 5"
      class="px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-md"
      @click="addSection"
    >
      Add Another Section
    </button>
  </div>
</template>

<script>
export default {
  name: "MyRepeatableSections",
  data() {
    return {
      sections: [
        { title: '', description: '' }
      ]
    };
  },
  methods: {
    addSection() {
      if (this.sections.length < 5) {
        this.sections.push({ title: '', description: '' });
      }
    },
    removeSection(index) {
      this.sections.splice(index, 1);
    }
  }
};
</script>

<style scoped>
/* Adjust as needed */
</style>