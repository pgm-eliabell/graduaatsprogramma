<template>
  <div class="post">
    <p>this is the testcomponent</p>
    <div v-if="loading" class="loading">Loading...</div>

    <div v-if="error" class="error">{{ error }}</div>

    <div v-if="cars.length" class="content">
      <h2>Cars List</h2>
      <ul>
        <li v-for="car in cars" :key="car.id">
          {{ car.name }} - {{ car.year }} - {{ car.color }} - ${{ car.price }}
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
// import axios from 'axios'
import { ref, onMounted } from 'vue'
import { useFetch } from '@vueuse/core'

const loading = ref(false)
const cars = ref([])
const error = ref(null)

onMounted(async () => {
  loading.value = true
  const { data, error: fetchError } = await useFetch('http://127.0.0.1:8001/api/cars').json()
console.log(data.value)
  if (fetchError.value) {
    error.value = fetchError.value.message
  } else {
    cars.value = data.value
  }

  loading.value = false
})
</script>

<style scoped>
.loading {
  color: blue;
}

.error {
  color: red;
}

.content {
  margin-top: 20px;
}
</style>