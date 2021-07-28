<template>
  <section class="section">
    <header class="section__header">
      <i class="section__icon fas fa-user-alt"></i>
      <h3 class="section__title">{{ title }}</h3>
    </header>
    <div class="form">
      <div class="form__login">
        <button type="button" class="btn btn__primary" @click="toggleModal">Logowanie</button>
        <p class="form__text">Masz już konto? Kliknij żeby się zalogować.</p>
        <div>
          <div class="custom-checkbox">
            <div class="custom-checkbox__item">
              <input 
                class="custom-checkbox__input"
                v-model="newUser.createNewAccount"
                type="checkbox" 
                id="new-account"
              >
              <span class="custom-checkbox__mark">
                <i class="fas fa-check"></i>
              </span>
            </div>  
            <label class="custom-checkbox__label" for="new-account">Stwórz nowe konto</label>
          </div>            
        </div>
      </div>
      <div class="form__area">
        <div class="form__box">
          <FormulateInput
            type="email"
            name="email"
            placeholder="Email *"
            validation="email"
            v-model="clientData.email"
            :show-errors="validation.email"
            :validation-messages="{
              email: 'Proszę podać poprawny adres email'
            }"
          />
        </div>
        <transition name="fade">
        <div class="form__box" v-if="newUser.createNewAccount">
          <FormulateInput
            type="password"
            name="password"
            placeholder="Hasło"
            v-model="newUser.password"
          />
        </div>
        </transition>
        <transition name="fade">
        <div class="form__box" v-if="newUser.createNewAccount">
          <FormulateInput
            type="password"
            name="passwordConfirm"
            placeholder="Potwierdź hasło"
            v-model="newUser.passwordConfirm"
          />
        </div>
        </transition>
        <div class="form__box">
          <FormulateInput
            type="text"
            name="firstName"
            placeholder="Imię *"
            :validation="[['matches', /^[a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŹŻ]/]]"
            v-model="clientData.firstName"
            :show-errors="validation.firstName"
            :validation-messages="{
              matches: 'Proszę podać imię'
            }"
          />
        </div>
        <div class="form__box">
          <FormulateInput
            type="text"
            name="lastName"
            placeholder="Nazwisko *"
            :validation="[['matches', /^[a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŹŻ]/]]"
            error-behavior="value"
            v-model="clientData.lastName"
            :show-errors="validation.lastName"
            :validation-messages="{
              matches: 'Proszę podać nazwisko'
            }"
          />
        </div>
        <div class="form__box">
          <FormulateInput
            v-model="clientData.state"
            :options="['Polska', 'Niemcy', 'Francja', 'Wielka Brytania', 'Czechy', 'Austria']"
            type="select"
          />
        </div>
        <div class="form__box">
          <FormulateInput
            type="text"
            placeholder="Ulica i numer domu*"
            :validation="[['matches', /^[a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŹŻ]+\s[0-9]{1,4}$/]]"
            error-behavior="value"
            v-model="clientData.street"
            :show-errors="validation.street"
            :validation-messages="{
              matches: 'Proszę podać nazwę ulicy i numer domu'
            }"
          />
        </div>
        <div class="form__box form__inputs">
          <FormulateInput
            type="text"
            name="zipCode"
            placeholder="Kod pocztowy *"
            :validation="[['matches', /^[0-9]{2}-[0-9]{3}$/]]"
            error-behavior="value"
            v-model="clientData.zipCode"
            :show-errors="validation.zipCode"
            :validation-messages="{
              matches: 'Format kodu np. 55-555'
            }"
          />
          <FormulateInput
            type="text"
            name="city"
            placeholder="Miasto *"
            :validation="[['matches', /^[a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŹŻ]/]]"
            error-behavior="value"
            v-model="clientData.city"
            :show-errors="validation.city"
            :validation-messages="{
              matches: 'Proszę podać nazwę miasta'
            }"
          />
        </div>
        <div class="form__box">
          <FormulateInput
            type="tel"
            name="phoneNumber"
            placeholder="Telefon *"
            :validation="[['matches', /^[0-9]{9}$/]]"
            error-behavior="value"
            v-model="clientData.phoneNumber"
            :show-errors="validation.phoneNumber"
            :validation-messages="{
              matches: 'Poprawny format np. 111222333'
            }"
          />
        </div>
      </div>
      <div>
        <div class="custom-checkbox">
          <div class="custom-checkbox__item">
            <input 
              class="custom-checkbox__input"
              v-model="newDeliveryAddress"
              type="checkbox" 
              id="new-delivery-address"
            >
            <span class="custom-checkbox__mark">
              <i class="fas fa-check"></i>
            </span>
          </div>  
          <label class="custom-checkbox__label" for="new-delivery-address">Dostawa pod inny adres</label>
        </div> 
      </div>
      <!-- LOGIN MODAL WINDOW -->
      <Modal 
        modalName="login-modal"
        v-if="modalLogin"
        @toggle-modal="toggleModal"
      >
        <template #header>
          <div>
            <img src="../assets/smart-logo.png" alt="Smartbees" />
          </div>
        </template>
        <template #body>
          <form @submit="onSubmit" class="modal__login">
            <FormulateInput
              type="email"
              placeholder="Email"
            />
            <FormulateInput
              type="password"
              placeholder="Hasło"
            />
            <button type="submit" class="btn btn__login">Zaloguj się</button>
            <a class="modal__hyperlink" href="#">Nie pamiętasz hasła?</a>
          </form>
        </template>
      </Modal>
    </div>
  </section>
</template>

<script>

import Modal from './Modal.vue';

export default {
  name: 'ClientForm',
  props: {
    title: String,
    modalLogin: Boolean
  },
  components: {
    Modal
  },
  data() {
    return {
      show: false,
      newDeliveryAddress: false,
      newUser: {
        createNewAccount: true,
        password: '',
        passwordconfirm: ''
      },
      clientData: {
        email: '',
        firstName: '',
        lastName: '',
        state: 'Polska',
        zipCode: '',
        city: '',
        street: '',
        phoneNumber: ''
      },
      validation: {
        firstName: false,
        lastName: false,
        email: false,
        zipCode: false,
        city: false,
        street: false,
        phoneNumber: false
      }
    }
  },
  methods: {
    setFieldError(inputName) {
      this.validation[inputName] = true;
    },
    resetFieldError() {
      this.validation = {
        firstName: false,
        lastName: false,
        email: false,
        zipCode: false,
        city: false,
        street: false,
        phoneNumber: false
      }
    },
    toggleModal() {
      this.$emit('toggle-modal', 'login-modal');
    },
    onSubmit(e) {
      e.preventDefault();
      this.toggleModal();
    },
    resetClientData() {
      this.clientData = {
        email: '',
        firstName: '',
        lastName: '',
        state: 'Polska',
        zipCode: '',
        city: '',
        street: '',
        phoneNumber: ''
      }
    }
  }
}
</script>