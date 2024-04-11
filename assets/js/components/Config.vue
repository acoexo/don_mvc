<template>
    <div class="home">
      <form method="post" @submit.prevent="llamarFuncionPHP">
        <div class="content">
          <div class="title--div">
            <p class="title"><span class="title--span">Token</span></p>
          </div>
          <div class="input--div">
            <input type="text" id="token" class="input" v-model="token" placeholder="Enter your token" />
          </div>
          <div class="button--div">
            <button type="submit" class="button">Submit</button>
          </div>
        </div>
      </form>
    </div>
  </template>
  
  <script setup>
  import { defineEmits, onMounted, ref } from 'vue';
  
  const emits = defineEmits(['title']);
  
  onMounted(() => {
    emits('title', 'Configuration');
  });
  const token = ref('');
  
  function llamarFuncionPHP() {
    jQuery.ajax({
      url:ajaxurl,
      type: 'POST',
      data: {
        action: 'don_server_post',
        token: `${token.value}`
      },
      success: function (response) {
        console.log('Respuesta del servidor: ', response.data.message);
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error('Error al llamar a la función de PHP:', errorThrown);
      }
    });
  }
  </script>
  
  <style lang="css" scoped>
  form {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: center;
    flex-shrink: 1;
    flex-grow: 1;
    align-self: stretch;
    width: 550px;
    height: 194px;
    opacity: 1;
    background-color: rgb(255, 255, 255);
    border-radius: 2px;
    box-shadow: rgba(3, 3, 3, 0.1) 2px 0px 10px;
    border: 0px;
  }
  
  .content {
    margin: 1rem;
  }
  
  .title--span {
    font-weight: 500;
  }
  
  .input--div {
    max-width: 20rem;
    width: 20rem;
    height: 2.5rem;
    margin: 1rem 0;
  }
  
  .input {
    width: 100%;
    height: 100%;
    border: none;
    outline: none;
    box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
  }
  
  ::placeholder {
    color: rgb(204, 204, 204)
  }
  
  .button--div {
    max-width: 20rem;
    width: 20rem;
    height: 2.5rem;
    margin: 1rem 0;
    box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
  }
  
  .button {
    width: 100%;
    height: 100%;
    border: none;
    outline: none;
    background-color: #000;
    color: #fff
  }
  </style>
  