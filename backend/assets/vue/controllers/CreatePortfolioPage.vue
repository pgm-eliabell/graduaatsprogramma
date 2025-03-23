<template>
  <div class="page-builder p-8 bg-gray-300 min-h-screen">
    <h2 class="text-4xl font-bold mb-8 text-center text-gray-900">
      Craft Your Own Page
    </h2>

    <div class="blocks space-y-6">
      <div class="flex items-center space-x-4"></div>
      <label for="visibility" class="text-lg font-medium text-gray-700">private:</label>
      <input
        id="visibility"
        type="checkbox"
        v-model="isVisible"
        class="form-checkbox h-5 w-5 text-blue-600"
      />

      <div
        v-for="(block, index) in blocks"
        :key="index"
        class="block p-6 border border-gray-300 rounded-xl shadow-lg bg-white"
      >
        <div v-if="!block.component" class="text-center">
          <button
            @click="selectComponent(index)"
            class="px-5 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-md transition font-semibold"
          >
            Add Component
          </button>
        </div>
        <div v-else class="p-4 rounded-lg bg-gray-50 shadow-sm">
          <component
            :is="block.component"
            :ref="'block_' + index"
            :content="block.content"
          />
        </div>
      </div>
    </div>

    <!-- Save all components -->
    <button
      @click="saveAllComponents"
      class="mt-8 px-6 py-3 bg-green-600 text-white font-semibold rounded shadow transition"
    >
      Save All
    </button>

    <!-- CRUD buttons for portfolio details -->
    <div class="mt-4 flex space-x-4">
      <button
        @click="editPortfolio"
        class="px-6 py-3 bg-blue-600 text-white font-semibold rounded shadow transition"
      >
        Edit Portfolio
      </button>
      <button
        @click="deletePortfolio"
        class="px-6 py-3 bg-red-600 text-white font-semibold rounded shadow transition"
      >
        Delete Portfolio
      </button>
    </div>

    <!-- Component Selector Modal -->
    <div
      v-if="showComponentSelector"
      class="component-selector fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 backdrop-blur-sm"
    >
      <div class="bg-white p-8 rounded-lg shadow-2xl w-full max-w-lg">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">
          Select a Component
        </h2>
        <ul class="space-y-3">
          <li v-for="(comp, name) in components" :key="name">
            <button
              @click="addComponent(name)"
              class="w-full px-4 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 shadow-sm transition"
            >
              {{ name }}
            </button>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
import HeroSection from "./HeroSection.vue";
import GalleryCard from "./GalleryCard.vue";
import ItemCard from "./ItemCard.vue";
import SocialLinks from "./SocialLinks.vue";
import VideoEmbed from "./VideoEmbed.vue";

export default {
  name: "CreatePortfolioPage",
  data() {
    return {
      portfolioId: null, // Holds the portfolio's ID returned from the API
      isVisible: false,
      blocks: [{ component: null }],
      showComponentSelector: false,
      selectedBlockIndex: null,
      components: {
        hero_section: HeroSection,
        gallery_card: GalleryCard,
        item_card: ItemCard,
        social_links: SocialLinks,
        video_embed: VideoEmbed,
      },
    };
  },
  mounted() {
    // Fetch existing portfolio data from /api/portfolios/edit
    fetch("/api/portfolios/edit")
      .then((resp) => {
        if (!resp.ok) throw new Error("Failed to load existing portfolio");
        return resp.json();
      })
      .then((data) => {
        // Assume the API returns an id, visible flag, and portfolioComponents array
        this.portfolioId = data.id || null;
        this.isVisible = data.visible || false;

        if (data.portfolioComponents && data.portfolioComponents.length) {
          data.portfolioComponents.forEach((componentData) => {
            const comp = this.components[componentData.componentType] || null;
            this.blocks.push({
              id: componentData.id,
              type: componentData.componentType,
              component: comp,
              content: componentData.content,
            });
          });
        }
        // Remove the initial empty block if it exists
        if (this.blocks.length > 0 && !this.blocks[0].component) {
          this.blocks.shift();
        }
      })
      .catch((err) => {
        console.error(err);
      });
  },
  methods: {
    selectComponent(index) {
      this.selectedBlockIndex = index;
      this.showComponentSelector = true;
    },
    addComponent(name) {
      this.blocks[this.selectedBlockIndex].component = this.components[name];
      this.blocks[this.selectedBlockIndex].type = name;
      this.showComponentSelector = false;
      this.blocks.push({ component: null });
    },
    saveAllComponents() {
      const blocksPayload = this.blocks
        .filter((b) => b.component)
        .map((b, index) => {
          const child = this.$refs[`block_${index}`]?.[0];
          let type = b.type || child?.$options?.name || "UnknownComponent";
          const content = child?.getData ? child.getData() : {};
          return {
            id: b.id || null,
            type,
            content,
          };
        });

      const payload = {
        blocks: blocksPayload,
        layout_config: {},
        visible: this.isVisible,
      };

      console.log("Sending payload:", JSON.stringify(payload, null, 2));

      fetch("/api/portfolios/save", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload),
      })
        .then((resp) => {
          if (!resp.ok) throw new Error("Failed to save");
          return resp.json();
        })
        .then((data) => {
          alert("All components saved!");
          console.log("Saved response:", data);
        })
        .catch((error) => {
          console.error(error);
          alert("Error saving components");
        });
    },
    async editPortfolio() {
      // Update portfolio details via the controller's edit endpoint
      try {
        const response = await fetch(`/api/portfolios/${this.portfolioId}/edit`, {
          method: "PATCH",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ visible: this.isVisible }),
        });
        if (!response.ok) throw new Error("Failed to edit portfolio");
        const data = await response.json();
        alert("Portfolio updated!");
        console.log("Edit response:", data);
      } catch (error) {
        console.error("Error editing portfolio:", error);
        alert("Error editing portfolio");
      }
    },
    async deletePortfolio() {
      // Remove the portfolio using the controller's delete endpoint
      if (!confirm("Are you sure you want to delete the portfolio?")) return;
      try {
        const response = await fetch(`/api/portfolios/${this.portfolioId}/delete`, {
          method: "DELETE",
        });
        if (!response.ok) throw new Error("Failed to delete portfolio");
        const data = await response.json();
        alert("Portfolio deleted!");
        console.log("Delete response:", data);
      } catch (error) {
        console.error("Error deleting portfolio:", error);
        alert("Error deleting portfolio");
      }
    },
  },
};
</script>