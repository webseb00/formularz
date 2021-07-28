<template>
  <div class="confirm">
    <Modal
      v-if="modalConfirm"
      @toggle-modal="toggleModal"
    >
      <template #header>
        <i class="fas fa-check"></i>
        <h3>Dziękujemy za złożenie zamówienia.</h3>
      </template>
      <template #body>
        <h4>Numer Twojego zamówienia: <b>{{ orderNumber }}</b></h4>
        <h4>Zamówiony produkt: <b>{{ product.name }}</b>, sztuk: <b>{{ product.quantity }}</b></h4>
        <h4>Cena produktu: <b>{{ product.price }} zł</b></h4>
        <h4>Cena wysyłki: <b>{{ deliveryCosts }} zł</b></h4>
        <h4>Do zapłaty: <b>{{ orderTotalCosts.toFixed(2) }} zł</b></h4>   
      </template>
    </Modal>
  </div>
</template>

<script>
import Modal from './Modal';

export default {
  name: 'OrderConfirm',
  components: {
    Modal
  },
  props: {
    modalConfirm: Boolean,
    orderNumber: Number,
    orderTotalCosts: Number,
    deliveryCosts: Number,
    product: Object
  },
  methods: {
    toggleModal() {
      this.$emit('toggle-modal', 'confirm-modal');
    }
  }
}
</script>
