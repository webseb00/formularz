<template>
  <section class="section">
    <header class="section__header">
      <i class="section__icon fas fa-clipboard-list"></i>
      <h3 class="section__title">{{ title }}</h3>
    </header>
      <div class="order">
        <div class="order__summary">
          <div class="order__icon"></div>
          <div class="order__details">
            <div class="order__product">
              <p>Testowy produkt</p>
              <p class="order__price">115,00zł</p>
            </div>
            <div>
              <p class="order__quantity">
                Ilość: 
                <FormulateInput
                  type="number"
                  name="sample"
                  v-model="quantity"
                  min="1"
                  max="6"
                  @change="increaseProduct"
                  :disabled="deliveryCosts === 0"
                  class="order__quantity-input"
                />
              </p>
            </div>
          </div>
        </div>
        <div class="order__info">
          <div>
            <p>Suma częściowa <span>{{ productPrice.toFixed(2) }} zł</span></p>
            <p v-if="deliveryCosts !== 0">Koszty wysyłki <span>{{ deliveryCosts.toFixed(2) }} zł</span></p>
            <p v-if="discountUsed">Rabat: <span>{{ discountSum.toFixed(2) }} zł</span></p>
            <h3>Łącznie <span>{{ !orderTotalCosts ? productPrice.toFixed(2) : orderTotalCosts.toFixed(2) }} zł</span></h3>
          </div>
        </div>
        <div class="order__confirm">
          <FormulateInput
            class="formulate-input-textarea"
            type="textarea"
            v-model="message"
            placeholder="Komentarz"
          /> 
          <div class="order__cta">
            <div class="custom-checkbox">
              <div class="custom-checkbox__item">
                <input 
                  class="custom-checkbox__input"
                  type="checkbox" 
                  id="newsletter"
                >
                <span class="custom-checkbox__mark">
                  <i class="fas fa-check"></i>
                </span>
              </div>  
              <label class="custom-checkbox__label" for="newsletter">Zapisz się, aby otrzymywać newsletter</label>
            </div> 
          </div>
          <div class="order__cta">
            <div class="custom-checkbox">
              <div class="custom-checkbox__item">
                <input 
                  class="custom-checkbox__input"
                  v-model="acceptOrder"
                  type="checkbox" 
                  id="regulations"
                >
                <span class="custom-checkbox__mark">
                  <i class="fas fa-check"></i>
                </span>
              </div>  
              <label class="custom-checkbox__label" for="regulations">Zapoznałam/em się z <a href="#">Regulaminem</a> zakupów</label>
            </div>            
          </div>
          <button 
            type="button" 
            class="btn btn__confirm" 
            @click="confirmOrder"
            :disabled="formSend"
          >
            <template v-if="sending">
              <Loader text="Wysyłanie..." />
            </template>
            <template v-else-if="formSend">
              Zamówienie wysłane!
            </template>
            <template v-else>
              Potwierdź zakup
            </template>
          </button>
        </div>
    </div>
  </section>
</template>

<script>

import Loader from './Loader';

export default {
  name: 'Summary',
  props: {
    title: String,
    deliveryCosts: Number,
    orderTotalCosts: Number,
    sending: Boolean,
    discountUsed: Boolean,
    discountSum: Number,
    formSend: Boolean
  },
  components: {
    Loader
  },
  data() {
    return {
      message: '',
      acceptOrder: false,
      productPrice: 115,
      quantity: 1
    }
  },
  methods: {
    increaseProduct() {
      this.$emit('calculate-total', this.quantity);
    },
    confirmOrder() {
      // if user clicked confirm button during sending, stop executing the function 
      if(this.sending) { return false; }

      if(!this.acceptOrder) {
        alert('Proszę zaakceptować regulamin zakupów!');
        return false;
      }

      this.$emit('confirm-order');
      this.message = '';
    }
  }
}
</script>