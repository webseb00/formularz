<template>
  <div class="container">
    <ClientForm 
      title="1. Twoje dane" 
      ref="clientForm" 
      @toggle-modal="toggleModal"
      :modalLogin="modal.modalLogin"
    />
    <DeliveryMethod 
      title="2. Metoda dostawy" 
      ref="deliveryMethod" 
      :deliveryError="error.delivery"
      @delivery-method="setDeliveryMethod" 
    />
    <PaymentMethod 
      title="3. Metoda płatności" 
      ref="paymentMethod" 
      :delivery="order.deliveryMethodSelect" 
      :modalPayment="modal.modalPayment"
      :paymentError="error.payment"
      :discountUsed="order.discount.isUsed"
      @toggle-modal="toggleModal"
      @submit-discount="submitDiscountCode"
      @set-method="setPaymentMethod"
    />
    <Summary 
      title="4. Podsumowanie" 
      ref="summary" 
      :deliveryCosts="order.deliveryCosts"
      :orderTotalCosts="order.orderTotalCosts"
      :sending="order.sending"
      :discountUsed="order.discount.isUsed"
      :discountSum="order.discount.sum"
      :formSend="formSend"
      @confirm-order="confirmOrder" 
      @calculate-total="calculateTotalCosts"
    />
    <!-- Display confirm modal after successful request -->
    <OrderConfirm
      :orderNumber="order.orderNumber"
      :modalConfirm="modal.modalConfirm"
      :product="order.product"
      :deliveryCosts="order.deliveryCosts"
      :orderTotalCosts="order.orderTotalCosts"
      @toggle-modal="toggleModal"
    />
  </div>
</template>

<script>

import ClientForm from './components/ClientForm.vue';
import DeliveryMethod from './components/DeliveryMethod.vue';
import PaymentMethod from './components/PaymentMethod.vue';
import Summary from './components/Summary.vue';
import OrderConfirm from './components/OrderConfirm';
import axios from 'axios';

