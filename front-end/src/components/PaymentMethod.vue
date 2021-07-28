<template>
  <section class="section payment">
    <header class="section__header">
      <i class="section__icon fas fa-money-check"></i>
      <h3 class="section__title">{{ title }}</h3>
    </header>
    <div class="radio">
      <transition name="fade">
        <div class="custom-radio" v-if="delivery !== 'DPD - pobranie'">
          <div class="custom-radio__item">
            <input 
              class="custom-radio__input"
              type="radio"
              v-model="paymentMethod" 
              :value="'PayU'" 
              id="radio-4"
              @click="onClick" 
            />
            <span class="custom-radio__mark">
            </span>
          </div>
          <label class="custom-radio__label" for="radio-4">
            <img class="custom-radio__graphic" src="../assets/payu-logo.png" alt="PayU" />
            <p class="custom-radio__text">PayU</p>
          </label>
        </div>
      </transition>
      <div class="custom-radio">
        <div class="custom-radio__item">
          <input 
            class="custom-radio__input"
            type="radio"
            v-model="paymentMethod" 
            :value="'Pobranie'" 
            id="radio-5"
            @click="onClick" 
          />
          <span class="custom-radio__mark">
          </span>
        </div>
        <label class="custom-radio__label" for="radio-5">
          <img class="custom-radio__graphic" src="../assets/wallet-2.png" alt="Pobranie" />
          <p class="custom-radio__text">Płatność przy odbiorze</p>
        </label>
      </div>
      <transition name="fade">
        <div class="custom-radio" v-if="delivery !== 'DPD - pobranie'">
          <div class="custom-radio__item">
            <input 
              class="custom-radio__input"
              type="radio"
              v-model="paymentMethod" 
              :value="'Przelew'" 
              id="radio-6"
              @click="onClick" 
            />
            <span class="custom-radio__mark">
            </span>
          </div>
          <label class="custom-radio__label" for="radio-6">
            <img class="custom-radio__graphic" src="../assets/druk.png" alt="Przelew" />
            <p class="custom-radio__text">Przelew bankowy - zwykły</p>
          </label>
        </div>
      </transition>
    </div>
    <!-- SHOW ERROR MESSAGE WHEN USER DIDN'T SELECT PAYMENT METHOD -->
    <template v-if="paymentError">
      <div class="message">
        <p class="message__text message__text--danger">
          Proszę wybrać metodę płatności!
        </p>
      </div>
    </template>
    <!-- DISCOUNT BUTTON -->
    <div>
      <button type="button" @click="toggleModal" class="btn btn__secondary">Dodaj kod rabatowy</button>
    </div>
    <!-- DISCOUNT FORM WINDOW -->
    <Modal
      modalName="payment-modal"
      v-if="modalPayment"
      @toggle-modal="toggleModal"
    >
      <template #body>
        <form @submit="onSubmit">
          <FormulateInput
            type="text"
            placeholder="Wpisz kod rabatowy"
            v-model="discountCode"
            class="discount-input"
          />
          <template v-if="discountUsed">
            <div v-if="discountUsed" class="message">
              <p class="message__text message__text--danger">
                Kod rabatowy można wykorzystać tylko raz!
              </p>
            </div>
          </template>
          <template v-else>
            <button 
              type="submit" 
              class="btn btn__primary"
            >
              Zatwierdź
            </button>
          </template>
        </form>
      </template>
    </Modal>
  </section>
</template>

<script>

import Modal from './Modal';

export default {
  name: 'PaymentMethod',
  props: {
    title: String,
    delivery: String,
    modalPayment: Boolean,
    paymentError: Boolean,
    discountUsed: Boolean
  },
  components: {
    Modal
  },
  data() {
    return {
      paymentMethod: '',
      discountCode: '',
      show: false
    }
  },
  methods: {
    onClick(e) {
      this.paymentMethod = e.target.value;
      this.$emit('set-method');
    },
    onSubmit(e) {
      e.preventDefault();
      this.$emit('submit-discount');
      this.toggleModal();
    },
    toggleModal() {
      this.$emit('toggle-modal', 'payment-modal');
      this.discountCode = '';
    },
    resetPayment() {
      this.paymentMethod = ''
    }
  }
}
</script>