<template>
    <div>
      <h1>{{ title }}</h1>
      <p>{{ description }}</p>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        title: 'this is a title for the testtemplate',
        description: 'this is a describe for the testtemplate'
      };
    },
    mounted() {
      axios.get('https://meafolio.ddev.site/api/content')
        .then(response => {
          this.title = response.data.title;
          this.description = response.data.description;
        })
        .catch(error => {
          console.error('Error fetching data:', error);
        });
    }
  };
  </script>
  