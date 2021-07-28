<template>
  <transition name="fade">
    <div class="modal-overlay">
      <div class="modal">
        <!-- MODAL HEADER -->
        <header class="modal__header">
          <span class="modal__close">
            <i class="fas fa-times" @click="toggleModal"></i>
          </span>
          <slot name="header">
          </slot>
        </header>
        <!-- MODAL BODY -->
        <div class="modal__body">
          <slot name="body">Modal body</slot>
        </div>
        <!-- MODAL FOOTER -->
        <footer class="modal__footer">
          <slot name="footer"></slot>
        </footer>
      </div>
    </div>
  </transition>
</template>

<script>
export default {
  name: 'Modal',
  methods: {
    toggleModal() {
      this.$emit('toggle-modal');
    }
  }
}
</script>
