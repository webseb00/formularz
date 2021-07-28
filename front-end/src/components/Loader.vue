<template>
  <div class="loader">
    <p class="loader__text">{{ text }}</p>
    <i class="loader__icon fas fa-circle-notch"></i>
  </div>
</template>

<script>
export default {
  name: 'Loader',
  props: {
    text: String
  }
}
</script>

