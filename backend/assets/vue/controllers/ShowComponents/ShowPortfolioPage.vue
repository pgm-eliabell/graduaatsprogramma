<template>
    <div class="show-portfolio-page p-4  min-h-screen">
      <h2 class="text-2xl font-bold mb-4">Show Portfolio (Mount Check)</h2>
  
      <div v-if="blocks.length === 0" class="text-gray-500">
        No components found.
      </div>
  
      <!-- Render each block -->
      <div
        v-for="(block, index) in blocks"
        :key="index"
        class="mb-8"
      >
        <component
          :is="componentMap[block.type] || DefaultDisplay"
          :content="block.content"
        />
      </div>
    </div>
  </template>
  
  <script>
  import HeroSection from "./HeroSection.vue";
  import GalleryCard from "./GalleryCard.vue";
  import ItemCard from "./ItemCard.vue";
  import SocialLinks from "./SocialLinks.vue";
  import VideoEmbed from "./VideoEmbed.vue";
  
  const DefaultDisplay = { /* ... */ };
  
  export default {
    name: "ShowPortfolioPage",
    props: {
      initialBlocks: { type: Array, default: () => [] },
    },
    data() {
      return {
        blocks: [],
        componentMap: {
          hero_section: HeroSection,
          gallery_card: GalleryCard,
          item_card: ItemCard,
          social_links: SocialLinks,
          video_embed: VideoEmbed,
        },
      };
    },
    mounted() {
  console.log("PortfolioPage MOUNTED!", this.initialBlocks);
  this.blocks = this.initialBlocks;
}
  };
  </script>