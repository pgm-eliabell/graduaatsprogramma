<template>
  <div class="bg-gray-900 item-card p-4 border border-gray-300 rounded">
    <h2 class="text-xl font-bold mb-2">Item Card</h2>

    <label class="block mb-1">Title</label>
    <input 
      v-model="title" 
      class="border p-2 w-full mb-2" 
      placeholder="Enter item title"
    />

    <label class="block mb-1">Description</label>
    <textarea
      v-model="description"
      class="border p-2 w-full mb-2"
      rows="3"
      placeholder="Enter description here"
    ></textarea>
  </div>
</template>

<script>
export default {
  name: "item_card",
  props: {
    content: {
      type: Object,
      default: () => ({})
    }
  },
  data() {
    return {
      // Initialize from content if it exists
      title: this.content.title || "",
      description: this.content.description || ""
    };
  },
  methods: {
    // The parent calls getData() to gather final fields
    getData() {
      return {
        title: this.title,
        description: this.description
      };
    }
  }
};
</script>