export default {
  name: 'App',
  components: {
    ClientForm,
    DeliveryMethod,
    PaymentMethod,
    Summary,
    OrderConfirm
  },
  data() {
    return {
      order: {
        newUser: {},
        clientData: {},
        product: {
          name: 'Produkt testowy',
          quantity: 1,
          price: 115
        },
        discount: {
          code: null,
          rate: 10,
          isUsed: false, 
          valid: null,
          sum: 0
        },
        deliveryMethodSelect: '',
        deliveryCosts: 0,
        orderTotalCosts: 0,
        paymentMethod: '',
        userComment: '',
        orderNumber: null,
        sending: false
      },
      modal: {
        modalPayment: false,
        modalLogin: false,
        modalConfirm: false
      },
      error: {
        delivery: false,
        payment: false
      },
      formSend: false
    }
  },
  methods: {
    calculateTotalCosts(number) {
      this.order.product.quantity = number;

      const { quantity, price } = this.order.product;
      const { deliveryCosts } = this.order;

      let total = (quantity * price) + deliveryCosts;
      this.order.orderTotalCosts = total;

      // include discount code if is true
      const { isUsed } = this.order.discount;

      if(isUsed) { this.calculateDiscount(); }
    },
    calculateDiscount() {
      const discount = (this.order.orderTotalCosts * this.order.discount.rate) / 100;

      this.order.discount.sum = discount;
      this.order.orderTotalCosts = this.order.orderTotalCosts - this.order.discount.sum;
    },
    toggleModal(modalName) {
      // Toggle specific modal based on modalName 
      if(modalName === 'payment-modal') {
        this.modal.modalPayment = !this.modal.modalPayment;
      } else if(modalName === 'login-modal') {
        this.modal.modalLogin = !this.modal.modalLogin;
      } else {
        this.modal.modalConfirm = !this.modal.modalConfirm;
      }
    },
    setClientData() {
      const data = this.$refs.clientForm.clientData;
      const setErrorFields = this.$refs.clientForm.setFieldError;

      const keys = Object.keys(data);

      keys.forEach(key => {
        if(data[key] === '') { 
          setErrorFields(key);
        } else {
          this.order.clientData = {...this.order.clientData, [key]: data[key]};
        }
      });     
    },
    setDeliveryMethod() {
      const method = this.$refs.deliveryMethod.deliveryMethod;
      const price = this.$refs.deliveryMethod.deliveryCost;
      
      if(!method) {
        this.error.delivery = true;
        return false;
      } else {
        this.order.deliveryMethodSelect = method;
        this.order.deliveryCosts = price;
        // set error value to default
        this.error.delivery = false;
        // calculate total costs
        this.calculateTotalCosts(this.order.product.quantity);
        return true;
      }
    },
    setPaymentMethod() {
      const paymentMethod = this.$refs.paymentMethod.paymentMethod;
  
      if(!paymentMethod) {
        this.error.payment = true;
        return false;
      } else {
        this.order.paymentMethod = paymentMethod;
        // set error value to default
        this.error.payment = false;
        return true;
      }
    },
    setUserComment() {
      this.order.userComment = this.$refs.summary.message;
    },
    submitDiscountCode() {
      const discountCode = this.$refs.paymentMethod.discountCode;
      // prevent from using the same code twice for one customer
      if(this.order.discount.code === discountCode) { return false; }
      
      axios({
        method: 'post',
        url: 'http://localhost/osadnicy1/index.php',
        data: {
          discountCode
        }
      })
      .then(res => {
        const obj = res.data;

        Array.from(obj).forEach(el => {
          const { discount_code, isValid } = el;
          if(discount_code === discountCode && isValid === '1') {
            this.order.discount.code = discountCode;
            this.order.discount.valid = true;
            this.order.discount.isUsed = true;
            this.calculateDiscount();
          } else {
            this.order.discount.valid = false;
          }
        });
      })
      .catch(err => console.log(err));
    },
    confirmOrder() {
      /*
      firstly, invoke function responsible for validating client data 
      and inserting them to the clientData object if are not empty
      */
      this.setClientData();

      const delivery = this.setDeliveryMethod();
      const payment = this.setPaymentMethod();
      // if delivery or payment are falsy, return false to prevent invoking other functions
      if(!delivery || !payment) { return false; }
      
      this.submitOrder();
    },
    submitOrder() {

      const correctInputs = Object.values(this.order.clientData).length;
      const clientFormInputs = Object.values(this.$refs.clientForm.clientData).length;
      
      // send form if user correctly filled out all required fields
      if(correctInputs !== clientFormInputs) { return false; }

      this.order.sending = !this.order.sending;

      const orderData = {
        client: this.order.clientData,
        orderDetails: { 
          deliveryMethod: this.order.deliveryMethodSelect,
          deliveryCost: this.order.deliveryCosts,
          paymentMethod: this.order.paymentMethod,
          productName: this.order.product.name,
          productQuantity: this.order.product.quantity,
          productPrice: this.order.product.price,
          totalCosts: this.order.orderTotalCosts
        }
      }; 

      axios({
        method: 'post',
        url: 'http://localhost/osadnicy1/index.php',
        data: orderData
      })
      .then(res => {
        /* 
          !!! FUNCTION SETIMEOUT is used only for simulating request to the server !!!
        */
        setTimeout(() => {
          this.order.orderNumber = res.data;
          this.toggleModal('confirm-modal');
          this.order.sending = !this.order.sending;
          this.formSend = true;
        }, 3000);
      })
      .catch(err => console.log(err));
    }
  }
}
</script>

<style lang="scss">
/* Vue formulate styles */
@import '../node_modules/@braid/vue-formulate/themes/snow/snow.scss';
/* Main styles */
@import './scss/style.scss';
</style>
