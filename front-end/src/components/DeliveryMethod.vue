<template>
  <section class="section">
    <header class="section__header">
      <i class="section__icon fas fa-truck-moving"></i>
      <h3 class="section__title">{{ title }}</h3>
    </header>
    <div class="radio">
      <div class="custom-radio">
        <div class="custom-radio__wrapper">
          <div class="custom-radio__item">
            <input 
              class="custom-radio__input"
              type="radio" 
              :value="'InPost - paczkomat'"
              v-model="deliveryMethod" 
              data-cost="10.99"
              id="radio-1"
              @click="onClick" 
            />
            <span class="custom-radio__mark">
            </span>
          </div>
          <label class="custom-radio__label" for="radio-1">
            <img class="custom-radio__graphic" src="../assets/inpost-logo.png" alt="InPost" />
            <p class="custom-radio__text">Paczkomaty 24/7</p>
          </label>
        </div>
        <p class="custom-radio__price">
          <b>10.99zł</b>
        </p>
      </div>
      <div class="custom-radio">
        <div class="custom-radio__wrapper">
          <div class="custom-radio__item">
            <input 
              class="custom-radio__input"
              type="radio" 
              :value="'DPD - dostawa'"
              v-model="deliveryMethod" 
              data-cost="18"
              id="radio-2"
              @click="onClick" 
            />
            <span class="custom-radio__mark">
            </span>
          </div>
          <label class="custom-radio__label" for="radio-2">
            <img class="custom-radio__graphic" src="../assets/dpd-logo.png" alt="DPD Logo" />
            <p class="custom-radio__text">Kurier DPD</p>
          </label>
        </div>
        <p class="custom-radio__price">
          <b>18.00zł</b>
        </p>
      </div>
      <div class="custom-radio">
        <div class="custom-radio__wrapper">
          <div class="custom-radio__item">
            <input 
              class="custom-radio__input"
              type="radio" 
              :value="'DPD - pobranie'"
              v-model="deliveryMethod" 
              data-cost="22"
              id="radio-3"
              @click="onClick" 
            />
            <span class="custom-radio__mark">
            </span>
          </div>
          <label class="custom-radio__label" for="radio-3">
            <img class="custom-radio__graphic" src="../assets/dpd-logo.png" alt="DPD Logo" />
            <p class="custom-radio__text">Kurier DPD - pobranie</p>
          </label>
        </div>
        <p class="custom-radio__price">
          <b>22.00zł</b>
        </p>
      </div>
    </div>
    <!-- SHOW ERROR MESSAGE WHEN USER DIDNT SELECT DELIVERY METHOD -->
    <template v-if="deliveryError">
      <div class="message">
      <p class="message__text message__text--danger">
        Proszę wybrać metodę dostawy!
      </p>
    </div>
    </template>
  </section>
</template>

<script>
export default {
  name: 'DeliveryMethod',
  props: {
    title: String,
    deliveryError: Boolean
  },
  data() {
    return {
      deliveryMethod: '',
      deliveryCost: null
    }
  },
  methods: {
    onClick(e) {
      const cost = Number(e.target.dataset.cost);

      this.deliveryCost = cost;
      this.deliveryMethod = e.target.value;
      this.$emit('delivery-method');
    },
    resetDelivery() {
      this.deliveryMethod = '';
      this.deliveryCost = null;
    }
  }
}
</script>