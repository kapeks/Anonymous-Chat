<template>
  <div v-if="viewState === 'form'">
    <h1 style="text-align: center;">Welcome to Anonym Chat</h1>
    <div v-if="errors">
      <ul>
        <li v-for="(messages, field) in errors" :key="field">
          {{ messages[0] }}
        </li>
      </ul>
    </div>

    <fieldset>
      <legend>Ваши данные</legend>
      <label>
        Пол:
        <select v-model="user.gender" required>
          <option value="" disabled>Выберите</option>
          <option value="male">Мужской</option>
          <option value="female">Женский</option>
        </select>
      </label>

      <label>
        Возраст:
        <select v-model="user.age" required>
          <option value="" disabled>Выберите</option>
          <option value="under_16">До 16 лет</option>
          <option value="17_22">17-22</option>
          <option value="23_27">23-27</option>
          <option value="28_35">28-35</option>
          <option value="36_plus">36 и старше</option>
        </select>
      </label>

    </fieldset>

    <fieldset>
      <legend>Ищем собеседника</legend>
      <label>
        Пол собеседника:
        <select v-model="partner.gender" required>
          <option value="" disabled>Выберите</option>
          <option value="male">Мужской</option>
          <option value="female">Женский</option>
        </select>
      </label>


      <label>
        Возраст собеседника:
        <select v-model="partner.age" required>
          <option value="" disabled>Выберите</option>
          <option value="under_16">До 16 лет</option>
          <option value="17_22">17-22</option>
          <option value="23_27">23-27</option>
          <option value="28_35">28-35</option>
          <option value="36_plus">36 и старше</option>
        </select>
      </label>

    </fieldset>
    <div>
      <button @click.prevent="storeUserParams" class="send-button" href="#">Отправить</button>
      
    </div>
  </div>

  <div v-if="viewState === 'searching'">
    <p style="text-align: center;">ищем собеседника!
      <a @click.prevent="reloadPage" href="#" class="rounded-lg block w-48 bg-sky-400 text-white text-center py-2">
        отменить поиск
      </a>
    </p>
  </div>

  <div v-if="viewState === 'chat'">
    <div class="chat-container">
      <div class="chat-box">
        <!-- Информация о состоянии чата -->
        <div class="chat-header">
          <span v-if="connected" class="status connected">🟢 Собеседник найден</span>
          <span v-else class="status disconnected">🔴 Собеседник отключился</span>
          <a @click.prevent="reloadPage" class="end-chat">Завершить чат</a>
        </div>

        <!-- Окно с сообщениями -->
        <div class="messages-container" ref="messagesContainer">
          <div v-for="(message, index) in messages" :key="index" class="message-container">
            <div :class="['message', message.sender === 'me' ? 'right' : 'left']">
              <p>{{ message.text }}</p>
            </div>
          </div>
        </div>

        <!-- Поле ввода -->
        <div class="input-container" v-if="!disabled">
          <input type="text" v-model="body" placeholder="Введите сообщение..." class="message-input" />
          <button @click.prevent="message" class="send-button">Отправить</button>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import axios from 'axios';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

