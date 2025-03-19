<template>
    <div class="item-card p-4 border border-gray-300 rounded bg-white">
      <h2 class="text-xl font-bold mb-2">Item Card</h2>
      <p><strong>Title:</strong> {{ content.title }}</p>
      <p><strong>Description:</strong> {{ content.description }}</p>
    </div>
  </template>
  
  <script>
  export default {
    name: "ShowItemCard",
    props: {
      content: {
        type: Object,
        default: () => ({})
      }
    }
  };
  </script>
  