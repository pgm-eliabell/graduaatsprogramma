<template>
  <div class="page-builder">
    <h1>Craft Your Own Page</h1>
    <div class="blocks">
      <div v-for="(block, index) in blocks" :key="index" class="block">
        <div v-if="!block.component">
          <button @click="selectComponent(index)">Add Component</button>
        </div>
        <div v-else>
          <component :is="block.component" />
        </div>
      </div>
    </div>
    <div v-if="showComponentSelector" class="component-selector">
      <h2>Select a Component</h2>
      <ul>
        <li v-for="(component, name) in components" :key="name">
          <button @click="addComponent(name)">{{ name }}</button>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import GalleryCard from "@/components/portfolioComponents/GalleryCard.vue";
import HeroSection from "@/components/portfolioComponents/HeroSection.vue";
import ItemCard from "@/components/portfolioComponents/ItemCard.vue";
import SocialLinks from "@/components/portfolioComponents/SocialLinks.vue";
import SpotifyEmbed from "@/components/portfolioComponents/SpotifyEmbed.vue";
import VideoEmbed from "@/components/portfolioComponents/VideoEmbed.vue";

export default {
  data() {
    return {
      blocks: [{ component: null }, { component: null }, { component: null }],
      showComponentSelector: false,
      selectedBlockIndex: null,
      components: {
        HeroSection,
        GalleryCard,
        ItemCard,
        SocialLinks,
        SpotifyEmbed,
        VideoEmbed,
      },
    };
  },
  methods: {
    selectComponent(index) {
      this.selectedBlockIndex = index;
      this.showComponentSelector = true;
    },
    addComponent(name) {
      this.blocks[this.selectedBlockIndex].component = this.components[name];
      this.showComponentSelector = false;
    },
  },
};
</script>

<style scoped>
.page-builder {
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
}

.blocks {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.block {
  border: 1px dashed #ccc;
  padding: 20px;
  text-align: center;
}

.component-selector {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.8);
  color: white;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.component-selector ul {
  list-style: none;
  padding: 0;
}

.component-selector li {
  margin: 10px 0;
}

.component-selector button {
  padding: 10px 20px;
  font-size: 16px;
}
</style>