export default {
  name: 'Chat',
  props: [
    'userChatId'
  ],

  data() {
    return {
      body: '', 
      errors: {}, 
      connected: '', 
      disabled: '', 
      messages: [], 
      companionChatId: '',
      viewState: 'form', //управление состоянием отображения.
      user: {  
        gender: '',
        age: ''
      },
      partner: {
        gender: '',
        age: ''
      }
    }
  },

  created() {  // подписка на канал и возврат ответов от event
    window.Echo.channel(this.userChatId)
      .listen('.anonym-chat', (res) => {
        console.log('Received event:', res);

        this.addcompanionChatIdMessage(res.message);
        this.companionChatId = res.companionChatId;

        if (res.disabled) {
          this.disabled = res.disabled;
          this.connected = '';
        }

        if (res.connected) {
          this.connected = res.connected;
          this.viewState = 'chat';
        }
      })

    // Обработчик закрытия окна браузера
    window.addEventListener('beforeunload', this.handleWindowClose);
  },

  // удаляем обработчик события после срабатывания beforeunload.
  beforeDestroy() {
    window.removeEventListener('beforeunload', this.handleWindowClose);
  },

  methods: {
    storeUserParams() {   //отправка параметров формы для поиска собеседника 
      axios.post('/user', { user: this.user, parent: this.partner, userChatId: this.userChatId }, {
        headers: { 
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
      })
        .then(res => {
          if (!this.companionChatId) {
            this.viewState = 'searching';
          }
        })
        .catch(error => {
          if (error.response && error.response.status === 422) {
            this.errors = error.response.data.errors;
          }
        });
    },
    message() {   // отправка сообщения собеседнику
      axios.post('/message', { body: this.body, userChatId: this.userChatId, companionChatId: this.companionChatId }, {
        headers: { // Добавляем CSRF-токен для защиты от атак
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
      })
        .then(res => {
          this.addMyMessage(res.data.myMessage);
          this.body = ''
        })
    },

    addMyMessage(text) {
      this.messages.push({
        text,
        sender: 'me' // Метка для отправителя
      });
      this.scrollToBottom();
    },

    //сохранение сообщений собеседника в обищй массив
    addcompanionChatIdMessage(text) {
      if (!text || text.trim() === '') return; // проверяет содержание строки на пустоту.
      this.messages.push({
        text,
        sender: 'companionChatId', // Метка для собеседника
      });
      this.scrollToBottom(); 
    },

    // Метод для уведомления сервера об отключении пользователя
    handleWindowClose() {
      if (this.viewState === 'searching' || this.viewState === 'chat') {
        const data = this.companionChatId
          ? { companionChatId: this.companionChatId }
          : { userChatId: this.userChatId };

        axios.post('/disconnect', data)
          .then(() => {
            console.log('Отключение зарегистрировано');
          })
          .catch(error => {
            console.error('Ошибка при уведомлении об отключении:', error);
          });
      }
    },

    // сбрасывание состояния при закрытии чата.
    reloadPage() {
      window.location.replace(window.location.href);
    },

    // автоматически скрол сообщений вниз, если поле заполнено.
    scrollToBottom() {
      this.$nextTick(() => {
        const container = this.$refs.messagesContainer;
        if (container) {
          container.scrollTop = container.scrollHeight;
        }
      });
    }

  }
}
</script>
<style>
/* Контейнер всего чата */
.chat-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  background: #f5f5f5;
}

/* Основной блок чата */
.chat-box {
  width: 100%;
  max-width: 500px;
  height: 90vh;
  display: flex;
  flex-direction: column;
  background: white;
  border-radius: 10px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  overflow: hidden;
}

/* Заголовок чата */
.chat-header {
  padding: 10px 15px;
  background: #0077b6;
  color: white;
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-weight: bold;
}

/* Статусы подключения */
.status {
  font-size: 14px;
}

.connected {
  color: #d1e7dd;
}

.disconnected {
  color: #f87171;
}

/* Кнопка завершения чата */
.end-chat {
  cursor: pointer;
  color: #fff;
  font-size: 14px;
  text-decoration: underline;
}

.end-chat:hover {
  color: #ffcd29;
}

/* Окно сообщений */
.messages-container {
  flex: 1;
  padding: 10px;
  overflow-y: auto;
  display: flex;
  flex-direction: column;
  overflow-x: hidden; 
  word-wrap: break-word; 
  padding: 10px; 
}

/* Поле ввода */
.input-container {
  display: flex;
  border-top: 1px solid #ddd;
  padding: 10px;
  background: #f8f8f8;
}

.message-input {
  flex: 1;
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 5px;
  font-size: 14px;
}

.send-button {
  margin-left: 10px;
  background: #0077b6;
  color: white;
  border: none;
  padding: 8px 15px;
  border-radius: 5px;
  cursor: pointer;
}

/* Эффект при наведении на кнопку отправки */
.send-button:hover {
  background: #005c91;
}

/* Контейнер для сообщения */
.message-container {
  display: flex;
  margin: 10px 0;
}

/* Сообщения собеседника (слева) */
.left {
  justify-content: flex-start;
  background-color: #f0f0f0;
  border-radius: 8px;
  padding: 10px;
  max-width: 70%;
}

/* Сообщения пользователя (справа) */
.right {
  justify-content: flex-end;
  background-color: #d1e7dd;
  border-radius: 8px;
  padding: 10px;
  max-width: 70%;
  margin-left: auto;
}
</style>