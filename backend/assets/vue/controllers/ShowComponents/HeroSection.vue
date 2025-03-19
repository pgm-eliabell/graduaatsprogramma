<template>
  <div class="hero-section p-4 bg-blue-50">
    <!-- Instead of edit fields, show read-only data -->
    <h2 class="text-xl font-bold">Name: {{ content.name }}</h2>
    <p>Occupation: {{ content.occupation }}</p>
    <p>Description: {{ content.description }}</p>
    <img v-if="content.profileImage" :src="content.profileImage" />
  </div>
</template>

<script>
export default {
  name: "HeroSection",
  props: { content: Object }
};
</script>
